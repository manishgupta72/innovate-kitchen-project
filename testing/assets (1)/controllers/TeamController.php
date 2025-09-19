<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/TeamModel.php';

$database = new Database();
$pdo = $database->connect();
$teamModel = new TeamModel($pdo);

// Define upload directory for team images
$uploadDir = 'uploads/admin/team/';

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
    return ''; // Return an empty string if no file was uploaded
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the action and team id from POST (using null coalescing operator)
    $action = $_POST['action'] ?? '';
    $teamId = $_POST['id'] ?? null;

    // Retrieve team fields from POST
    $teamType    = $_POST['team_type'] ?? '';
    $title       = $_POST['title'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $description = $_POST['description'] ?? '';
    $url         = $_POST['url'] ?? '';

    // Process file upload for team image
    $newUploadImage = handleFileUpload($_FILES['upload_image'] ?? null, $uploadDir);

    // For update action, if a new image is uploaded, then use it and delete the old file.
    // Otherwise, preserve the existing image from the hidden field.
    if ($action === 'update') {
        if ($newUploadImage !== '') {
            $uploadImage = $newUploadImage;
            // Delete the old image if it exists
            $existingTeam = $teamModel->getTeamById($teamId);
            if (!empty($existingTeam['upload_image'])) {
                $oldFilePath = $uploadDir . $existingTeam['upload_image'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        } else {
            $uploadImage = (isset($_POST['existing_upload_image']) && $_POST['existing_upload_image'] !== '')
                ? $_POST['existing_upload_image']
                : '';
        }
    } else {
        // For add action, simply use the new upload (which might be empty)
        $uploadImage = $newUploadImage;
    }

    // Action handling
    switch ($action) {
        case 'add':
            if ($teamModel->addTeam($teamType, $title, $designation, $description, $uploadImage, $url)) {
                $_SESSION['message'] = 'Team member added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add team member.';
                $_SESSION['message_type'] = 'error';
            }
            break;
        case 'update':
            if ($teamModel->updateTeam($teamId, $teamType, $title, $designation, $description, $uploadImage, $url)) {
                $_SESSION['message'] = 'Team member updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update team member.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }
    header('Location: manage-team');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $teamId = $_GET['id'];
    $existingTeam = $teamModel->getTeamById($teamId);
    if ($existingTeam && !empty($existingTeam['upload_image'])) {
        $filePath = $uploadDir . $existingTeam['upload_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    if ($teamModel->deleteTeam($teamId)) {
        $_SESSION['message'] = 'Team member deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete team member.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: manage-team');
    exit;
}