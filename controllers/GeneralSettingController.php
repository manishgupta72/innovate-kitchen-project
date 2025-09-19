<?php
require_once 'model/AdminModel.php';

$adminModel = new AdminModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    if (isset($_FILES['website_logo']) && $_FILES['website_logo']['error'] == 0) {
        $logoFilename = 'logo_' . time() . '_' . $_FILES['website_logo']['name'];
        move_uploaded_file($_FILES['website_logo']['tmp_name'], 'uploads/admin/' . $logoFilename);
        $adminModel->updateLogo('website_logo', $logoFilename);
    }

    if (isset($_FILES['admin_logo']) && $_FILES['admin_logo']['error'] == 0) {
        $adminLogoFilename = 'admin_logo_' . time() . '_' . $_FILES['admin_logo']['name'];
        move_uploaded_file($_FILES['admin_logo']['tmp_name'], 'uploads/admin/' . $adminLogoFilename);
        $adminModel->updateLogo('admin_logo', $adminLogoFilename);
    }

    if (isset($_FILES['favicon_logo']) && $_FILES['favicon_logo']['error'] == 0) {
        $faviconFilename = 'favicon_' . time() . '_' . $_FILES['favicon_logo']['name'];
        move_uploaded_file($_FILES['favicon_logo']['tmp_name'], 'uploads/admin/' . $faviconFilename);
        $adminModel->updateLogo('favicon_logo', $faviconFilename);
    }

    // Prepare data for update using the new field names
    $data = [
        'system_name'       => $_POST['system_name'] ?? '',
        'application_title' => $_POST['application_title'] ?? '',
        'website_title'     => $_POST['website_title'] ?? '',
        'website_url'       => $_POST['website_url'] ?? '',
        'website_description' => $_POST['website_description'] ?? '',
        'website_keywords'  => $_POST['website_keywords'] ?? '',
        'site_copyright'    => $_POST['site_copyright'] ?? '',
    ];

    // Update general settings
    if ($adminModel->updateGeneralSettings($data)) {
        $_SESSION['message'] = "General settings updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update general settings.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect to prevent form resubmission
    header("Location: general-setting");
    exit();
}