<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ReviewModel.php';

$database = new Database();
$pdo = $database->connect();
$reviewModel = new ReviewModel($pdo);

// Define upload directory for review author images
$uploadDir = 'uploads/admin/review/';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $reviewId = $_POST['id'] ?? null;

    // Retrieve review fields from POST
    $reviewType  = $_POST['review_type'] ?? '';
    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $authorName  = $_POST['author_name'] ?? '';
    $ratings     = $_POST['ratings'] ?? '';
    $publishDate = $_POST['publish_date'] ?? '';

    // Process author image upload
    $newAuthorImage = handleFileUpload($_FILES['author_image'] ?? null, $uploadDir);

    // For update: if no new file is uploaded, preserve existing image
    if ($action === 'update') {
        if ($newAuthorImage === '' && isset($_POST['existing_author_image']) && $_POST['existing_author_image'] !== '') {
            $authorImage = $_POST['existing_author_image'];
        } else {
            $authorImage = $newAuthorImage;
            // If new file uploaded, delete old image
            if ($reviewId) {
                $existingReview = $reviewModel->getReviewById($reviewId);
                if (!empty($existingReview['author_image'])) {
                    $oldFilePath = $uploadDir . $existingReview['author_image'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }
        }
    } else {
        $authorImage = $newAuthorImage;
    }

    switch ($action) {
        case 'add':
            if ($reviewModel->addReview($reviewType, $title, $description, $authorName, $ratings, $authorImage, $publishDate)) {
                $_SESSION['message'] = 'Review added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add review.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($reviewModel->updateReview($reviewId, $reviewType, $title, $description, $authorName, $ratings, $authorImage, $publishDate)) {
                $_SESSION['message'] = 'Review updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update review.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-review');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $reviewId = $_GET['id'];
    $existingReview = $reviewModel->getReviewById($reviewId);
    if ($existingReview && !empty($existingReview['author_image'])) {
        $filePath = $uploadDir . $existingReview['author_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    if ($reviewModel->deleteReview($reviewId)) {
        $_SESSION['message'] = 'Review deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete review.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-review');
    exit;
}
