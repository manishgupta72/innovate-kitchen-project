<?php include 'include/head.php'; ?>




<div class="auth-container p-4 m-auto">
    <div class="text-center mb-4">
        <a href="admin">
            <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['admin_logo']); ?>" height="70" alt="Admin Logo">
        </a>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <h4 class="card-title"><i class="material-icons">person</i> Login Dashboard</h4>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['message'])) {
                $messageTypeClass = ($_SESSION['message_type'] === 'success') ? 'alert-success' : 'alert-danger';
                echo "<div class='alert {$messageTypeClass}'>{$_SESSION['message']}</div>";
                unset($_SESSION['message'], $_SESSION['message_type']);
            }
            ?>

            <form action="login" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <input name="username" id="username" type="text" class="form-control" required>
                        <span class="input-group-text"><i class="material-icons">person</i></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input name="password" id="password" type="password" class="form-control" required>
                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center text-muted mt-3">
        &copy; 2025 Developed by <a href="https://www.rbtech.in">RB Tech Solutions</a>
    </div>
</div>


<?php include 'include/footer.php'; ?>