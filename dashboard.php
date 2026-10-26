<?php
require_once __DIR__ . '/includes/functions.php';
$user = require_login();

if ($user['role_name'] === 'Lecturer') redirect('lecturer/index.php');
if ($user['role_name'] === 'Student') redirect('student/index.php');
if ($user['role_name'] === 'Department Officer') redirect('department/index.php');

$title = 'Admin Dashboard';
$gradeRows = fetch_all('SELECT predicted_grade, COUNT(*) total FROM predictions GROUP BY predicted_grade');
$riskRows = fetch_all('SELECT risk_level, COUNT(*) total FROM predictions GROUP BY risk_level');
require __DIR__ . '/views/partials/header.php';
?>
<div class="row g-3 mb-3">
    <?php foreach ([
        ['Students', table_count('students'), 'bi-people'],
        ['Departments', table_count('departments'), 'bi-building'],
        ['Courses', table_count('courses'), 'bi-journal-text'],
        ['Predictions', table_count('predictions'), 'bi-cpu'],
        ['High Risk Students', table_count('predictions', 'risk_level = "High"'), 'bi-exclamation-triangle'],
        ['Faculties', table_count('faculties'), 'bi-diagram-3'],
    ] as $card): ?>
        <div class="col-md-4 col-xl-2"><div class="metric-card card"><div class="icon"><i class="bi <?= e($card[2]) ?>"></i></div><h3><?= e((string)$card[1]) ?></h3><p><?= e($card[0]) ?></p></div></div>
    <?php endforeach; ?>
</div>
<div class="row g-3">
    <div class="col-lg-6"><div class="card p-3"><h2 class="h6">Prediction Distribution</h2><canvas data-chart="pie" data-labels='<?= e(json_encode(array_column($gradeRows, 'predicted_grade'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($gradeRows, 'total')))) ?>'></canvas></div></div>
    <div class="col-lg-6"><div class="card p-3"><h2 class="h6">Risk Analysis</h2><canvas data-chart="bar" data-labels='<?= e(json_encode(array_column($riskRows, 'risk_level'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($riskRows, 'total')))) ?>'></canvas></div></div>
</div>
<div class="table-wrap mt-3">
    <h2 class="h6">Latest Activities</h2>
    <table class="table table-hover align-middle">
        <thead><tr><th>User</th><th>Action</th><th>Details</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach (fetch_all('SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC LIMIT 8') as $row): ?>
            <tr><td><?= e($row['username']) ?></td><td><?= e($row['action']) ?></td><td><?= e($row['details']) ?></td><td><?= e($row['created_at']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
