<?php
require_once 'model/AdminModel.php';

$adminModel = new AdminModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch current company details to retain existing images if no new image is uploaded
    $companyDetails = $adminModel->getCompanyDetails();

    // Handle file uploads for company logo and digital signature
    $logoFilename = $companyDetails['company_logo_invoice']; // Default to existing logo
    $signatureFilename = $companyDetails['company_digital_signature']; // Default to existing signature

    // Handle company logo upload
    if (isset($_FILES['company_logo_invoice']) && $_FILES['company_logo_invoice']['error'] == 0) {
        $logoFilename = 'logo_' . time() . '_' . $_FILES['company_logo_invoice']['name'];
        move_uploaded_file($_FILES['company_logo_invoice']['tmp_name'], 'uploads/admin/company/' . $logoFilename);
        $adminModel->updateCompanyLogo($logoFilename);
    }

    // Handle digital signature upload
    if (isset($_FILES['company_digital_signature']) && $_FILES['company_digital_signature']['error'] == 0) {
        $signatureFilename = 'signature_' . time() . '_' . $_FILES['company_digital_signature']['name'];
        move_uploaded_file($_FILES['company_digital_signature']['tmp_name'], 'uploads/admin/company/' . $signatureFilename);
        $adminModel->updateCompanyDigitalSignature($signatureFilename);
    }

    // Prepare data for company details update
    $data = [
        'company_name' => $_POST['company_name'] ?? '',
        'company_invoice_name' => $_POST['company_invoice_name'] ?? '',
        'company_address_1' => $_POST['company_address_1'] ?? '',
        'company_address_2' => $_POST['company_address_2'] ?? '',
        'company_city' => $_POST['company_city'] ?? '',
        'company_pin_code' => $_POST['company_pin_code'] ?? '',
        'company_gst_no' => $_POST['company_gst_no'] ?? '',
        'company_state' => $_POST['company_state'] ?? '', // New field
        'company_state_code' => $_POST['company_state_code'] ?? '', // New field
        'company_contact_no' => $_POST['company_contact_no'] ?? '',
        'company_bank_name' => $_POST['company_bank_name'] ?? '',
        'company_bank_account_no' => $_POST['company_bank_account_no'] ?? '',
        'company_ifsc_code' => $_POST['company_ifsc_code'] ?? '',
        'company_bank_branch' => $_POST['company_bank_branch'] ?? '',
        'company_logo_invoice' => $logoFilename,  // Use the filename (either updated or existing)
        'company_digital_signature' => $signatureFilename // Use the filename (either updated or existing)
    ];

    // Update company details
    if ($adminModel->updateCompanyDetails($data)) {
        $_SESSION['message'] = "Company details updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update company details.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect to prevent form resubmission
    header("Location: company-details");
    exit();
}