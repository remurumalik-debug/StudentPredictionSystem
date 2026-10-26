<?php
require_once __DIR__ . '/includes/functions.php';
audit_log('Logout', 'User logged out');
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)$params['httponly']);
}
session_destroy();
setcookie('remember_token', '', time() - 3600, '/');
redirect('login.php');
