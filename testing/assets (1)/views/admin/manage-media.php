<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/MediaModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$masterDataModel = new MasterDataModel($pdo);
$mediaModel = new MediaModel($pdo);

$masterDataRows = $masterDataModel->getMasterDataByType(1);
$mediaItems = $mediaModel->getAllMedia();
$companyDetails = $adminModel->getCompanyDetails() ?? [];

$mediaTypesMapping = [];
foreach ($masterDataRows as $row) {
    $mediaTypesMapping[$row['id']] = $row['name'];
}
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="card-title mb-0">Manage Media</h4>
                                <a href="add-media" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Media Item
                                </a>
                            </header>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Media Type</th>
                                                <th>Title</th>
                                                <th>URL</th>
                                                <th>Date</th>
                                                <th>Upload File</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($mediaItems as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($mediaTypesMapping[$item['media_type']] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($item['title'] ?? 'No Title') ?></td>
                                                    <td>
                                                        <?php if (!empty($item['url'])): ?>
                                                            <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank">Open Link</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">No URL</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($item['date'] ?? 'No Date') ?></td>
                                                    <td>
                                                        <?php if (!empty($item['upload_file'])): ?>
                                                            <img src="<?= UPLOAD_MEDIA . htmlspecialchars($item['upload_file']) ?>" alt="Upload File" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <img src="<?= UPLOAD_COMPANY . htmlspecialchars($companyDetails['company_logo_invoice'] ?? '') ?>" alt="Default Image" class="img-thumbnail" style="max-height: 50px;">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit-media?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-pen-to-square fa-lg"></i>
                                                        </a>
                                                        <a href="#" onclick="confirmDeleteMediaItem(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="fas fa-trash fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>

<script>
    function confirmDeleteMediaItem(id) {
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-media?action=delete&id=' + id;
        };
        $('#deleteModal').modal('show');
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