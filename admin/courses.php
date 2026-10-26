<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    db()->prepare('INSERT INTO courses (department_id, code, title, unit, semester, level, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())')->execute([(int)$_POST['department_id'], trim($_POST['code']), trim($_POST['title']), (int)$_POST['unit'], trim($_POST['semester']), trim($_POST['level'])]);
    flash('success', 'Course added.');
    redirect('admin/courses.php');
}
$departments = fetch_all('SELECT * FROM departments ORDER BY name');
$courses = fetch_all('SELECT c.*, d.name department FROM courses c LEFT JOIN departments d ON d.id=c.department_id ORDER BY c.level, c.code');
$title = 'Courses';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3"><div class="col-lg-4"><form class="card p-3" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><h2 class="h6">Add Course</h2><select name="department_id" class="form-select mb-2"><?php foreach($departments as $d): ?><option value="<?= e((string)$d['id']) ?>"><?= e($d['name']) ?></option><?php endforeach; ?></select><input name="code" class="form-control mb-2" placeholder="Course Code" required><input name="title" class="form-control mb-2" placeholder="Course Title" required><input name="unit" type="number" class="form-control mb-2" value="3"><input name="semester" class="form-control mb-2" value="First Semester"><input name="level" class="form-control mb-3" value="300"><button class="btn btn-primary">Save</button></form></div><div class="col-lg-8"><div class="table-wrap"><table class="table"><thead><tr><th>Code</th><th>Title</th><th>Unit</th><th>Department</th><th>Level</th></tr></thead><tbody><?php foreach($courses as $c): ?><tr><td><?= e($c['code']) ?></td><td><?= e($c['title']) ?></td><td><?= e((string)$c['unit']) ?></td><td><?= e($c['department']) ?></td><td><?= e($c['level']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
