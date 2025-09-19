<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php
require_once 'model/AdminModel.php';
$adminModel = new AdminModel();
$companyDetails = $adminModel->getCompanyDetails();
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>
    <section role="main" class="content-body content-body-modern">
        <section class="card">
            <div class="row m-2">
                <section class="card">
                    <header class="card-header">
                        <h4 class="font-weight-semibold">Company Details</h4>
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
                        <form action="company-details" method="post" enctype="multipart/form-data">
                            <div class="row form-group pb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Company Name</label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="<?php echo htmlspecialchars($companyDetails['company_name'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Invoice Name</label>
                                        <input type="text" class="form-control" name="company_invoice_name"
                                            value="<?php echo htmlspecialchars($companyDetails['company_invoice_name'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Address 1</label>
                                        <input type="text" class="form-control" name="company_address_1"
                                            value="<?php echo htmlspecialchars($companyDetails['company_address_1'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Address 2</label>
                                        <input type="text" class="form-control" name="company_address_2"
                                            value="<?php echo htmlspecialchars($companyDetails['company_address_2'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company City</label>
                                        <input type="text" class="form-control" name="company_city"
                                            value="<?php echo htmlspecialchars($companyDetails['company_city'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Pin Code</label>
                                        <input type="text" class="form-control" name="company_pin_code"
                                            value="<?php echo htmlspecialchars($companyDetails['company_pin_code'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company GST No.</label>
                                        <input type="text" class="form-control" name="company_gst_no"
                                            value="<?php echo htmlspecialchars($companyDetails['company_gst_no'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company State</label>
                                        <input type="text" class="form-control" name="company_state"
                                            value="<?php echo htmlspecialchars($companyDetails['company_state'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company State Code</label>
                                        <input type="text" class="form-control" name="company_state_code"
                                            value="<?php echo htmlspecialchars($companyDetails['company_state_code'] ?? ''); ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Company Contact No.</label>
                                        <input type="text" class="form-control" name="company_contact_no"
                                            value="<?php echo htmlspecialchars($companyDetails['company_contact_no'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Bank Name</label>
                                        <input type="text" class="form-control" name="company_bank_name"
                                            value="<?php echo htmlspecialchars($companyDetails['company_bank_name'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Bank Account No.</label>
                                        <input type="text" class="form-control" name="company_bank_account_no"
                                            value="<?php echo htmlspecialchars($companyDetails['company_bank_account_no'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company IFSC Code</label>
                                        <input type="text" class="form-control" name="company_ifsc_code"
                                            value="<?php echo htmlspecialchars($companyDetails['company_ifsc_code'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Company Bank Branch</label>
                                        <input type="text" class="form-control" name="company_bank_branch"
                                            value="<?php echo htmlspecialchars($companyDetails['company_bank_branch'] ?? ''); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Upload Company Logo</label>
                                        <input type="file" class="form-control" name="company_logo_invoice">
                                        <?php if (!empty($companyDetails['company_logo_invoice'])): ?>
                                        <img src="<?php echo UPLOAD_COMPANY . htmlspecialchars($companyDetails['company_logo_invoice']); ?>"
                                            class="img-fluid mt-2" style="max-height: 100px;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Upload Digital Signature</label>
                                        <input type="file" class="form-control" name="company_digital_signature">
                                        <?php if (!empty($companyDetails['company_digital_signature'])): ?>
                                        <img src="<?php echo UPLOAD_COMPANY . htmlspecialchars($companyDetails['company_digital_signature']); ?>"
                                            class="img-fluid mt-2" style="max-height: 100px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Company Details</button>
                        </form>
                    </div>
                </section>
            </div>
        </section>
    </section>
</div>

<?php include 'include/footer.php'; ?>