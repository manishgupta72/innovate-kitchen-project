<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/AdminModel.php';
$adminModel = new AdminModel();
$settings = $adminModel->getGeneralSettings();
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">General Settings</h4>
                            </div>
                            <div class="card-body">


                                <form action="general-setting" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label class="form-label">System Name</label>
                                                <input type="text" class="form-control" name="system_name"
                                                    value="<?= htmlspecialchars($settings['system_name'] ?? '') ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Application Title</label>
                                                <input type="text" class="form-control" name="application_title"
                                                    value="<?= htmlspecialchars($settings['application_title'] ?? '') ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Website Title</label>
                                                <input type="text" class="form-control" name="website_title"
                                                    value="<?= htmlspecialchars($settings['website_title'] ?? '') ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Website URL</label>
                                                <input type="url" class="form-control" name="website_url"
                                                    value="<?= htmlspecialchars($settings['website_url'] ?? '') ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Admin Logo</label>
                                                <input type="file" class="form-control" name="admin_logo">
                                                <?php if (!empty($settings['admin_logo'])): ?>
                                                    <img src="<?= UPLOAD_IMG_ADMIN . htmlspecialchars($settings['admin_logo']); ?>" class="img-fluid mt-2" style="max-height: 100px;">
                                                <?php endif; ?>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Favicon Logo</label>
                                                <input type="file" class="form-control" name="favicon_logo">
                                                <?php if (!empty($settings['favicon_logo'])): ?>
                                                    <img src="<?= UPLOAD_IMG_ADMIN . htmlspecialchars($settings['favicon_logo']); ?>" class="img-fluid mt-2" style="max-height: 32px;">
                                                <?php endif; ?>
                                            </div>

                                        </div>

                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label class="form-label">Website Description</label>
                                                <textarea class="form-control" name="website_description" rows="3"><?= htmlspecialchars($settings['website_description'] ?? '') ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Website Keywords</label>
                                                <input type="text" class="form-control" name="website_keywords"
                                                    value="<?= htmlspecialchars($settings['website_keywords'] ?? '') ?>">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Website Logo</label>
                                                <input type="file" class="form-control" name="website_logo">
                                                <?php if (!empty($settings['website_logo'])): ?>
                                                    <img src="<?= UPLOAD_IMG_ADMIN . htmlspecialchars($settings['website_logo']); ?>" class="img-fluid mt-2" style="max-height: 100px;">
                                                <?php endif; ?>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Site Copyright</label>
                                                <input type="text" class="form-control" name="site_copyright"
                                                    value="<?= htmlspecialchars($settings['site_copyright'] ?? '') ?>">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary">Update Settings</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- .container -->
        </div> <!-- .content-wrapper -->
    </div> <!-- .app-content -->
</div> <!-- .app-container -->

<?php include 'include/footer.php'; ?>
<?php if (isset($_SESSION['message'])): ?>
    <script>
        window.addEventListener("load", function() {
            setTimeout(function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?= $_SESSION['message_type'] === 'success' ? 'success' : 'error' ?>',
                    title: <?= json_encode($_SESSION['message']) ?>,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }, 300);
        });
    </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>