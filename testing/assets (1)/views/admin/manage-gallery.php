<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/GalleryModel.php';

$database = new Database();
$pdo = $database->connect();
$galleryModel = new Gallery($pdo);

$galleryItems = $galleryModel->getAllGalleryItems();

// Load gallery types from master_data (where master_type = 3)
require_once 'model/MasterDataModel.php';
$masterDataModel = new MasterDataModel($pdo);
$galleryTypesData = $masterDataModel->getMasterDataByType(3);
$galleryTypesMapping = [];
foreach ($galleryTypesData as $type) {
    $galleryTypesMapping[$type['id']] = $type['name'];
}
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="card">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="card-title mb-0">Manage Gallery</h4>
                                <a href="add-gallery" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Gallery
                                </a>
                            </header>
                            <div class="card-body">
                                <?php if (isset($_SESSION['message'])) : ?>
                                    <div id="alert-message"
                                        class="alert alert-<?= $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show"
                                        role="alert">
                                        <?= $_SESSION['message']; ?>
                                    </div>
                                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                                <?php endif; ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                                        <thead>
                                            <tr>
                                                <th>Gallery Type</th>
                                                <th>Title</th>
                                                <th>YouTube URL</th>
                                                <th>Upload File</th>
                                                <th>Thumb Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($galleryItems) && !empty($galleryItems)) { ?>
                                                <?php foreach ($galleryItems as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($galleryTypesMapping[$item['gallery_type']] ?? ''); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td>
                                                            <?php if (!empty($item['youtube_url'])): ?>
                                                                <a href="<?= htmlspecialchars($item['youtube_url']); ?>"
                                                                    target="_blank">Open Link</a>
                                                            <?php else: ?>
                                                                No URL
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if (!empty($item['upload_file'])) {
                                                                $files = json_decode($item['upload_file'], true);
                                                                if (is_array($files) && !empty($files)) {
                                                                    foreach ($files as $file) {
                                                                        echo '<img src="' . UPLOAD_GALLERY . htmlspecialchars($file) . '" alt="Upload File" style="width: 50px; height: 50px; object-fit: cover; margin-right:5px;">';
                                                                    }
                                                                } else {
                                                                    echo 'No Image';
                                                                }
                                                            } else {
                                                                echo 'No Image';
                                                            }
                                                            ?>
                                                        </td>

                                                        <td>
                                                            <?php if (!empty($item['thumb_image'])): ?>
                                                                <img src="<?php echo UPLOAD_GALLERY . 'thumbs/' . htmlspecialchars($item['thumb_image']); ?>"
                                                                    alt="Thumb Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="edit-gallery?id=<?php echo $item['id']; ?>" data-toggle="tooltip"
                                                                title="Edit Gallery Item">
                                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                                            </a>
                                                            <span class="mx-1">|</span>
                                                            <a href="#" onclick="confirmDeleteGalleryItem(<?php echo $item['id']; ?>)"
                                                                data-toggle="tooltip" title="Delete Gallery Item">
                                                                <i class="mdi mdi-trash-can-outline"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7">No gallery items available.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div> <!-- End table-responsive -->
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>

<script>
    function confirmDeleteGalleryItem(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-gallery?action=delete&id=' + id;
        };
    }
</script>