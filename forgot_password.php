<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $user = fetch_one('SELECT id FROM users WHERE email = ?', [trim($_POST['email'] ?? '')]);
    if ($user) {
        $token = bin2hex(random_bytes(24));
        db()->prepare('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), NOW())')->execute([$user['id'], hash('sha256', $token)]);
        flash('success', 'Reset token generated for demonstration: ' . $token);
    } else {
        flash('info', 'If the email exists, a reset instruction will be generated.');
    }
}
$title = 'Forgot Password';
require __DIR__ . '/views/partials/header.php';
?>
<div class="auth-page">
    <form class="auth-card" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <h1 class="h4 fw-bold">Forgot password</h1>
        <?php foreach (consume_flash() as $msg): ?><div class="alert alert-<?= e($msg['type']) ?>"><?= e($msg['message']) ?></div><?php endforeach; ?>
        <label class="form-label">Email address</label>
        <input type="email" name="email" class="form-control mb-3" required>
        <button class="btn btn-primary w-100">Generate Reset Token</button>
        <a class="btn btn-link w-100 mt-2" href="<?= e(url('login.php')) ?>">Back to login</a>
    </form>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
