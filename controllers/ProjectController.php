<?php
require_once 'model/ProjectModel.php';

$database = new Database();
$pdo = $database->connect();
$projectModel = new ProjectModel($pdo);

// WebP Image Upload Function
function handleImageUpload($file, $existingFileName = '')
{
    if (empty($file['name'])) {
        return $existingFileName;
    }

    $uploadDirectory = 'uploads/admin/project/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
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
                return ''; // Unsupported
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

// Main logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $projectId = $_POST['id'] ?? null;

    $projectType = $_POST['project_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';
    $date = !empty($_POST['date']) ? $_POST['date'] : null;

    $existingFile = $_POST['existing_upload_file'] ?? '';
    $uploadFile = handleImageUpload($_FILES['upload_file'], $existingFile);

    // Delete old file if a new one was uploaded
    if ($action === 'update' && $uploadFile !== $existingFile && !empty($existingFile)) {
        $oldPath = 'uploads/admin/project/' . $existingFile;
        if (file_exists($oldPath)) unlink($oldPath);
    }

    switch ($action) {
        case 'add':
            $projectModel->addProject($projectType, $title, $description, $url, $uploadFile, $date);
            $_SESSION['message'] = 'Project added successfully.';
            $_SESSION['message_type'] = 'success';
            break;

        case 'update':
            $projectModel->updateProject($projectId, $projectType, $title, $description, $url, $uploadFile, $date);
            $_SESSION['message'] = 'Project updated successfully.';
            $_SESSION['message_type'] = 'success';
            break;
    }

    header('Location: manage-project');
    exit;
}

// Delete logic
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $projectId = $_GET['id'];
    $existing = $projectModel->getProjectById($projectId);
    if (!empty($existing['upload_file'])) {
        $filePath = 'uploads/admin/project/' . $existing['upload_file'];
        if (file_exists($filePath)) unlink($filePath);
    }
    $projectModel->deleteProject($projectId);
    $_SESSION['message'] = 'Project deleted successfully.';
    $_SESSION['message_type'] = 'success';
    header('Location: manage-project');
    exit;
}
