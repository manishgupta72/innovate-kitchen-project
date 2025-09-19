<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/AdminModel.php';

$database = new Database();
$pdo = $database->connect();
$adminModel = new AdminModel($pdo);

// Image upload function
function handleImageUpload($file, $existingFileName = '')
{
    if (empty($file['name'])) {
        return $existingFileName; // Use existing image if no new file uploaded
    }
    $uploadDirectory = 'uploads/admin/company/';
    $fileName = time() . '_' . basename($file['name']);
    $filePath = $uploadDirectory . $fileName;
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        // Ensure upload directory exists
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName; // Return new file name for database storage
        }
    }
    return ''; // Return empty string if upload fails
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare contact settings data using new field names
    $data = [
        'contact_number'            => $_POST['contact_number'] ?? '',
        'contact_number_alternate'  => $_POST['contact_number_alternate'] ?? '',
        'whatsapp_number'           => $_POST['whatsapp_number'] ?? '',
        'email_id'                  => $_POST['email_id'] ?? '',
        'email_id_alternate'        => $_POST['email_id_alternate'] ?? '',
        'address_line_1'            => $_POST['address_line_1'] ?? '',
        'address_line_2'            => $_POST['address_line_2'] ?? '',
        'address_line_3'            => $_POST['address_line_3'] ?? '',
        'google_map_iframe'         => $_POST['google_map_iframe'] ?? '',
        'facebook_url'              => $_POST['facebook_url'] ?? '',
        'instagram_url'             => $_POST['instagram_url'] ?? '',
        'youtube_url'               => $_POST['youtube_url'] ?? '',
        'linkedin_url'              => $_POST['linkedin_url'] ?? '',
        'x_url'                     => $_POST['x_url'] ?? '',
        'custom_url'                => $_POST['custom_url'] ?? '',
        'extra_info_1'              => $_POST['extra_info_1'] ?? '',
        'extra_info_2'              => $_POST['extra_info_2'] ?? '',
        'song_text'                 => $_POST['song_text'] ?? ''
    ];

    // Retrieve existing contact settings for existing image
    $existingContactSettings = $adminModel->getContactSettings();
    $existingImage = $existingContactSettings['song_image'] ?? '';

    // Handle image upload
    $data['song_image'] = handleImageUpload($_FILES['song_image'], $existingImage);

    // Update contact settings
    if ($adminModel->updateContactSettings($data)) {
        $_SESSION['message'] = "Contact settings updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update contact settings.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect to prevent form resubmission
    header("Location: contact-setting");
    exit();
}