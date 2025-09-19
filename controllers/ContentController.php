<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ContentModel.php';

$database = new Database();
$pdo = $database->connect();
$contentModel = new ContentModel($pdo);

// WebP Image Upload Handler
function handleImageUpload($file, $existingFileName = '', $uploadDir = 'uploads/admin/content/')
{
    if (empty($file['name'])) {
        return $existingFileName;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = time() . '_' . uniqid() . '.webp';
    $filePath = $uploadDir . $fileName;

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $file['tmp_name'];
        $mimeType = mime_content_type($tmpPath);

        switch ($mimeType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($tmpPath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($tmpPath);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($tmpPath);
                break;
            default:
                return '';
        }

        if (!$source) return '';

        $maxWidth = 1200;
        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newWidth = $maxWidth;
            $newHeight = intval($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (!imagewebp($resized, $filePath, 85)) {
            return '';
        }

        imagedestroy($source);
        imagedestroy($resized);

        return $fileName;
    }

    return '';
}

// Handle POST Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $contentId = $_POST['id'] ?? null;

    $contentType = $_POST['content_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';

    // Process uploads
    $uploadDir = 'uploads/admin/content/';
    $existing1 = $_POST['existing_upload_image'] ?? '';
    $existing2 = $_POST['existing_upload_image2'] ?? '';

    $uploadImage  = handleImageUpload($_FILES['upload_image'], $existing1, $uploadDir);
    $uploadImage2 = handleImageUpload($_FILES['upload_image2'], $existing2, $uploadDir);

    // Delete old files if new uploaded
    if ($action === 'update' && $contentId) {
        $existingContent = $contentModel->getContentById($contentId);

        if ($uploadImage !== $existing1 && !empty($existingContent['upload_image'])) {
            $oldPath = $uploadDir . $existingContent['upload_image'];
            if (file_exists($oldPath)) unlink($oldPath);
        }

        if ($uploadImage2 !== $existing2 && !empty($existingContent['upload_image2'])) {
            $oldPath2 = $uploadDir . $existingContent['upload_image2'];
            if (file_exists($oldPath2)) unlink($oldPath2);
        }
    }

    // Save to database
    $success = false;
    if ($action === 'add') {
        $success = $contentModel->addContent($contentType, $title, $description, $url, $uploadImage, $uploadImage2);
        $_SESSION['message'] = $success ? 'Content added successfully.' : 'Failed to add content.';
    } elseif ($action === 'update') {
        $success = $contentModel->updateContent($contentId, $contentType, $title, $description, $url, $uploadImage, $uploadImage2);
        $_SESSION['message'] = $success ? 'Content updated successfully.' : 'Failed to update content.';
    }

    $_SESSION['message_type'] = $success ? 'success' : 'error';
    header('Location: manage-content');
    exit;
}

// Handle Delete
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $contentId = $_GET['id'];
    $existingContent = $contentModel->getContentById($contentId);

    if (!empty($existingContent['upload_image'])) {
        $filePath = $uploadDir . $existingContent['upload_image'];
        if (file_exists($filePath)) unlink($filePath);
    }

    if (!empty($existingContent['upload_image2'])) {
        $filePath2 = $uploadDir . $existingContent['upload_image2'];
        if (file_exists($filePath2)) unlink($filePath2);
    }

    $deleted = $contentModel->deleteContent($contentId);
    $_SESSION['message'] = $deleted ? 'Content deleted successfully.' : 'Failed to delete content.';
    $_SESSION['message_type'] = $deleted ? 'success' : 'error';

    header('Location: manage-content');
    exit;
}
