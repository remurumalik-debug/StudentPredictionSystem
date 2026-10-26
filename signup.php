<?php
require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('dashboard.php');
}

$departments = fetch_all('SELECT id, name FROM departments ORDER BY name');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($username) < 3 || strlen($password) < 6 || $password !== $confirm) {
        flash('danger', 'Use a username of at least 3 characters and matching passwords of at least 6 characters.');
    } elseif (fetch_one('SELECT id FROM users WHERE username = ? OR email = ?', [$username, $email])) {
        flash('danger', 'Username or email already exists.');
    } elseif (fetch_one('SELECT id FROM students WHERE matric_no = ?', [trim($_POST['matric_no'])])) {
        flash('danger', 'Matric number already exists.');
    } else {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $role = fetch_one('SELECT id FROM roles WHERE name = "Student"');
            $stmt = $pdo->prepare('INSERT INTO users (role_id, username, email, password_hash, status, created_at) VALUES (?, ?, ?, ?, "active", NOW())');
            $stmt->execute([(int)$role['id'], $username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO students (user_id, department_id, matric_no, first_name, last_name, email, phone, gender, age, level, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([
                $userId,
                (int)$_POST['department_id'],
                trim($_POST['matric_no']),
                trim($_POST['first_name']),
                trim($_POST['last_name']),
                $email,
                trim($_POST['phone']),
                $_POST['gender'],
                (int)$_POST['age'],
                trim($_POST['level']),
                trim($_POST['address'])
            ]);

            $_SESSION['user_id'] = $userId;
            session_regenerate_id(true);
            $pdo->prepare('INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, "Sign Up", "Student account created", ?, NOW())')->execute([$userId, $_SERVER['REMOTE_ADDR'] ?? 'localhost']);
            $pdo->commit();
            flash('success', 'Account created successfully. Welcome.');
            redirect('student/index.php');
        } catch (Throwable $e) {
            $pdo->rollBack();
            flash('danger', 'Unable to create account. Please check your details and try again.');
        }
    }
}

$title = 'Student Sign Up';
require __DIR__ . '/views/partials/header.php';
?>
<div class="auth-page">
    <form class="auth-card" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <h1 class="h4 fw-bold mb-1">Student sign up</h1>
        <p class="text-secondary">Create your student account and access your own predictions and activity history.</p>
        <?php foreach (consume_flash() as $msg): ?><div class="alert alert-<?= e($msg['type']) ?>"><?= e($msg['message']) ?></div><?php endforeach; ?>
        <div class="row g-2">
            <div class="col-md-6"><label class="form-label">First Name</label><input name="first_name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Last Name</label><input name="last_name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Matric No</label><input name="matric_no" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
            <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Department</label><select name="department_id" class="form-select"><?php foreach($departments as $d): ?><option value="<?= e((string)$d['id']) ?>"><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-select"><option>Female</option><option>Male</option></select></div>
            <div class="col-md-3"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="20"></div>
            <div class="col-md-6"><label class="form-label">Level</label><input name="level" class="form-control" value="100"></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control"></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
        </div>
        <button class="btn btn-primary w-100 mt-3">Create Account</button>
        <a class="btn btn-link w-100 mt-2" href="<?= e(url('login.php')) ?>">Already have an account?</a>
    </form>
</div>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
