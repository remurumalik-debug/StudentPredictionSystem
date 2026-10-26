<?php
require_once __DIR__ . '/includes/functions.php';
$user = require_login();

if (in_array($user['role_name'], ['Super Admin','Administrator'], true)) {
    $rows = fetch_all('SELECT a.*, u.username, r.name role_name FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id LEFT JOIN roles r ON r.id = u.role_id ORDER BY a.created_at DESC LIMIT 100');
    $heading = 'All User Activities';
} else {
    $rows = fetch_all('SELECT a.*, u.username, r.name role_name FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id LEFT JOIN roles r ON r.id = u.role_id WHERE a.user_id = ? ORDER BY a.created_at DESC LIMIT 100', [$user['id']]);
    $heading = 'My Activities';
}

$title = 'Activities';
require __DIR__ . '/views/partials/header.php';
?>
<div class="table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h5 mb-0"><?= e($heading) ?></h1>
        <button class="btn btn-outline-primary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
    <table class="table table-hover align-middle">
        <thead><tr><th>User</th><th>Role</th><th>Action</th><th>Details</th><th>IP Address</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row['username']) ?></td>
                <td><?= e($row['role_name']) ?></td>
                <td><?= e($row['action']) ?></td>
                <td><?= e($row['details']) ?></td>
                <td><?= e($row['ip_address']) ?></td>
                <td><?= e($row['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
