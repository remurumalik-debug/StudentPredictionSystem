<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_login();
if (in_array($user['role_name'], ['Super Admin','Administrator','Department Officer'], true)) {
    $rows = fetch_all('SELECT s.*, d.name department FROM students s LEFT JOIN departments d ON d.id=s.department_id ORDER BY s.matric_no');
} else {
    $rows = fetch_all('SELECT s.*, d.name department FROM students s LEFT JOIN departments d ON d.id=s.department_id WHERE s.user_id = ? ORDER BY s.matric_no', [$user['id']]);
}
$title = 'Student Report';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="d-flex justify-content-between mb-3"><h1 class="h5">Student Report</h1><button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Print</button></div>
<div class="table-wrap"><table class="table table-bordered"><thead><tr><th>Matric No</th><th>Name</th><th>Department</th><th>Level</th><th>Email</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['matric_no']) ?></td><td><?= e($r['first_name'].' '.$r['last_name']) ?></td><td><?= e($r['department']) ?></td><td><?= e($r['level']) ?></td><td><?= e($r['email']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
