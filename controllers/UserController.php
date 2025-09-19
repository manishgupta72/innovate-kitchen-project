<?php
require_once 'model/UserModel.php';

$database = new Database();
$pdo = $database->connect();
$userModel = new UserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'])) {
        // Update user
        $userId = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if ($userModel->updateUser($userId, $username, $email, $role, $password)) {
            $_SESSION['message'] = "User updated successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update user.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: manage-users");
    } else {
        // Register new user
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if ($userModel->createUser($username, $email, $role, $password)) {
            $_SESSION['message'] = "User registered successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to register user.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: manage-users");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $user = $userModel->getUserById($userId);
    if ($user) {
        // User found, render the edit form (handled in admin_register.php)
    } else {
        $_SESSION['message'] = "User not found.";
        $_SESSION['message_type'] = "danger";
        header("Location: manage-users");
        exit;
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    if ($userModel->deleteUser($userId)) {
        $_SESSION['message'] = "User deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete user.";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: manage-users");
    exit();
}
