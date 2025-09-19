<?php
require_once 'model/AdminModel.php';

$adminModel = new AdminModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'counter_name_1' => $_POST['counter_name_1'] ?? '',
        'count_no_1'     => $_POST['count_no_1'] ?? 0,
        'counter_name_2' => $_POST['counter_name_2'] ?? '',
        'count_no_2'     => $_POST['count_no_2'] ?? 0,
        'counter_name_3' => $_POST['counter_name_3'] ?? '',
        'count_no_3'     => $_POST['count_no_3'] ?? 0,
        'counter_name_4' => $_POST['counter_name_4'] ?? '',
        'count_no_4'     => $_POST['count_no_4'] ?? 0,
    ];

    if ($adminModel->updateCounterSettings($data)) {
        $_SESSION['message'] = "Counter settings updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update counter settings.";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: counter-setting");
    exit();
}