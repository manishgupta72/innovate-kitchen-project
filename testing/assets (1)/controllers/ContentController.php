<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ContentModel.php';

$database = new Database();
$pdo = $database->connect();
$contentModel = new ContentModel($pdo);

// Define the upload directory (use your constant or literal path)
$uploadDir = 'uploads/admin/content/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $contentId = $_POST['id'] ?? null;

    // Retrieve content fields from POST
    $contentType = $_POST['content_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';

    // Initialize file upload variables
    $newFileUploaded = false;
    $newFileUploaded2 = false;
    $uploadImage = null;
    $uploadImage2 = null;
    if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($_FILES['upload_image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $targetFilePath)) {
            $uploadImage = $fileName;
            $newFileUploaded = true;
        }
    }
    if (isset($_FILES['upload_image2']) && $_FILES['upload_image2']['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($_FILES['upload_image2']['name']);
        $targetFilePath = $uploadDir . $fileName;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (move_uploaded_file($_FILES['upload_image2']['tmp_name'], $targetFilePath)) {
            $uploadImage2 = $fileName;
            $newFileUploaded2 = true;
        }
    }

    // If updating and no new file was uploaded, preserve the existing file
    if ($action === 'update' && !$newFileUploaded && !empty($_POST['existing_upload_image'])) {
        $uploadImage = $_POST['existing_upload_image'];
    }
    if ($action === 'update' && !$newFileUploaded2 && !empty($_POST['existing_upload_image2'])) {
        $uploadImage2 = $_POST['existing_upload_image2'];
    }


    // If a new file was uploaded during update, delete the previous image
    if ($action === 'update' && $newFileUploaded && $contentId) {
        $existingContent = $contentModel->getContentById($contentId);
        if (!empty($existingContent['upload_image'])) {
            $oldFilePath = $uploadDir . $existingContent['upload_image'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    }
    if ($action === 'update' && $newFileUploaded2 && $contentId) {
        $existingContent = $contentModel->getContentById($contentId);
        if (!empty($existingContent['upload_image2'])) {
            $oldFilePath2 = $uploadDir . $existingContent['upload_image2'];
            if (file_exists($oldFilePath2)) {
                unlink($oldFilePath2);
            }
        }
    }


    // Action handling
    switch ($action) {
        case 'add':
            if ($contentModel->addContent($contentType, $title, $description, $url, $uploadImage, $uploadImage2)) {
                $_SESSION['message'] = 'Content added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add content.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($contentModel->updateContent($contentId, $contentType, $title, $description, $url, $uploadImage, $uploadImage2)) {
                $_SESSION['message'] = 'Content updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update content.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-content');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $contentId = $_GET['id'];
    $existingContent = $contentModel->getContentById($contentId);
    if (!empty($existingContent['upload_image'])) {
        $filePath = $uploadDir . $existingContent['upload_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    if ($contentModel->deleteContent($contentId)) {
        $_SESSION['message'] = 'Content deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete content.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-content');
    exit;
}
