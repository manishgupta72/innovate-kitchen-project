<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/ArtistModel.php';

$database = new Database();
$pdo = $database->connect();
$artistModel = new ArtistModel($pdo);

// Image upload function
function handleImageUpload($file, $existingFileName = '')
{
    if (empty($file['name'])) {
        return $existingFileName; // Use existing image if no new file uploaded
    }
    $uploadDirectory = 'uploads/admin/artist/';
    $fileName = time() . '_' . basename($file['name']);
    $filePath = $uploadDirectory . $fileName;
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName; // Return new file name for database storage
        }
    }
    return ''; // Return empty string if upload fails
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $artistId = $_POST['id'] ?? null;

    // Prepare artist data
    $artistName = $_POST['artist_name'] ?? '';
    $songIds = isset($_POST['song_ids']) ? implode(',', $_POST['song_ids']) : ''; // Convert array to comma-separated string

    // Get existing artist data if updating
    $existingArtist = $artistId ? $artistModel->getArtistById($artistId) : null;

    // Handle image upload
    $artistImage = handleImageUpload($_FILES['artist_image'], $existingArtist['artist_image'] ?? '');

    // Action handling
    switch ($action) {
        case 'add':
            if ($artistModel->addArtist($artistName, $songIds, $artistImage)) {
                $_SESSION['message'] = 'Artist added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to add artist.';
                $_SESSION['message_type'] = 'error';
            }
            break;

        case 'update':
            if ($artistModel->updateArtist($artistId, $artistName, $songIds, $artistImage)) {
                $_SESSION['message'] = 'Artist updated successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update artist.';
                $_SESSION['message_type'] = 'error';
            }
            break;
    }

    header('Location: manage-artist');
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $artistId = $_GET['id'];
    $existingArtist = $artistModel->getArtistById($artistId);

    if ($existingArtist && $artistModel->deleteArtist($artistId)) {
        // Delete the image file from the directory if it exists
        $uploadDirectory = 'uploads/admin/artist/';
        if (!empty($existingArtist['artist_image']) && file_exists($uploadDirectory . $existingArtist['artist_image'])) {
            unlink($uploadDirectory . $existingArtist['artist_image']);
        }
        $_SESSION['message'] = 'Artist deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete artist.';
        $_SESSION['message_type'] = 'error';
    }

    header('Location: manage-artist');
    exit;
}