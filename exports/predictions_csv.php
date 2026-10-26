<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator','Department Officer']);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="prediction_report.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['Matric No','Student','Predicted Grade','Predicted GPA','Probability Score','Confidence','Risk Level','Created At']);
$rows = fetch_all('SELECT p.*, s.matric_no, CONCAT(s.first_name," ",s.last_name) student FROM predictions p JOIN students s ON s.id=p.student_id ORDER BY p.created_at DESC');
foreach ($rows as $r) {
    fputcsv($out, [$r['matric_no'], $r['student'], $r['predicted_grade'], $r['predicted_gpa'], $r['probability_score'], $r['confidence'], $r['risk_level'], $r['created_at']]);
}
fclose($out);
exit;
