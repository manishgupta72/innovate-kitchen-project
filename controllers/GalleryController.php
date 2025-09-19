<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/GalleryModel.php';

$database = new Database();
$pdo = $database->connect();
$galleryModel = new Gallery($pdo);

// Define upload directories
$uploadDir = 'uploads/admin/gallery/';
$thumbDir  = 'uploads/admin/gallery/thumbs/';

// Function to handle a single file upload for a given directory
function handleSingleFileUpload($file, $uploadDirectory)
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

// Process multiple file uploads for "upload_file[]"
$newUploadFiles = [];
$newUploadFilesUploaded = false;
if (isset($_FILES['upload_file']) && !empty($_FILES['upload_file']['name'][0])) {
    $fileCount = count($_FILES['upload_file']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        if (isset($_FILES['upload_file']['error'][$i]) && $_FILES['upload_file']['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES['upload_file']['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($_FILES['upload_file']['tmp_name'][$i], $targetFilePath)) {
                $newUploadFiles[] = $fileName;
                $newUploadFilesUploaded = true;
            }
        }
    }
}

// For update: preserve existing files if no new file uploaded
$existingFiles = [];
if (isset($_POST['action']) && $_POST['action'] === 'update' && !empty($_POST['existing_upload_file'])) {
    $existingFiles = json_decode($_POST['existing_upload_file'], true);
    if (!is_array($existingFiles)) {
        $existingFiles = [];
    }
}

// If any deletion checkboxes were checked, remove those files from the existing files array and delete them
if (isset($_POST['delete_existing_files']) && is_array($_POST['delete_existing_files'])) {
    $filesToDelete = $_POST['delete_existing_files'];
    $existingFiles = array_filter($existingFiles, function ($file) use ($filesToDelete) {
        return !in_array($file, $filesToDelete);
    });
    foreach ($filesToDelete as $file) {
        $oldFilePath = $uploadDir . $file;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }
}

// Merge existing files (if updating) with newly uploaded files
$uploadFiles = [];
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $uploadFiles = array_merge($existingFiles, $newUploadFiles);
} else {
    $uploadFiles = $newUploadFiles;
}
// JSON encode the files array for storing in DB
$uploadFilesJson = json_encode($uploadFiles);

// Process thumb image upload (single file)
$thumbImage = '';
if (isset($_FILES['thumb_image'])) {
    $thumbImage = handleSingleFileUpload($_FILES['thumb_image'], $thumbDir);
}
$newThumbUploaded = !empty($thumbImage);

// For update: preserve existing thumb image if no new file uploaded
if (isset($_POST['action']) && $_POST['action'] === 'update' && !$newThumbUploaded && !empty($_POST['existing_thumb_image'])) {
    $thumbImage = $_POST['existing_thumb_image'];
}

// For update: if a new thumb image is uploaded, delete previous thumb image
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $galleryId = $_POST['id'] ?? null;
    if ($galleryId) {
        $existingGallery = $galleryModel->getGalleryItemById($galleryId);
        if ($newThumbUploaded && !empty($existingGallery['thumb_image'])) {
            $oldThumbPath = $thumbDir . $existingGallery['thumb_image'];
            if (file_exists($oldThumbPath)) {
                unlink($oldThumbPath);
            }
        }
    }
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $galleryId = $_POST['id'] ?? null;

    // Retrieve gallery fields from POST
    $galleryType = $_POST['gallery_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $youtubeUrl = $_POST['youtube_url'] ?? '';

    // Action handling
    switch ($action) {
        case 'add':
            if ($galleryModel->addGalleryItem($galleryType, $title, $description, $uploadFilesJson, $thumbImage, $youtubeUrl)) {
                $_SESSION['message'] = 'Gallery item added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add gallery item.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($galleryModel->updateGalleryItem($galleryId, $galleryType, $title, $description, $uploadFilesJson, $thumbImage, $youtubeUrl)) {
                $_SESSION['message'] = 'Gallery item updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update gallery item.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-gallery');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $galleryId = $_GET['id'];
    $existingGallery = $galleryModel->getGalleryItemById($galleryId);
    if ($existingGallery) {
        if (!empty($existingGallery['upload_file'])) {
            $files = json_decode($existingGallery['upload_file'], true);
            if (is_array($files)) {
                foreach ($files as $file) {
                    $filePath = $uploadDir . $file;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        if (!empty($existingGallery['thumb_image'])) {
            $thumbPath = $thumbDir . $existingGallery['thumb_image'];
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
    if ($galleryModel->deleteGalleryItem($galleryId)) {
        $_SESSION['message'] = 'Gallery item deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete gallery item.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-gallery');
    exit;
}
