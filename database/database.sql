CREATE DATABASE IF NOT EXISTS custech_student_prediction CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE custech_student_prediction;

DROP TABLE IF EXISTS password_resets, login_attempts, audit_logs, notifications, sessions, predictions, recommendations, attendance, assignments, quizzes, tests, results, courses, lecturers, students, users, permissions, roles, departments, faculties, settings;

CREATE TABLE roles (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(60) UNIQUE NOT NULL, description VARCHAR(255));
CREATE TABLE permissions (id INT AUTO_INCREMENT PRIMARY KEY, role_id INT, permission_name VARCHAR(100), FOREIGN KEY (role_id) REFERENCES roles(id));
CREATE TABLE faculties (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, code VARCHAR(20) UNIQUE, created_at DATETIME);
CREATE TABLE departments (id INT AUTO_INCREMENT PRIMARY KEY, faculty_id INT NOT NULL, name VARCHAR(120) NOT NULL, code VARCHAR(20) UNIQUE, created_at DATETIME, FOREIGN KEY (faculty_id) REFERENCES faculties(id));
CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, role_id INT NOT NULL, username VARCHAR(60) UNIQUE NOT NULL, email VARCHAR(120) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL, status ENUM('active','inactive') DEFAULT 'active', last_login DATETIME NULL, created_at DATETIME, FOREIGN KEY (role_id) REFERENCES roles(id));
CREATE TABLE students (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NULL, department_id INT, matric_no VARCHAR(40) UNIQUE NOT NULL, first_name VARCHAR(80), last_name VARCHAR(80), email VARCHAR(120), phone VARCHAR(40), gender VARCHAR(20), age INT, level VARCHAR(20), address TEXT, passport VARCHAR(255), created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (department_id) REFERENCES departments(id));
CREATE TABLE lecturers (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, department_id INT, staff_no VARCHAR(40), first_name VARCHAR(80), last_name VARCHAR(80), email VARCHAR(120), created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (department_id) REFERENCES departments(id));
CREATE TABLE courses (id INT AUTO_INCREMENT PRIMARY KEY, department_id INT, code VARCHAR(20), title VARCHAR(160), unit INT, semester VARCHAR(40), level VARCHAR(20), created_at DATETIME, FOREIGN KEY (department_id) REFERENCES departments(id));
CREATE TABLE results (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, course_id INT, session_year VARCHAR(20), semester VARCHAR(40), ca_score DECIMAL(5,2), exam_score DECIMAL(5,2), total_score DECIMAL(5,2), grade VARCHAR(5), gpa_point DECIMAL(3,2), created_at DATETIME, FOREIGN KEY (student_id) REFERENCES students(id), FOREIGN KEY (course_id) REFERENCES courses(id));
CREATE TABLE attendance (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, course_id INT, attendance_date DATE, status VARCHAR(20), created_at DATETIME, FOREIGN KEY (student_id) REFERENCES students(id), FOREIGN KEY (course_id) REFERENCES courses(id));
CREATE TABLE assignments (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, course_id INT, score DECIMAL(5,2), max_score DECIMAL(5,2) DEFAULT 100, created_at DATETIME);
CREATE TABLE quizzes (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, course_id INT, score DECIMAL(5,2), max_score DECIMAL(5,2) DEFAULT 100, created_at DATETIME);
CREATE TABLE tests (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, course_id INT, score DECIMAL(5,2), max_score DECIMAL(5,2) DEFAULT 100, created_at DATETIME);
CREATE TABLE predictions (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, input_data JSON, predicted_grade VARCHAR(60), predicted_gpa DECIMAL(4,2), probability_score DECIMAL(5,2), confidence INT, risk_level VARCHAR(20), recommendations TEXT, created_by INT, created_at DATETIME, FOREIGN KEY (student_id) REFERENCES students(id), FOREIGN KEY (created_by) REFERENCES users(id));
CREATE TABLE recommendations (id INT AUTO_INCREMENT PRIMARY KEY, prediction_id INT, recommendation TEXT, created_at DATETIME, FOREIGN KEY (prediction_id) REFERENCES predictions(id));
CREATE TABLE notifications (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, title VARCHAR(160), message TEXT, is_read TINYINT DEFAULT 0, created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE audit_logs (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, action VARCHAR(120), details TEXT, ip_address VARCHAR(60), created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE sessions (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, token VARCHAR(255), expires_at DATETIME, created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE password_resets (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, token VARCHAR(255), expires_at DATETIME, used_at DATETIME NULL, created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE login_attempts (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80), ip_address VARCHAR(60), created_at DATETIME);
CREATE TABLE settings (id INT AUTO_INCREMENT PRIMARY KEY, setting_key VARCHAR(100) UNIQUE, setting_value TEXT);

INSERT INTO roles (name, description) VALUES
('Super Admin','Full system owner'),('Administrator','University administrator'),('Lecturer','Course lecturer'),('Student','Student user'),('Department Officer','Department analytics officer');

INSERT INTO faculties (name, code, created_at) VALUES
('Faculty of Computing and Informatics','FCI',NOW()),('Faculty of Engineering','ENG',NOW()),('Faculty of Science','SCI',NOW());

INSERT INTO departments (faculty_id, name, code, created_at) VALUES
(1,'Computer Science','CSC',NOW()),(1,'Software Engineering','SEN',NOW()),(2,'Civil Engineering','CVE',NOW()),(3,'Mathematics','MTH',NOW());

INSERT INTO users (role_id, username, email, password_hash, created_at) VALUES
(1,'superadmin','superadmin@custech.edu.ng','demo:super123',NOW()),
(2,'admin','admin@custech.edu.ng','demo:admin123',NOW()),
(3,'lecturer','lecturer@custech.edu.ng','demo:lecturer123',NOW()),
(4,'student','student@custech.edu.ng','demo:student123',NOW()),
(5,'department','department@custech.edu.ng','demo:department123',NOW());

INSERT INTO students (user_id, department_id, matric_no, first_name, last_name, email, phone, gender, age, level, address, created_at) VALUES
(4,1,'CST/CSC/2022/001','Amina','Usman','student@custech.edu.ng','08030000001','Female',21,'300','Osara, Kogi State',NOW()),
(NULL,1,'CST/CSC/2022/002','David','Ojo','david.ojo@example.com','08030000002','Male',22,'300','Lokoja',NOW()),
(NULL,2,'CST/SEN/2022/005','Maryam','Bello','maryam.bello@example.com','08030000003','Female',20,'300','Okene',NOW()),
(NULL,1,'CST/CSC/2021/014','Samuel','Ade','samuel.ade@example.com','08030000004','Male',23,'400','Abuja',NOW());

INSERT INTO lecturers (user_id, department_id, staff_no, first_name, last_name, email, created_at) VALUES
(3,1,'CUSTECH/STAFF/010','Grace','Ibrahim','lecturer@custech.edu.ng',NOW());

INSERT INTO courses (department_id, code, title, unit, semester, level, created_at) VALUES
(1,'CSC301','Database Management Systems',3,'First Semester','300',NOW()),
(1,'CSC305','Artificial Intelligence',3,'First Semester','300',NOW()),
(1,'CSC311','Software Engineering',3,'Second Semester','300',NOW()),
(2,'SEN303','Web Application Development',3,'First Semester','300',NOW());

INSERT INTO predictions (student_id, input_data, predicted_grade, predicted_gpa, probability_score, confidence, risk_level, recommendations, created_by, created_at) VALUES
(1, JSON_OBJECT('attendance',88,'previous_cgpa',3.8), 'Second Class Upper', 3.82, 76.40, 91, 'Low', 'Maintain attendance.\nPractice past questions.', 2, NOW()),
(2, JSON_OBJECT('attendance',58,'previous_cgpa',2.1), 'At Risk', 1.72, 34.30, 74, 'High', 'Increase attendance.\nMeet level adviser.\nImprove assignments.', 2, NOW()),
(3, JSON_OBJECT('attendance',72,'previous_cgpa',3.0), 'Second Class Lower', 2.95, 59.10, 84, 'Medium', 'Improve test preparation.\nIncrease study hours.', 2, NOW());

INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES
(2,'Seed Data','Initial database imported','localhost',NOW());
