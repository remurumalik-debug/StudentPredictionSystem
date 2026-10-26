<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_login();
if (in_array($user['role_name'], ['Super Admin','Administrator','Department Officer','Lecturer'], true)) {
    $rows = fetch_all('SELECT p.*, s.matric_no, CONCAT(s.first_name," ",s.last_name) student, d.name department FROM predictions p JOIN students s ON s.id=p.student_id LEFT JOIN departments d ON d.id=s.department_id WHERE p.risk_level IN ("High","Medium") ORDER BY FIELD(p.risk_level,"High","Medium"), p.probability_score');
} else {
    $rows = fetch_all('SELECT p.*, s.matric_no, CONCAT(s.first_name," ",s.last_name) student, d.name department FROM predictions p JOIN students s ON s.id=p.student_id LEFT JOIN departments d ON d.id=s.department_id WHERE s.user_id = ? AND p.risk_level IN ("High","Medium") ORDER BY FIELD(p.risk_level,"High","Medium"), p.probability_score', [$user['id']]);
}
$title = 'Risk Report';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="d-flex justify-content-between mb-3"><h1 class="h5">Academic Risk Report</h1><button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Print</button></div>
<div class="table-wrap"><table class="table table-bordered"><thead><tr><th>Student</th><th>Department</th><th>Risk</th><th>Prediction</th><th>Recommendations</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['matric_no'].' - '.$r['student']) ?></td><td><?= e($r['department']) ?></td><td class="risk-<?= e(strtolower($r['risk_level'])) ?>"><?= e($r['risk_level']) ?></td><td><?= e($r['predicted_grade']) ?></td><td><?= nl2br(e($r['recommendations'])) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
