<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/AdminModel.php';
$adminModel = new AdminModel();
$counterSettings = $adminModel->getCounterSettings();
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <header class="card-header">
                                <h4 class="font-weight-semibold">Counter Settings</h4>
                            </header>
                            <div class="card-body">

                                <form action="counter-setting" method="post">
                                    <!-- Counter 1 Row -->
                                    <div class="row form-group pb-3">
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Counter Name 1</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="counter_name_1"
                                                        value="<?php echo htmlspecialchars($counterSettings['counter_name_1'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Count No.1</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="count_no_1"
                                                        value="<?php echo htmlspecialchars($counterSettings['count_no_1'] ?? 0); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Counter 2 Row -->
                                    <div class="row form-group pb-3">
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Counter Name 2</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="counter_name_2"
                                                        value="<?php echo htmlspecialchars($counterSettings['counter_name_2'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Count No.2</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="count_no_2"
                                                        value="<?php echo htmlspecialchars($counterSettings['count_no_2'] ?? 0); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Counter 3 Row -->
                                    <div class="row form-group pb-3">
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Counter Name 3</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="counter_name_3"
                                                        value="<?php echo htmlspecialchars($counterSettings['counter_name_3'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Count No.3</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="count_no_3"
                                                        value="<?php echo htmlspecialchars($counterSettings['count_no_3'] ?? 0); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Counter 4 Row -->
                                    <div class="row form-group pb-3">
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Counter Name 4</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="counter_name_4"
                                                        value="<?php echo htmlspecialchars($counterSettings['counter_name_4'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Count No.4</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="count_no_4"
                                                        value="<?php echo htmlspecialchars($counterSettings['count_no_4'] ?? 0); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary mt-3">Update Counter Settings</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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