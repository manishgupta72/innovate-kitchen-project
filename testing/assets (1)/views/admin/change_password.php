<?php
require_once 'include/head.php';
require_once 'include/head-bar.php';
require_once 'include/side-bar.php';
require_once 'model/AdminModel.php';

// Initialize AdminModel
$adminModel = new AdminModel();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="card">
                            <header class="card-header">
                                <h4 class="font-weight-semibold">Change Your Dashboard Password</h4>
                            </header>
                            <div class="card-body">
                                <form class="row" action="change-password" method="post">
                                    <?php
                                    if (isset($_SESSION['message'])) {
                                        $messageTypeClass = ($_SESSION['message_type'] === 'success') ? 'alert-success' : 'alert-danger';
                                        echo "<div class='alert {$messageTypeClass}'>{$_SESSION['message']}</div>";

                                        // Clear the message from the session
                                        unset($_SESSION['message']);
                                        unset($_SESSION['message_type']);
                                    }
                                    ?>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php $email = $_SESSION['user']['user_email'] ?? ''; ?>
                                            <label class="col-form-label" for="formGroupExampleInput">Email ID</label>
                                            <input type="text" class="form-control" name="email_id" disabled value="<?php echo htmlspecialchars($email); ?>" placeholder="Email ID">
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Old Password</label>
                                            <input type="password" class="form-control" name="old_password" placeholder="Old Password" required>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">New Password</label>
                                            <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Confirm New Password</label>
                                            <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm New Password" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-start mt-3">
                                        <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">Change Password</button>
                                    </div>

                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/footer.php' ?>