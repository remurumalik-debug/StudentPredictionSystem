<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator']);
$rows = fetch_all('SELECT p.*, s.matric_no, CONCAT(s.first_name," ",s.last_name) student FROM predictions p JOIN students s ON s.id=p.student_id ORDER BY p.created_at DESC');
$title = 'Predictions';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="d-flex justify-content-between mb-3"><a class="btn btn-primary" href="<?= e(url('student/prediction.php')) ?>"><i class="bi bi-stars"></i> New Prediction</a><a class="btn btn-outline-success" href="<?= e(url('exports/predictions_csv.php')) ?>"><i class="bi bi-download"></i> CSV</a></div>
<div class="table-wrap"><table class="table table-hover"><thead><tr><th>Student</th><th>Grade</th><th>GPA</th><th>Confidence</th><th>Risk</th><th>Date</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['matric_no'].' - '.$r['student']) ?></td><td><?= e($r['predicted_grade']) ?></td><td><?= e($r['predicted_gpa']) ?></td><td><?= e($r['confidence']) ?>%</td><td class="risk-<?= e(strtolower($r['risk_level'])) ?>"><?= e($r['risk_level']) ?></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
