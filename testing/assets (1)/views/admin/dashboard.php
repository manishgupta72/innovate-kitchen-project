<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>

<?php include 'include/side-bar.php'; ?>

<!-- BEGIN Neptune Container -->
<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col">
                        <div class="page-description">
                            <h1>Welcome to <?php echo htmlspecialchars($settings['system_name']); ?></h1>
                        </div>
                    </div>
                </div>

                <!-- Optional Dashboard Widget -->
                <div class="row">
                    <div class="col-xl-4">
                        <div class="card widget widget-stats">

                        </div>
                    </div>
                    <!-- More widgets can go here -->
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END Neptune Container -->

<?php include 'include/footer.php'; ?>