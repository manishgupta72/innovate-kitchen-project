<?php
require_once 'model/AdminModel.php';

$adminModel = new AdminModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['user_id'] ?? 0;
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

    // Verify current password
    if ($adminModel->verifyCurrentPassword($userId, $oldPassword)) {
        // Check if new passwords match
        if ($newPassword === $confirmNewPassword) {
            // Update password
            if ($adminModel->updateUserPassword($userId, $newPassword)) {
                $_SESSION['message'] = "Password updated successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to update password. Please try again.";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "New passwords do not match.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Current password is incorrect.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect to prevent form resubmission
    header("Location: change-password");
    exit();
}