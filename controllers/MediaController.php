<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/MediaModel.php';

$database = new Database();
$pdo = $database->connect();
$mediaModel = new MediaModel($pdo);

// ✅ Image upload function
function handleImageUpload($file, $existingFileName = '')
{
    if (empty($file['name'])) {
        return $existingFileName;
    }

    $uploadDirectory = 'uploads/admin/media/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $fileName = time() . '_' . uniqid() . '.webp';
    $filePath = $uploadDirectory . $fileName;

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

// 🌐 Handle Form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $mediaId = $_POST['id'] ?? null;

    $mediaType   = $_POST['media_type'] ?? '';
    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $url         = $_POST['url'] ?? '';
    $date        = !empty($_POST['date']) ? $_POST['date'] : null;

    // Handle new or existing upload
    $existingFile = $_POST['existing_upload_file'] ?? '';
    $uploadFile = handleImageUpload($_FILES['upload_file'], $existingFile);

    // Delete old file if a new one was uploaded
    if ($action === 'update' && $uploadFile !== $existingFile && !empty($existingFile)) {
        $oldFilePath = 'uploads/admin/media/' . $existingFile;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // Action logic
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

// 🗑️ Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $mediaId = $_GET['id'];
    $existingMedia = $mediaModel->getMediaById($mediaId);

    if (!empty($existingMedia['upload_file'])) {
        $filePath = 'uploads/admin/media/' . $existingMedia['upload_file'];
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
