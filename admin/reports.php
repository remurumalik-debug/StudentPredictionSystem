<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Super Admin','Administrator']);
$title = 'Reports';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="row g-3">
<?php foreach ([
    ['Student Report','reports/student_report.php','bi-people'],
    ['Prediction Report','reports/prediction_report.php','bi-cpu'],
    ['Risk Report','reports/risk_report.php','bi-exclamation-triangle'],
    ['CSV Export','exports/predictions_csv.php','bi-filetype-csv']
] as $r): ?>
    <div class="col-md-6 col-xl-3"><a class="card metric-card text-decoration-none" href="<?= e(url($r[1])) ?>"><div class="icon"><i class="bi <?= e($r[2]) ?>"></i></div><h3 class="h5"><?= e($r[0]) ?></h3><p>Open printable report</p></a></div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
