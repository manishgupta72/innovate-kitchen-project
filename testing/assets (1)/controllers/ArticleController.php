<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ArticleModel.php';

// Define upload directories
$uploadDir = 'uploads/admin/articles/';
$thumbDir  = 'uploads/admin/articles/thumbs/';

// Helper function to generate a slug from a string
function generateSlug($string)
{
    $slug = strtolower(trim($string));
    $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    $slug = trim($slug, '-');
    $slug = preg_replace('~-+~', '-', $slug);
    return $slug ? $slug : 'n-a';
}

// Function to handle a single file upload for a given directory
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

// Process multiple file uploads for "upload_images[]"
$newUploadFiles = [];
$newUploadFilesUploaded = false;
if (isset($_FILES['upload_images']) && !empty($_FILES['upload_images']['name'][0])) {
    $fileCount = count($_FILES['upload_images']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        if (isset($_FILES['upload_images']['error'][$i]) && $_FILES['upload_images']['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES['upload_images']['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($_FILES['upload_images']['tmp_name'][$i], $targetFilePath)) {
                $newUploadFiles[] = $fileName;
                $newUploadFilesUploaded = true;
            }
        }
    }
}

// For update: preserve existing files if no new file uploaded
$existingFiles = [];
if (isset($_POST['action']) && $_POST['action'] === 'update' && !empty($_POST['existing_upload_images'])) {
    $existingFiles = json_decode($_POST['existing_upload_images'], true);
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
    $thumbImage = handleFileUpload($_FILES['thumb_image'], $thumbDir);
}
$newThumbUploaded = !empty($thumbImage);

// For update: preserve existing thumb image if no new file uploaded
if (isset($_POST['action']) && $_POST['action'] === 'update' && !$newThumbUploaded && !empty($_POST['existing_thumb_image'])) {
    $thumbImage = $_POST['existing_thumb_image'];
}

// For update: if a new thumb image is uploaded, delete previous thumb image
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $articleId = $_POST['id'] ?? null;
    if ($articleId) {
        // We need to instantiate the model to fetch the existing article.
        // (We assume the database and $pdo are available at this point.)
        require_once 'model/ArticleModel.php';
        $articleModel = new ArticleModel($pdo);
        $existingArticle = $articleModel->getArticleById($articleId);
        if ($newThumbUploaded && !empty($existingArticle['thumb_image'])) {
            $oldThumbPath = $thumbDir . $existingArticle['thumb_image'];
            if (file_exists($oldThumbPath)) {
                unlink($oldThumbPath);
            }
        }
    }
}

// Now handle POST actions for add/update/delete:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get action and article id
    $action = $_POST['action'] ?? '';
    $articleId = $_POST['id'] ?? null;

    // Retrieve article fields from POST
    $articlesType = $_POST['articles_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $subTitle = $_POST['sub_title'] ?? '';
    $description = $_POST['description'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $publishDate = $_POST['publish_date'] ?? '';

    // Auto-generate slug if empty using title
    if (empty($slug) && !empty($title)) {
        $slug = generateSlug($title);
    }

    // Include our ArticleModel (if not already included)
    require_once 'model/ArticleModel.php';
    $articleModel = new ArticleModel($pdo);

    switch ($action) {
        case 'add':
            if ($articleModel->addArticle($articlesType, $title, $subTitle, $description, $slug, $thumbImage, $uploadFilesJson, $publishDate)) {
                $_SESSION['message'] = 'Article added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add article.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($articleModel->updateArticle($articleId, $articlesType, $title, $subTitle, $description, $slug, $thumbImage, $uploadFilesJson, $publishDate)) {
                $_SESSION['message'] = 'Article updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update article.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-article');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $articleId = $_GET['id'];
    require_once 'model/ArticleModel.php';
    $articleModel = new ArticleModel($pdo);
    $existingArticle = $articleModel->getArticleById($articleId);
    if ($existingArticle) {
        // Delete multiple upload images
        if (!empty($existingArticle['upload_images'])) {
            $files = json_decode($existingArticle['upload_images'], true);
            if (is_array($files)) {
                foreach ($files as $file) {
                    $filePath = $uploadDir . $file;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        // Delete thumb image
        if (!empty($existingArticle['thumb_image'])) {
            $thumbPath = $thumbDir . $existingArticle['thumb_image'];
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
    if ($articleModel->deleteArticle($articleId)) {
        $_SESSION['message'] = 'Article deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete article.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-article');
    exit;
}
