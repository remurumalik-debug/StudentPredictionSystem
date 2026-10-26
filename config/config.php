<?php
declare(strict_types=1);

define('APP_NAME', 'CUSTECH Student Performance Prediction System');
define('DB_HOST', 'localhost');
define('DB_NAME', 'custech_student_prediction');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$basePath = preg_replace('#/(admin|lecturer|student|department|reports|exports)$#', '', $basePath);
define('BASE_URL', $basePath === '' ? '' : $basePath);

date_default_timezone_set('Africa/Lagos');
