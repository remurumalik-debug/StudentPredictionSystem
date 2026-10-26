<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Lecturer']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $total = (float)$_POST['ca_score'] + (float)$_POST['exam_score'];
    db()->prepare('INSERT INTO results (student_id, course_id, session_year, semester, ca_score, exam_score, total_score, grade, gpa_point, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())')->execute([(int)$_POST['student_id'], (int)$_POST['course_id'], $_POST['session_year'], $_POST['semester'], (float)$_POST['ca_score'], (float)$_POST['exam_score'], $total, $total>=70?'A':($total>=60?'B':($total>=50?'C':($total>=45?'D':($total>=40?'E':'F')))), $total>=70?5:($total>=60?4:($total>=50?3:($total>=45?2:($total>=40?1:0))))]);
    flash('success', 'Score uploaded.');
    redirect('lecturer/scores.php');
}
$students = fetch_all('SELECT id, matric_no, CONCAT(first_name," ",last_name) name FROM students ORDER BY first_name');
$courses = fetch_all('SELECT id, code, title FROM courses ORDER BY code');
$title = 'Enter Scores';
require __DIR__ . '/../views/partials/header.php';
?>
<form class="card p-3" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="row g-3"><div class="col-md-6"><label class="form-label">Student</label><select name="student_id" class="form-select"><?php foreach($students as $s): ?><option value="<?= e((string)$s['id']) ?>"><?= e($s['matric_no'].' - '.$s['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Course</label><select name="course_id" class="form-select"><?php foreach($courses as $c): ?><option value="<?= e((string)$c['id']) ?>"><?= e($c['code'].' - '.$c['title']) ?></option><?php endforeach; ?></select></div><div class="col-md-3"><label class="form-label">Session</label><input name="session_year" class="form-control" value="2025/2026"></div><div class="col-md-3"><label class="form-label">Semester</label><input name="semester" class="form-control" value="First Semester"></div><div class="col-md-3"><label class="form-label">CA</label><input type="number" name="ca_score" class="form-control" value="25"></div><div class="col-md-3"><label class="form-label">Exam</label><input type="number" name="exam_score" class="form-control" value="45"></div></div><button class="btn btn-primary mt-3">Upload Result</button></form>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
