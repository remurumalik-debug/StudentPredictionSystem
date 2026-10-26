<?php require_once __DIR__ . '/includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(APP_NAME) ?></title>
    <link href="<?= e(url('assets/vendor/bootstrap/css/bootstrap.min.css')) ?>" rel="stylesheet">
    <link href="<?= e(url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<section class="hero">
    <div class="hero-inner">
        <p class="text-uppercase fw-bold">Confluence University of Science and Technology</p>
        <h1>Intelligent Student Performance Prediction System</h1>
        <p class="lead col-lg-8">Predict academic class, risk level, confidence, and intervention recommendations using student academic and learning indicators.</p>
        <div class="d-flex gap-2 flex-wrap mt-4">
            <a class="btn btn-primary btn-lg" href="<?= e(url('login.php')) ?>">Open System</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('signup.php')) ?>">Student Sign Up</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('docs/system-documentation.md')) ?>">View Documentation</a>
        </div>
    </div>
</section>
</body>
</html>
