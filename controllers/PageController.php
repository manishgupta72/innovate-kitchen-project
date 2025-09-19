<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/PageModel.php';

$database = new Database();
$pdo = $database->connect();
$pageModel = new PageModel($pdo);

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $pageId = $_POST['id'] ?? null;

    // Retrieve page fields from POST
    $pageType   = $_POST['page_type'] ?? '';
    $title      = $_POST['title'] ?? '';
    $subTitle   = $_POST['sub_title'] ?? '';
    $description = $_POST['description'] ?? '';
    $slug       = $_POST['slug'] ?? '';

    // Auto-generate slug from title if slug is empty
    if (empty($slug) && !empty($title)) {
        $slug = generateSlug($title);
    }

    switch ($action) {
        case 'add':
            if ($pageModel->addPage($pageType, $title, $subTitle, $description, $slug)) {
                $_SESSION['message'] = 'Page added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add page.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($pageModel->updatePage($pageId, $pageType, $title, $subTitle, $description, $slug)) {
                $_SESSION['message'] = 'Page updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update page.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-page');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $pageId = $_GET['id'];
    if ($pageModel->deletePage($pageId)) {
        $_SESSION['message'] = 'Page deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete page.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-page');
    exit;
}
