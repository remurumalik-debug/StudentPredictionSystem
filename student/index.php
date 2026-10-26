<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Student']);
$student = fetch_one('SELECT s.*, d.name department, f.name faculty FROM students s LEFT JOIN departments d ON d.id=s.department_id LEFT JOIN faculties f ON f.id=d.faculty_id WHERE s.user_id = ?', [$user['id']]) ?: fetch_one('SELECT s.*, d.name department, f.name faculty FROM students s LEFT JOIN departments d ON d.id=s.department_id LEFT JOIN faculties f ON f.id=d.faculty_id LIMIT 1');
$predictions = fetch_all('SELECT * FROM predictions WHERE student_id = ? ORDER BY created_at DESC LIMIT 5', [$student['id'] ?? 0]);
$title = 'Student Profile';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3">
    <div class="col-lg-4"><div class="card p-3"><div class="text-center"><img src="<?= e(url('assets/images/avatar.svg')) ?>" width="96" height="96" class="rounded-circle mb-3" alt=""><h2 class="h5"><?= e(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></h2><p class="text-secondary"><?= e($student['matric_no'] ?? '') ?></p></div><hr><p><strong>Department:</strong> <?= e($student['department'] ?? '') ?></p><p><strong>Faculty:</strong> <?= e($student['faculty'] ?? '') ?></p><p><strong>Level:</strong> <?= e($student['level'] ?? '') ?></p></div></div>
    <div class="col-lg-8"><div class="table-wrap"><h2 class="h6">Prediction History</h2><table class="table"><thead><tr><th>Grade</th><th>GPA</th><th>Risk</th><th>Confidence</th><th>Date</th></tr></thead><tbody><?php foreach($predictions as $p): ?><tr><td><?= e($p['predicted_grade']) ?></td><td><?= e($p['predicted_gpa']) ?></td><td class="risk-<?= e(strtolower($p['risk_level'])) ?>"><?= e($p['risk_level']) ?></td><td><?= e($p['confidence']) ?>%</td><td><?= e($p['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
