<?php
require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'local';

    $recent = fetch_one('SELECT COUNT(*) AS attempts FROM login_attempts WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)', [$ip]);
    if (($recent['attempts'] ?? 0) >= 7) {
        flash('danger', 'Too many login attempts. Please wait ten minutes.');
    } else {
        $user = fetch_one('SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON r.id = u.role_id WHERE u.username = ? AND u.status = "active"', [$username]);
        if ($user && demo_password_matches($user['password_hash'], $password)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            if (str_starts_with($user['password_hash'], 'demo:')) {
                $stmt = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }
            if (!empty($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                db()->prepare('INSERT INTO sessions (user_id, token, expires_at, created_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW())')->execute([$user['id'], hash('sha256', $token)]);
                setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
            }
            db()->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);
            audit_log('Login', 'User logged in');
            redirect('dashboard.php');
        }
        db()->prepare('INSERT INTO login_attempts (username, ip_address, created_at) VALUES (?, ?, NOW())')->execute([$username, $ip]);
        flash('danger', 'Invalid username or password.');
    }
}

$title = 'Login';
require __DIR__ . '/views/partials/header.php';
?>
<div class="auth-page">
    <form class="auth-card" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <h1 class="h4 fw-bold mb-1">Sign in</h1>
        <p class="text-secondary">Use your CUSTECH account to continue.</p>
        <?php foreach (consume_flash() as $msg): ?><div class="alert alert-<?= e($msg['type']) ?>"><?= e($msg['message']) ?></div><?php endforeach; ?>
        <label class="form-label">Username</label>
        <input class="form-control mb-3" name="username" required autofocus>
        <label class="form-label">Password</label>
        <input class="form-control mb-3" type="password" name="password" required>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-check"><input class="form-check-input" type="checkbox" name="remember"> <span class="form-check-label">Remember me</span></label>
            <a href="<?= e(url('forgot_password.php')) ?>">Forgot password?</a>
        </div>
        <button class="btn btn-primary w-100">Login</button>
        <a class="btn btn-outline-primary w-100 mt-2" href="<?= e(url('signup.php')) ?>">Create Student Account</a>
        <div class="small text-secondary mt-3">Demo: admin/admin123, lecturer/lecturer123, student/student123.</div>
    </form>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
