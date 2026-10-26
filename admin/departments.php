<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    db()->prepare('INSERT INTO departments (faculty_id, name, code, created_at) VALUES (?, ?, ?, NOW())')->execute([(int)$_POST['faculty_id'], trim($_POST['name']), trim($_POST['code'])]);
    flash('success', 'Department saved.');
    redirect('admin/departments.php');
}
$faculties = fetch_all('SELECT * FROM faculties ORDER BY name');
$rows = fetch_all('SELECT d.*, f.name faculty, COUNT(s.id) students FROM departments d JOIN faculties f ON f.id=d.faculty_id LEFT JOIN students s ON s.department_id=d.id GROUP BY d.id ORDER BY f.name, d.name');
$title = 'Departments';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3">
    <div class="col-lg-4"><form class="card p-3" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><h2 class="h6">Add Department</h2><label class="form-label">Faculty</label><select name="faculty_id" class="form-select mb-2"><?php foreach($faculties as $f): ?><option value="<?= e((string)$f['id']) ?>"><?= e($f['name']) ?></option><?php endforeach; ?></select><label class="form-label">Name</label><input name="name" class="form-control mb-2" required><label class="form-label">Code</label><input name="code" class="form-control mb-3" required><button class="btn btn-primary">Save</button></form></div>
    <div class="col-lg-8"><div class="table-wrap"><table class="table"><thead><tr><th>Faculty</th><th>Department</th><th>Code</th><th>Students</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['faculty']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['code']) ?></td><td><?= e((string)$r['students']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
