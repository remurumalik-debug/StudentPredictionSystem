# System Documentation

## Architecture

The system uses a simple MVC-style PHP structure:

- `config/` stores database and application settings.
- `includes/` stores shared helpers for authentication, database access, security, and formatting.
- `models/PredictionEngine.php` stores the intelligent prediction logic.
- `admin/`, `lecturer/`, `student/`, and `department/` store role-based modules.
- `reports/` and `exports/` store printable and downloadable reports.
- `database/database.sql` creates and seeds the MySQL database.

## Prediction Engine

The prediction engine considers:

- Continuous assessment (CA) score.
- Attendance percentage.
- Test score.
- Previous CGPA.
- Assignment score.
- Study hours per week.
- Health status and financial challenges (combined as a constraint flag).

The factors are weighted (CA 0.24, attendance 0.18, test 0.17, previous CGPA 0.16, assignments 0.10, study hours 0.08, constraints 0.07; total 1.0).

The engine returns:

- Predicted class of degree.
- Predicted GPA.
- Probability score.
- Confidence percentage.
- Risk level.
- Recommendations.

## Security

The project includes:

- PDO prepared statements.
- Password hashing.
- CSRF tokens on forms.
- Session regeneration on login.
- Output escaping.
- Basic brute-force login attempt limiting.
- Role-based authorization.

## ER Diagram

```mermaid
erDiagram
    ROLES ||--o{ USERS : owns
    USERS ||--o| STUDENTS : maps_to
    USERS ||--o| LECTURERS : maps_to
    FACULTIES ||--o{ DEPARTMENTS : contains
    DEPARTMENTS ||--o{ STUDENTS : admits
    DEPARTMENTS ||--o{ LECTURERS : employs
    DEPARTMENTS ||--o{ COURSES : offers
    STUDENTS ||--o{ RESULTS : earns
    COURSES ||--o{ RESULTS : has
    STUDENTS ||--o{ ATTENDANCE : records
    STUDENTS ||--o{ PREDICTIONS : receives
    PREDICTIONS ||--o{ RECOMMENDATIONS : generates
    USERS ||--o{ AUDIT_LOGS : creates
```

## Flowchart

```mermaid
flowchart TD
    A["User opens system"] --> B["Login"]
    B --> C{"Role"}
    C --> D["Admin Dashboard"]
    C --> E["Lecturer Dashboard"]
    C --> F["Student Dashboard"]
    C --> G["Department Dashboard"]
    F --> H["Enter academic indicators"]
    D --> H
    H --> I["Prediction Engine"]
    I --> J["Predicted Grade"]
    I --> K["Confidence"]
    I --> L["Risk Level"]
    I --> M["Recommendations"]
    J --> N["Save Prediction"]
    N --> O["Reports and Analytics"]
```

## Use Case Diagram

```mermaid
flowchart LR
    Admin["Administrator"] --> ManageStudents["Manage Students"]
    Admin --> ManageCourses["Manage Courses"]
    Admin --> RunPrediction["Run Prediction"]
    Admin --> Reports["Generate Reports"]
    Lecturer["Lecturer"] --> Scores["Enter Scores"]
    Lecturer --> Attendance["Record Attendance"]
    Lecturer --> Risk["View Risk Analysis"]
    Student["Student"] --> Profile["Manage Profile"]
    Student --> Predict["View Prediction"]
    Student --> CGPA["Calculate CGPA"]
    Dept["Department Officer"] --> Analytics["View Department Analytics"]
    Dept --> DeptReports["Print Department Reports"]
```

## Class Diagram

```mermaid
classDiagram
    class User {
      id
      username
      role
      login()
      logout()
    }
    class Student {
      matric_no
      department
      level
      viewPrediction()
    }
    class Lecturer {
      staff_no
      enterScores()
      recordAttendance()
    }
    class PredictionEngine {
      weights
      predict(input)
      recommendations()
    }
    class Report {
      print()
      exportCsv()
    }
    User <|-- Student
    User <|-- Lecturer
    Student --> PredictionEngine
    PredictionEngine --> Report
```

## Sequence Diagram

```mermaid
sequenceDiagram
    participant User
    participant Form
    participant Engine as PredictionEngine
    participant DB as MySQL
    User->>Form: Submit indicators
    Form->>Engine: predict(input)
    Engine-->>Form: grade, confidence, risk, recommendations
    Form->>DB: Save prediction
    DB-->>Form: Success
    Form-->>User: Display printable result
```

## Deployment Diagram

```mermaid
flowchart TD
    Browser["Chrome Browser"] --> Apache["Apache in XAMPP"]
    Apache --> PHP["PHP 8 Runtime"]
    PHP --> MySQL["MySQL Database"]
    PHP --> Files["Uploads and Assets"]
```

## Suggested AI Model Training Enhancement

For a stronger defense, historical student data can be used to train a Decision Tree or Random Forest offline. The trained model can then be exported to JSON and loaded by PHP at runtime. This deployment still requires no Python on the examiner's computer.
