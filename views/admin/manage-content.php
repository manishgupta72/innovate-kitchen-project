<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ContentModel.php';
require_once 'model/MasterDataModel.php';



$database = new Database();
$pdo = $database->connect();
$contentModel = new ContentModel($pdo);

$contentItems = $contentModel->getAllContent();

$masterDataModel = new MasterDataModel($pdo);
$contentTypesData = $masterDataModel->getMasterDataByType(2); // 2 represents Content Type

$contentTypesMapping = [];
foreach ($contentTypesData as $type) {
    $contentTypesMapping[$type['id']] = $type['name'];
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
                                <h4 class="card-title mb-0">Manage Content</h4>
                                <a href="add-content" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Content
                                </a>
                            </header>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Content Type</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>URL</th>
                                                <th>Upload Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($contentItems) && !empty($contentItems)) { ?>
                                                <?php foreach ($contentItems as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($contentTypesMapping[$item['content_type']] ?? ''); ?>
                                                        </td>

                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td>
                                                            <?= $item['description'] ?? ''; ?>
                                                        </td>



                                                        <td>
                                                            <?php if (!empty($item['url'])): ?>
                                                                <a href="<?= htmlspecialchars($item['url']); ?>" target="_blank">Open
                                                                    Link</a>
                                                            <?php else: ?>
                                                                No URL
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($item['upload_image'])): ?>
                                                                <img src="<?php echo UPLOAD_CONTENT . htmlspecialchars($item['upload_image']); ?>"
                                                                    alt="Upload Image"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <a href="edit-content?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                                            </a>
                                                            <a href="#" onclick="confirmDeleteContent(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="6 text-center">No content available.</td>
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

</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>

<script>
    function confirmDeleteContent(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-content?action=delete&id=' + id;
        };
    }
</script>
<?php if (isset($_SESSION['message'])): ?>
    <script>
        window.addEventListener("load", function() {
            setTimeout(function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?= $_SESSION['message_type'] === 'success' ? 'success' : 'error' ?>',
                    title: <?= json_encode($_SESSION['message']) ?>,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }, 300);
        });
    </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>