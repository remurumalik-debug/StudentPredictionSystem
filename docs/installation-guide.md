# Installation Guide

## Requirements

- XAMPP with Apache, PHP 8+, and MySQL.
- Chrome or another modern browser.

## Steps

1. Copy `StudentPredictionSystem` to `C:\xampp\htdocs\StudentPredictionSystem`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Click Import.
5. Select `C:\xampp\htdocs\StudentPredictionSystem\database\database.sql`.
6. Click Go.
7. Open `http://localhost/StudentPredictionSystem`.
8. Login with `admin` and `admin123`.

## Database Configuration

Default XAMPP credentials are already configured:

```php
DB_HOST = localhost
DB_NAME = custech_student_prediction
DB_USER = root
DB_PASS = empty
```

If your MySQL password is different, edit `config/config.php`.
