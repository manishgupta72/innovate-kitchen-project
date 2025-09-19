<?php include 'include/head.php' ?>
<?php include 'include/head-bar.php' ?>

<?php
require_once 'model/UserModel.php';

$database = new Database();
$pdo = $database->connect();
$userModel = new UserModel($pdo);

$users = $userModel->getAllUsers();
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php' ?>
    <section role="main" class="content-body content-body-modern">

        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="card-title"> Dashboard Users</h2>
                            </div>
                            <div class="col-lg-6">
                                <a href="register" class="pull-right btn btn-primary"><i class="fas fa-user-plus"></i>
                                    Add New User</a>
                            </div>
                        </div>
                    </header>
                    <div class="card-body">
                        <?php
                        if (isset($_SESSION['message'])) {
                            $messageTypeClass = ($_SESSION['message_type'] === 'success') ? 'alert-success' : 'alert-danger';
                            echo "<div class='alert {$messageTypeClass}'>{$_SESSION['message']}</div>";
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        }
                        ?>
                        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_roll']); ?></td>
                                    <td>
                                        <a href="register?id=<?php echo $user['user_id']; ?>" class=""
                                            data-toggle="tooltip" title="Edit User"><i
                                                class="fa-regular fa-pen-to-square"></i>Edit</a>
                                        <a class="font-weight-extra-bold mx-1">|</a>
                                        <a href="#" onclick="confirmDeleteUser(<?php echo $user['user_id']; ?>)"
                                            data-toggle="tooltip" title="Delete User"><i
                                                class="mdi mdi-trash-can-outline"></i>Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php' ?>

<script>
function confirmDeleteUser(user_id) {
    // Show delete confirmation modal
    $('#deleteModal').modal('show');
    document.getElementById('deleteConfirmBtn').onclick = function() {
        window.location.href = 'manage-users?action=delete&id=' + user_id;
    };
}
</script>