<?php
require_once 'model/UserModel.php';

$database = new Database();
$pdo = $database->connect();
$userModel = new UserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = 'Invalid CSRF token';
        $_SESSION['message_type'] = 'error';
        header('Location: register');
        exit;
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if ($userModel->createUser($username, $email, $role, $password)) {
        $_SESSION['message'] = 'User registered successfully';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to register user';
        $_SESSION['message_type'] = 'error';
    }

    header('Location: register');
    exit;
}
