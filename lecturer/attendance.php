<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Lecturer']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    db()->prepare('INSERT INTO attendance (student_id, course_id, attendance_date, status, created_at) VALUES (?, ?, ?, ?, NOW())')->execute([(int)$_POST['student_id'], (int)$_POST['course_id'], $_POST['attendance_date'], $_POST['status']]);
    flash('success', 'Attendance saved.');
    redirect('lecturer/attendance.php');
}
$students = fetch_all('SELECT id, matric_no, CONCAT(first_name," ",last_name) name FROM students ORDER BY first_name');
$courses = fetch_all('SELECT id, code, title FROM courses ORDER BY code');
$title = 'Attendance';
require __DIR__ . '/../views/partials/header.php';
?>
<form class="card p-3" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="row g-3"><div class="col-md-5"><label class="form-label">Student</label><select name="student_id" class="form-select"><?php foreach($students as $s): ?><option value="<?= e((string)$s['id']) ?>"><?= e($s['matric_no'].' - '.$s['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Course</label><select name="course_id" class="form-select"><?php foreach($courses as $c): ?><option value="<?= e((string)$c['id']) ?>"><?= e($c['code']) ?></option><?php endforeach; ?></select></div><div class="col-md-2"><label class="form-label">Date</label><input type="date" name="attendance_date" class="form-control" value="<?= e(date('Y-m-d')) ?>"></div><div class="col-md-1"><label class="form-label">Status</label><select name="status" class="form-select"><option>Present</option><option>Absent</option></select></div></div><button class="btn btn-primary mt-3">Save Attendance</button></form>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
