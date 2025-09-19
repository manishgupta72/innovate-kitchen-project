<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/MediaModel.php';

$database = new Database();
$pdo = $database->connect();
$mediaModel = new MediaModel($pdo);

// Define upload directory (adjust if you use a constant)
$uploadDir = 'uploads/admin/media/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $mediaId = $_POST['id'] ?? null;

    // Retrieve updated media fields from POST
    $mediaType   = $_POST['media_type'] ?? '';
    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $url         = $_POST['url'] ?? '';
    $date        = $_POST['date'] ?? '';

    // Initialize variables for file upload
    $newFileUploaded = false; // flag for a new upload
    $uploadFile = null;
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($_FILES['upload_file']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $targetFilePath)) {
            $uploadFile = $fileName;  // Save only the file name
            $newFileUploaded = true;
        }
    }

    // If updating and no new file uploaded, preserve existing file
    if ($action === 'update' && !$newFileUploaded && !empty($_POST['existing_upload_file'])) {
        $uploadFile = $_POST['existing_upload_file'];
    }

    // Only if a new file was actually uploaded, delete the previous file from the directory
    if ($action === 'update' && $newFileUploaded && $mediaId) {
        $existingMedia = $mediaModel->getMediaById($mediaId);
        if (!empty($existingMedia['upload_file'])) {
            $oldFilePath = $uploadDir . $existingMedia['upload_file'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    }

    // Action handling
    switch ($action) {
        case 'add':
            if ($mediaModel->addMedia($mediaType, $title, $description, $url, $date, $uploadFile)) {
                $_SESSION['message'] = 'Media item added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add media item.';
                $_SESSION['message_type'] = 'error';
            }
            break;

        case 'update':
            if ($mediaModel->updateMedia($mediaId, $mediaType, $title, $description, $url, $date, $uploadFile)) {
                $_SESSION['message'] = 'Media item updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update media item.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }

    header('Location: manage-media');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $mediaId = $_GET['id'];
    // Fetch existing media record to get file name
    $existingMedia = $mediaModel->getMediaById($mediaId);
    if (!empty($existingMedia['upload_file'])) {
        $filePath = $uploadDir . $existingMedia['upload_file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    if ($mediaModel->deleteMedia($mediaId)) {
        $_SESSION['message'] = 'Media item deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete media item.';
        $_SESSION['message_type'] = 'error';
    }

    header('Location: manage-media');
    exit;
}