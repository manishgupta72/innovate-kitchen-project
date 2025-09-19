<?php

include 'include/head.php';
include 'include/head-bar.php';
require_once 'model/UserModel.php';

// Database and UserModel initialization
$database = new Database();
$pdo = $database->connect();
$userModel = new UserModel($pdo);

// Get the user ID from the URL query string
$userId = isset($_GET['id']) ? $_GET['id'] : null;
$user = $userId ? $userModel->getUserById($userId) : null;
$isEdit = $userId !== null;

?>
<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>

    <section role="main" class="content-body content-body-modern">
        <section class="card">
            <header class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="font-weight-semibold"><?php echo $isEdit ? 'Edit User' : 'Register New User'; ?></h4>
                    </div>
                    <div class="col-lg-6">
                        <a href="manage-users" class="pull-right btn btn-primary"><i class="fa-solid fa-table-list"></i>
                            Manage Users</a>
                    </div>
                </div>
            </header>
            <div class="card-body">
                <?php
                // Display session message if any
                if (isset($_SESSION['message'])) {
                    $messageTypeClass = ($_SESSION['message_type'] === 'success') ? 'alert-success' : 'alert-danger';
                    echo "<div class='alert {$messageTypeClass}'>{$_SESSION['message']}</div>";
                    unset($_SESSION['message'], $_SESSION['message_type']);
                }
                ?>

                <!-- Form to register or update user -->
                <form action="<?php echo $isEdit ? 'update-user' : 'register'; ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php if ($isEdit): ?>
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo $isEdit ? htmlspecialchars($user['username']) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $isEdit ? htmlspecialchars($user['user_email']) : ''; ?>"
                                    required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin"
                                        <?php echo ($isEdit && $user['user_roll'] == 'admin') ? 'selected' : ''; ?>>
                                        Admin</option>
                                    <option value="manager"
                                        <?php echo ($isEdit && $user['user_roll'] == 'manager') ? 'selected' : ''; ?>>
                                        Manager</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label
                                    for="password"><?php echo $isEdit ? 'New Password (leave blank to keep current)' : 'Password'; ?></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    <?php echo $isEdit ? '' : 'required'; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit"
                            class="btn btn-primary"><?php echo $isEdit ? 'Update User' : 'Register User'; ?></button>
                    </div>
                </form>
            </div>
        </section>
    </section>
</div>

<?php include 'include/footer.php'; ?>