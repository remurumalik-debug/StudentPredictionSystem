<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = db()->prepare('INSERT INTO students (matric_no, first_name, last_name, email, gender, age, department_id, level, phone, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([
        trim($_POST['matric_no']), trim($_POST['first_name']), trim($_POST['last_name']), trim($_POST['email']),
        $_POST['gender'], (int)$_POST['age'], (int)$_POST['department_id'], trim($_POST['level']), trim($_POST['phone']), trim($_POST['address'])
    ]);
    audit_log('Create Student', $_POST['matric_no']);
    flash('success', 'Student record added successfully.');
    redirect('admin/students.php');
}

$q = trim($_GET['q'] ?? '');
$students = fetch_all('SELECT s.*, d.name department FROM students s LEFT JOIN departments d ON d.id = s.department_id WHERE CONCAT(s.matric_no, s.first_name, s.last_name, s.email) LIKE ? ORDER BY s.created_at DESC', ['%' . $q . '%']);
$departments = fetch_all('SELECT id, name FROM departments ORDER BY name');
$title = 'Students';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
    <form class="d-flex gap-2"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search students"><button class="btn btn-primary"><i class="bi bi-search"></i></button></form>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentModal"><i class="bi bi-plus-lg"></i> Add Student</button>
</div>
<div class="table-wrap">
    <table class="table table-hover align-middle">
        <thead><tr><th>Matric No</th><th>Name</th><th>Department</th><th>Level</th><th>Email</th><th>Gender</th></tr></thead>
        <tbody><?php foreach ($students as $s): ?><tr><td><?= e($s['matric_no']) ?></td><td><?= e($s['first_name'] . ' ' . $s['last_name']) ?></td><td><?= e($s['department']) ?></td><td><?= e($s['level']) ?></td><td><?= e($s['email']) ?></td><td><?= e($s['gender']) ?></td></tr><?php endforeach; ?></tbody>
    </table>
</div>
<div class="modal fade" id="studentModal" tabindex="-1"><div class="modal-dialog modal-lg"><form method="post" class="modal-content">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="modal-header"><h2 class="modal-title h5">Register Student</h2><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body row g-3">
        <div class="col-md-4"><label class="form-label">Matric No</label><input name="matric_no" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">First Name</label><input name="first_name" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Last Name</label><input name="last_name" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-select"><option>Female</option><option>Male</option></select></div>
        <div class="col-md-3"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="20"></div>
        <div class="col-md-6"><label class="form-label">Department</label><select name="department_id" class="form-select"><?php foreach ($departments as $d): ?><option value="<?= e((string)$d['id']) ?>"><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Level</label><input name="level" class="form-control" value="300"></div>
        <div class="col-md-3"><label class="form-label">Phone</label><input name="phone" class="form-control"></div>
        <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control"></textarea></div>
    </div>
    <div class="modal-footer"><button class="btn btn-primary">Save Student</button></div>
</form></div></div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
