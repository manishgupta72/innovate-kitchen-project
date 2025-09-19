<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ServiceModel.php';

$database = new Database();
$pdo = $database->connect();
$serviceModel = new ServiceModel($pdo);

// Define upload directory for service thumb images
$uploadDir = 'uploads/admin/services/';

// Helper function to generate a slug from a string
function generateSlug($string)
{
    // Convert to lowercase, trim whitespace, and remove unwanted characters
    $slug = strtolower(trim($string));
    // Replace non letter or digits by -
    $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
    // Transliterate characters to ASCII
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    // Remove unwanted characters
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    // Trim
    $slug = trim($slug, '-');
    // Remove duplicate -
    $slug = preg_replace('~-+~', '-', $slug);
    // Return 'n-a' if slug is empty
    return $slug ? $slug : 'n-a';
}

// Function to handle single file upload
function handleFileUpload($file, $uploadDirectory)
{
    $fileName = '';
    if (isset($file) && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($file['name']);
        $targetFilePath = $uploadDirectory . $fileName;
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $fileName;
        }
    }
    return '';
}

// Handle POST Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $serviceId = $_POST['id'] ?? null;

    // Retrieve service fields from POST
    $servicesType = $_POST['services_type'] ?? '';
    $title        = $_POST['title'] ?? '';
    $subTitle     = $_POST['sub_title'] ?? '';
    $description  = $_POST['description'] ?? '';
    $slug         = $_POST['slug'] ?? '';
    $url          = $_POST['url'] ?? '';
    $displayOrder = $_POST['display_order'] ?? 0;
    $displayType  = $_POST['display_type'] ?? 0;
    $display      = isset($_POST['display']) ? 1 : 0;
    $qaData       = isset($_POST['qa']) ? array_values($_POST['qa']) : [];

    // Auto-generate slug if not provided (using title)
    if (empty($slug) && !empty($title)) {
        $slug = generateSlug($title);
    }

    // Process thumb image upload
    $newThumbImage = handleFileUpload($_FILES['thumb_image'] ?? null, $uploadDir);

    // Handle Update: If new image is uploaded, delete old one
    if ($action === 'update') {
        if (!empty($newThumbImage)) {
            $thumbImage = $newThumbImage;
            $existingService = $serviceModel->getServiceById($serviceId);
            if (!empty($existingService['thumb_image'])) {
                $oldFilePath = $uploadDir . $existingService['thumb_image'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        } else {
            $thumbImage = $_POST['existing_thumb_image'] ?? '';
        }
    } else {
        $thumbImage = $newThumbImage;
    }

    // Action Handling
    switch ($action) {
        case 'add':
            if ($serviceModel->addService($servicesType, $title, $subTitle, $description, $thumbImage, $slug, $url, $displayOrder, $displayType, $display, $qaData)) {
                $_SESSION['message'] = 'Service added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add service.';
                $_SESSION['message_type'] = 'error';
            }
            break;

        case 'update':
            if ($serviceModel->updateService($serviceId, $servicesType, $title, $subTitle, $description, $thumbImage, $slug, $url, $displayOrder, $displayType, $display, $qaData)) {
                $_SESSION['message'] = 'Service updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update service.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-service');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $serviceId = $_GET['id'];
    $existingService = $serviceModel->getServiceById($serviceId);
    if ($existingService && !empty($existingService['thumb_image'])) {
        $filePath = $uploadDir . $existingService['thumb_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    if ($serviceModel->deleteService($serviceId)) {
        $_SESSION['message'] = 'Service deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete service.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-service');
    exit;
}
