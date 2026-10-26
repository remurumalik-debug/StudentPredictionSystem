<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Lecturer']);
$courses = fetch_all('SELECT c.* FROM courses c ORDER BY c.code LIMIT 12');
$title = 'Lecturer Dashboard';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3 mb-3"><div class="col-md-4"><div class="metric-card card"><div class="icon"><i class="bi bi-book"></i></div><h3><?= e((string)count($courses)) ?></h3><p>Assigned Courses</p></div></div><div class="col-md-4"><div class="metric-card card"><div class="icon"><i class="bi bi-graph-up"></i></div><h3><?= e((string)table_count('predictions')) ?></h3><p>Student Predictions</p></div></div><div class="col-md-4"><div class="metric-card card"><div class="icon"><i class="bi bi-exclamation-triangle"></i></div><h3><?= e((string)table_count('predictions','risk_level="High"')) ?></h3><p>High Risk Alerts</p></div></div></div>
<div class="table-wrap"><h2 class="h6">Courses</h2><table class="table"><thead><tr><th>Code</th><th>Title</th><th>Unit</th><th>Semester</th></tr></thead><tbody><?php foreach($courses as $c): ?><tr><td><?= e($c['code']) ?></td><td><?= e($c['title']) ?></td><td><?= e((string)$c['unit']) ?></td><td><?= e($c['semester']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
