# Development of an Intelligent Student Performance Prediction System for CUSTECH

This project is a complete PHP 8 and MySQL final-year B.Sc demonstration system that runs on XAMPP without Python, Node.js, Composer, or external server tools.

## Installation

1. Install XAMPP.
2. Copy the `StudentPredictionSystem` folder into `xampp/htdocs/`.
3. Open `http://localhost/phpmyadmin`.
4. Import `database/database.sql`.
5. Confirm database settings in `config/config.php`.
6. Start Apache and MySQL.
7. Open `http://localhost/StudentPredictionSystem`.

## Default Accounts

| Role | Username | Password |
| --- | --- | --- |
| Super Admin | `superadmin` | `super123` |
| Administrator | `admin` | `admin123` |
| Lecturer | `lecturer` | `lecturer123` |
| Student | `student` | `student123` |
| Department Officer | `department` | `department123` |

The seed passwords use a `demo:` marker so the system can be imported easily. On first successful login, the app automatically replaces the marker with a PHP `password_hash()` value.

## Features

- Authentication, logout, forgot password, reset password, remember me, CSRF protection, and role-based access.
- Admin dashboard with cards, charts, latest activity, students, departments, courses, predictions, reports, and CSV export.
- Student sign up, profile, prediction result history, AI prediction form, print result, own activity history, and CGPA calculator.
- Lecturer course dashboard, score upload, attendance entry, and risk visibility.
- Department analytics, grade distribution, risk distribution, and printable reports.
- PHP-only weighted prediction engine with predicted grade, GPA, confidence, risk level, and recommendations.
- MySQL schema covering users, students, lecturers, faculties, departments, courses, results, attendance, assignments, quizzes, tests, predictions, recommendations, audit logs, notifications, sessions, roles, permissions, settings, login attempts, and password resets.

## Chrome Link

After copying to XAMPP and importing the database, open:

`http://localhost/StudentPredictionSystem`

## Notes for Defense

The AI component is intentionally transparent. Each academic factor is normalized, multiplied by an academic importance weight, adjusted for risk factors, and mapped to a class of degree. This makes it simple to defend because the examiner can trace how every input affects the final output.
