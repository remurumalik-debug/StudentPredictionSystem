<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/PredictionEngine.php';
$user = require_login();
$selectedStudent = $user['role_name'] === 'Student' ? fetch_one('SELECT id FROM students WHERE user_id = ?', [$user['id']]) : null;
$students = $selectedStudent
    ? fetch_all('SELECT id, matric_no, CONCAT(first_name," ",last_name) name FROM students WHERE id = ?', [$selectedStudent['id']])
    : fetch_all('SELECT id, matric_no, CONCAT(first_name," ",last_name) name FROM students ORDER BY first_name');
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $input = [
        'attendance' => (float)$_POST['attendance'],
        'assignment_score' => (float)$_POST['assignment_score'],
        'continuous_assessment' => (float)$_POST['continuous_assessment'],
        'test_score' => (float)$_POST['test_score'],
        'previous_cgpa' => (float)$_POST['previous_cgpa'],
        'study_hours' => (float)$_POST['study_hours'],
        'health_status' => $_POST['health_status'],
        'financial_challenges' => $_POST['financial_challenges'],
    ];
    $engine = new PredictionEngine();
    $result = $engine->predict($input);
    $studentId = (int)($_POST['student_id'] ?: ($selectedStudent['id'] ?? 0));
    db()->prepare('INSERT INTO predictions (student_id, input_data, predicted_grade, predicted_gpa, probability_score, confidence, risk_level, recommendations, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())')->execute([
        $studentId,
        json_encode($input),
        $result['predicted_grade'],
        $result['predicted_gpa'],
        $result['score'],
        $result['confidence'],
        $result['risk_level'],
        implode("\n", $result['recommendations']),
        $user['id']
    ]);
    audit_log('Run Prediction', 'Student ID ' . $studentId);
    flash('success', 'Prediction generated and saved.');
}

$title = 'AI Prediction';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3">
    <div class="col-xl-8">
        <form class="card p-3" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <h2 class="h6">Prediction Inputs</h2>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Student</label><select name="student_id" class="form-select"><?php foreach($students as $s): ?><option value="<?= e((string)$s['id']) ?>" <?= (($selectedStudent['id'] ?? 0)==$s['id'])?'selected':'' ?>><?= e($s['matric_no'].' - '.$s['name']) ?></option><?php endforeach; ?></select></div>
                <?php foreach ([
                    'attendance'=>'Attendance %','assignment_score'=>'Assignment %','continuous_assessment'=>'CA %','test_score'=>'Test Score %'
                ] as $name=>$label): ?><div class="col-md-4"><label class="form-label"><?= e($label) ?></label><input type="number" min="0" max="100" name="<?= e($name) ?>" class="form-control" value="75" required></div><?php endforeach; ?>
                <div class="col-md-4"><label class="form-label">Previous CGPA</label><input type="number" min="0" max="5" step=".01" name="previous_cgpa" class="form-control" value="3.25"></div>
                <div class="col-md-4"><label class="form-label">Study Hours/Week</label><input type="number" min="0" max="60" name="study_hours" class="form-control" value="18"></div>
                <div class="col-md-4"><label class="form-label">Health Status</label><select name="health_status" class="form-select"><option>Good</option><option>Fair</option><option>Poor</option></select></div>
                <div class="col-md-4"><label class="form-label">Financial Challenges</label><select name="financial_challenges" class="form-select"><option>No</option><option>Yes</option></select></div>
            </div>
            <button class="btn btn-primary mt-3"><i class="bi bi-cpu"></i> Generate Prediction</button>
        </form>
    </div>
    <div class="col-xl-4">
        <div class="card p-3">
            <h2 class="h6">Latest Result</h2>
            <?php if ($result): ?>
                <h3><?= e($result['predicted_grade']) ?></h3>
                <p>Predicted GPA: <strong><?= e((string)$result['predicted_gpa']) ?></strong></p>
                <p>Confidence: <strong><?= e((string)$result['confidence']) ?>%</strong></p>
                <p>Risk Level: <strong class="risk-<?= e(strtolower($result['risk_level'])) ?>"><?= e($result['risk_level']) ?></strong></p>
                <h4 class="h6">Recommendations</h4>
                <ul><?php foreach($result['recommendations'] as $tip): ?><li><?= e($tip) ?></li><?php endforeach; ?></ul>
                <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Print</button>
            <?php else: ?>
                <p class="text-secondary">Run a prediction to see the grade class, confidence score, risk level, and recommendations.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
