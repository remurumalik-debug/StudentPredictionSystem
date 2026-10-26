<?php $user = current_user(); ?>
<!doctype html>
<html lang="en" data-theme="<?= e($_COOKIE['theme'] ?? 'light') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? APP_NAME) ?></title>
    <link href="<?= e(url('assets/vendor/bootstrap/css/bootstrap.min.css')) ?>" rel="stylesheet">
    <link href="<?= e(url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')) ?>" rel="stylesheet">
    <link href="<?= e(url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<div class="app-shell">
<?php if ($user): ?>
    <aside class="sidebar">
        <a class="brand" href="<?= e(url('dashboard.php')) ?>"><i class="bi bi-mortarboard-fill"></i><span>PREDICTION SYSTEM FOR CUSTECH</span></a>
        <nav>
            <a href="<?= e(url('dashboard.php')) ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <?php if (in_array($user['role_name'], ['Super Admin','Administrator'], true)): ?>
                <a href="<?= e(url('admin/students.php')) ?>"><i class="bi bi-people"></i> Students</a>
                <a href="<?= e(url('admin/departments.php')) ?>"><i class="bi bi-building"></i> Departments</a>
                <a href="<?= e(url('admin/courses.php')) ?>"><i class="bi bi-journal-text"></i> Courses</a>
                <a href="<?= e(url('admin/predictions.php')) ?>"><i class="bi bi-cpu"></i> Predictions</a>
                <a href="<?= e(url('admin/reports.php')) ?>"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
            <?php endif; ?>
            <?php if ($user['role_name'] === 'Lecturer'): ?>
                <a href="<?= e(url('lecturer/index.php')) ?>"><i class="bi bi-book"></i> My Courses</a>
                <a href="<?= e(url('lecturer/scores.php')) ?>"><i class="bi bi-pencil-square"></i> Scores</a>
                <a href="<?= e(url('lecturer/attendance.php')) ?>"><i class="bi bi-calendar-check"></i> Attendance</a>
            <?php endif; ?>
            <?php if ($user['role_name'] === 'Student'): ?>
                <a href="<?= e(url('student/index.php')) ?>"><i class="bi bi-person-badge"></i> Profile</a>
                <a href="<?= e(url('student/prediction.php')) ?>"><i class="bi bi-stars"></i> Prediction</a>
                <a href="<?= e(url('student/cgpa.php')) ?>"><i class="bi bi-calculator"></i> CGPA</a>
            <?php endif; ?>
            <?php if ($user['role_name'] === 'Department Officer'): ?>
                <a href="<?= e(url('department/index.php')) ?>"><i class="bi bi-bar-chart"></i> Analytics</a>
                <a href="<?= e(url('department/reports.php')) ?>"><i class="bi bi-printer"></i> Reports</a>
            <?php endif; ?>
            <a href="<?= e(url('activities.php')) ?>"><i class="bi bi-clock-history"></i> Activities</a>
        </nav>
    </aside>
<?php endif; ?>
<main class="<?= $user ? 'content' : 'public-content' ?>">
<?php if ($user): ?>
    <header class="topbar">
        <div>
            <strong><?= e($title ?? 'Dashboard') ?></strong>
            <small><?= e($user['role_name']) ?></small>
        </div>
        <div class="top-actions">
            <button class="btn btn-sm btn-outline-secondary" id="themeToggle" title="Toggle theme"><i class="bi bi-moon-stars"></i></button>
            <a class="btn btn-sm btn-outline-danger" href="<?= e(url('logout.php')) ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </header>
<?php endif; ?>
<?php foreach (consume_flash() as $msg): ?>
    <div class="alert alert-<?= e($msg['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($msg['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endforeach; ?>
