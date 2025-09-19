<?php
require_once 'model/MasterDataModel.php';

$database        = new Database();
$pdo             = $database->connect();
$masterDataModel = new MasterDataModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action      = $_POST['action'] ?? '';
    $id          = $_POST['id'] ?? '';
    $name        = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $masterType  = $_POST['master_type'] ?? '';

    // Define the mapping for master types (should match your view's mapping)
    $masterTypesMapping = [
        1 => 'Media Type',
        2 => 'Content Type',
        // Add other mappings if needed
    ];
    // Determine the master_name based on the master type value
    $masterName = $masterTypesMapping[$masterType] ?? '';

    if ($action === 'add') {
        if ($masterDataModel->addMasterData($name, $description, $masterType, $masterName)) {
            $_SESSION['message']      = "Master data added successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message']      = "Failed to add master data.";
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'edit') {
        if ($masterDataModel->updateMasterData($id, $name, $description, $masterType, $masterName)) {
            $_SESSION['message']      = "Master data updated successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message']      = "Failed to update master data.";
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'delete') {
        if ($masterDataModel->deleteMasterData($id)) {
            $_SESSION['message']      = "Master data deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message']      = "Failed to delete master data.";
            $_SESSION['message_type'] = "danger";
        }
    }

    // Redirect to prevent form resubmission
    header("Location: master-data-setting");
    exit();
}