<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $tokenHash = hash('sha256', trim($_POST['token'] ?? ''));
    $reset = fetch_one('SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used_at IS NULL', [$tokenHash]);
    if ($reset && strlen($_POST['password'] ?? '') >= 6) {
        db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($_POST['password'], PASSWORD_DEFAULT), $reset['user_id']]);
        db()->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?')->execute([$reset['id']]);
        flash('success', 'Password reset successfully. You can now login.');
        redirect('login.php');
    }
    flash('danger', 'Invalid token or password too short.');
}
$title = 'Reset Password';
require __DIR__ . '/views/partials/header.php';
?>
<div class="auth-page">
    <form class="auth-card" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <h1 class="h4 fw-bold">Reset password</h1>
        <?php foreach (consume_flash() as $msg): ?><div class="alert alert-<?= e($msg['type']) ?>"><?= e($msg['message']) ?></div><?php endforeach; ?>
        <label class="form-label">Reset token</label>
        <input name="token" class="form-control mb-3" required>
        <label class="form-label">New password</label>
        <input type="password" name="password" class="form-control mb-3" required>
        <button class="btn btn-primary w-100">Reset Password</button>
    </form>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
