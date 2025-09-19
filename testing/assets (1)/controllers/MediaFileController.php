<?php
require_once 'model/MediaFileModel.php';

$database = new Database();
$pdo = $database->connect();
$mediaFileModel = new MediaFileModel($pdo);

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($mediaFileModel->deleteMediaFile($id)) {
        $_SESSION['message'] = 'Media file deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete media file.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: media-file');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;
    $mediaType = $_POST['media_type'] ?? '';
    $mediaName = $_POST['media_name'] ?? '';
    $media_url = $_POST['media_url'] ?? '';
    $createdBy = $_SESSION['user']['id'];
    $media_upload = '';

    // Check for empty fields
    if (empty($mediaType) || empty($mediaName) || !isset($_FILES['media_file']) || $_FILES['media_file']['error'] != 0) {
        $_SESSION['message'] = 'All fields are required.';
        $_SESSION['message_type'] = 'error';
        header('Location: media-file');
        exit;
    }

  // Check for duplicate media name (for both add and update)
  if ($mediaFileModel->isDuplicateMediaName($mediaName, $id)) {
    $_SESSION['message'] = 'Media name already exists.';
    $_SESSION['message_type'] = 'error';
    header('Location: media-file');
    exit;
}

// Handling POST request for Add and Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $media_upload = '';

    // Handle file upload
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
        $fileName = time() . '_' . basename($_FILES['media_file']['name']);
        $targetPath = 'uploads/admin/'. $fileName; // Ensure UPLOAD_IMG_ADMIN has the correct path

        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $targetPath)) {
            $media_upload = $fileName; // Save only file name in the database
        }
    }



    switch ($action) {
        case 'add':
            // Extract and sanitize input data
            $mediaType = htmlspecialchars($_POST['media_type']);
            $mediaName = htmlspecialchars($_POST['media_name']);
            $createdBy = $_SESSION['user']['id'];

            // Add media file to the database
            if ($mediaFileModel->addMediaFile($mediaType, $mediaName, $media_url, $media_upload, $createdBy)) {
                $_SESSION['message'] = 'New media file added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add media file.';
                $_SESSION['message_type'] = 'error';
            }
            break;

        case 'update':
            $id = $_POST['id'];
            $mediaType = htmlspecialchars($_POST['media_type']);
            $mediaName = htmlspecialchars($_POST['media_name']);
            $updatedBy = $_SESSION['user']['id'];

            // Use existing media URL if new file is not uploaded
            $existingMediaUrl = $_POST['media_upload'] ?? '';
            $media_upload = $media_upload ?: $existingMediaUrl;

            // Update media file in the database
            if ($mediaFileModel->updateMediaFile($id, $mediaType, $mediaName, $media_url, $media_upload, $updatedBy)) {
                $_SESSION['message'] = 'Media file updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update media file.';
                $_SESSION['message_type'] = 'error';
            }
            break;

        // Additional cases (if any)...
    }
}

    header('Location: media-file');
    exit;
}
