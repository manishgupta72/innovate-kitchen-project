<?php
require_once 'model/UserModel.php';

$database = new Database();
$pdo = $database->connect();
$userModel = new UserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = 'Invalid CSRF token';
        $_SESSION['message_type'] = 'error';
        header('Location: login');
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['username'] = $_POST['username'];
    $user = $userModel->getUserByUsername($username);

    if ($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user'] = $user;
        $userModel->updateLastLogin($user['user_id']);
        header('Location: dashboard');
        exit;
    } else {
        $_SESSION['message'] = 'Invalid username or password';
        $_SESSION['message_type'] = 'error';
        header('Location: login');
        exit;
    }
}
