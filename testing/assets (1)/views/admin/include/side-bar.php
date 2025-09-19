<?php
require_once 'model/UserModel.php';
$database = new Database();
$pdo = $database->connect();

$userModel = new UserModel($pdo);

if (isset($_SESSION['username'])) {
    $loggedInUser = $userModel->getUserByUsername($_SESSION['username']);

    if ($loggedInUser) {
        $userRole = $userModel->getUserRoleById($loggedInUser['user_id']);
    }
}

if (!isset($userRole)) {
    $userRole = 'admin';
}
?>

<!-- Sidebar Start -->
<div class="app-sidebar">
    <!-- <div class="logo"> -->
    <!-- <a href="dashboard" class="logo-icon"><span class="logo-text"><?php echo htmlspecialchars($settings['system_name']); ?></span></a> -->

    <!-- <a href="#">
            <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['admin_logo']); ?>" width="200"
                alt="" style="object-fit: cover;" height="40px" />



        </a> -->

    <!-- </div> -->
    <div class="app-menu">
        <ul class="accordion-menu">
            <li class="sidebar-title mb-4"> <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['admin_logo']); ?>" width="200"
                    alt="" style="object-fit: cover;" height="40px" /></li>

            <li class="active-page">
                <a href="dashboard" class="active"><i class="material-icons-two-tone">dashboard</i>Dashboard</a>
            </li>

            <li>
                <a href="#"><i class="material-icons-two-tone">settings</i>Master Setting<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="general-setting">General Setting</a></li>
                    <li><a href="contact-setting">Contact Setup</a></li>
                    <li><a href="counter-setting">Counter Setup</a></li>
                    <li><a href="master-data-setting">Master Data</a></li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="material-icons-two-tone">perm_media</i>Media<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-media">Add Media</a></li>
                    <li><a href="manage-media">Manage Media</a></li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="material-icons-two-tone">description</i>Content<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-content">Add Content</a></li>
                    <li><a href="manage-content">Manage Content</a></li>
                </ul>
            </li>
            <!-- <li>
                <a href="#"><i class="material-icons-two-tone">description</i>Gallery<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-gallery">Add Gallery</a></li>
                    <li><a href="manage-gallery">Manage Gallery</a></li>
                </ul>
            </li> -->

            <!-- <li>
                <a href="#"><i class="material-icons-two-tone">group</i>Team<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-team">Add Team</a></li>
                    <li><a href="manage-team">Manage Team</a></li>
                </ul>
            </li> -->

            <!-- <li>
                <a href="#"><i class="material-icons-two-tone">build_circle</i>Services<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-service">Add Service</a></li>
                    <li><a href="manage-service">Manage Service</a></li>
                </ul>
            </li> -->

            <li>
                <a href="#"><i class="material-icons-two-tone">pages</i>Pages<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-page">Add Pages</a></li>
                    <li><a href="manage-page">Manage Pages</a></li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="material-icons-two-tone">rate_review</i>Reviews<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li><a href="add-review">Add Review</a></li>
                    <li><a href="manage-review">Manage Review</a></li>
                </ul>
            </li>

            <li>
                <a href="change-password"><i class="material-icons-two-tone">lock</i>Change Password</a>
            </li>

            <li>
                <a href="logout"><i class="material-icons-two-tone">logout</i>Logout</a>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar End -->