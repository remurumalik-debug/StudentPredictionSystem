<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Department Officer']);
$grades = fetch_all('SELECT predicted_grade, COUNT(*) total FROM predictions GROUP BY predicted_grade');
$risks = fetch_all('SELECT risk_level, COUNT(*) total FROM predictions GROUP BY risk_level');
$best = fetch_all('SELECT s.matric_no, CONCAT(s.first_name," ",s.last_name) student, MAX(p.predicted_gpa) gpa FROM predictions p JOIN students s ON s.id=p.student_id GROUP BY s.id ORDER BY gpa DESC LIMIT 5');
$title = 'Department Analytics';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3"><div class="col-lg-6"><div class="card p-3"><h2 class="h6">Grade Distribution</h2><canvas data-chart="bar" data-labels='<?= e(json_encode(array_column($grades,'predicted_grade'))) ?>' data-values='<?= e(json_encode(array_map('intval',array_column($grades,'total')))) ?>'></canvas></div></div><div class="col-lg-6"><div class="card p-3"><h2 class="h6">Risk Distribution</h2><canvas data-chart="pie" data-labels='<?= e(json_encode(array_column($risks,'risk_level'))) ?>' data-values='<?= e(json_encode(array_map('intval',array_column($risks,'total')))) ?>'></canvas></div></div></div>
<div class="table-wrap mt-3"><h2 class="h6">Best Performing Students</h2><table class="table"><thead><tr><th>Matric No</th><th>Student</th><th>Predicted GPA</th></tr></thead><tbody><?php foreach($best as $b): ?><tr><td><?= e($b['matric_no']) ?></td><td><?= e($b['student']) ?></td><td><?= e($b['gpa']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
