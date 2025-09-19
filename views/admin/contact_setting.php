<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/AdminModel.php';
$adminModel = new AdminModel();
$contactSettings = $adminModel->getContactSettings();
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contact Settings</h4>
                            </div>
                            <div class="card-body">

                                <form action="contact-setting" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact Number</label>
                                                <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($contactSettings['contact_number'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contact Number Alternate</label>
                                                <input type="text" class="form-control" name="contact_number_alternate" value="<?= htmlspecialchars($contactSettings['contact_number_alternate'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">WhatsApp Number</label>
                                                <input type="text" class="form-control" name="whatsapp_number" value="<?= htmlspecialchars($contactSettings['whatsapp_number'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email ID</label>
                                                <input type="email" class="form-control" name="email_id" value="<?= htmlspecialchars($contactSettings['email_id'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email ID Alternate</label>
                                                <input type="email" class="form-control" name="email_id_alternate" value="<?= htmlspecialchars($contactSettings['email_id_alternate'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Extra Info 1</label>
                                                <textarea class="form-control" name="extra_info_1" rows="2"><?= htmlspecialchars($contactSettings['extra_info_1'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Extra Info 2</label>
                                                <textarea class="form-control" name="extra_info_2" rows="2"><?= htmlspecialchars($contactSettings['extra_info_2'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Facebook URL</label>
                                                <input type="url" class="form-control" name="facebook_url" value="<?= htmlspecialchars($contactSettings['facebook_url'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Instagram URL</label>
                                                <input type="url" class="form-control" name="instagram_url" value="<?= htmlspecialchars($contactSettings['instagram_url'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1</label>
                                                <textarea class="form-control" name="address_line_1" rows="2"><?= htmlspecialchars($contactSettings['address_line_1'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2</label>
                                                <textarea class="form-control" name="address_line_2" rows="2"><?= htmlspecialchars($contactSettings['address_line_2'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 3</label>
                                                <textarea class="form-control" name="address_line_3" rows="2"><?= htmlspecialchars($contactSettings['address_line_3'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Google Map Iframe</label>
                                                <textarea class="form-control" name="google_map_iframe" rows="2"><?= htmlspecialchars($contactSettings['google_map_iframe'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">YouTube URL</label>
                                                <input type="url" class="form-control" name="youtube_url" value="<?= htmlspecialchars($contactSettings['youtube_url'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">LinkedIn URL</label>
                                                <input type="url" class="form-control" name="linkedin_url" value="<?= htmlspecialchars($contactSettings['linkedin_url'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">X URL</label>
                                                <input type="url" class="form-control" name="x_url" value="<?= htmlspecialchars($contactSettings['x_url'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Custom URL</label>
                                                <input type="url" class="form-control" name="custom_url" value="<?= htmlspecialchars($contactSettings['custom_url'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary mt-3">Update Settings</button>
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