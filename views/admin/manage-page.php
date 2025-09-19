<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/PageModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$pageModel = new PageModel($pdo);

// Fetch all pages
$pages = $pageModel->getAllPages();

// Fetch page types from master_data where master_type = 6
$masterDataModel = new MasterDataModel($pdo);
$pageTypesData = $masterDataModel->getMasterDataByType(6);
$pageTypesMapping = [];
foreach ($pageTypesData as $type) {
    $pageTypesMapping[$type['id']] = $type['name'];
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
                                <h4 class="card-title mb-0"> Manage Page</h4>
                                <a href="add-page" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Page
                                </a>
                            </header>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Page Type</th>
                                                <th>Title</th>
                                                <th>Sub Title</th>
                                                <th>Slug</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($pages) && !empty($pages)) { ?>
                                                <?php foreach ($pages as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($pageTypesMapping[$item['page_type']] ?? ''); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td><?php echo htmlspecialchars($item['sub_title'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($item['slug'] ?? ''); ?></td>

                                                        <td>
                                                            <a href="edit-page?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                                            </a>
                                                            <a href="#" onclick="confirmDeletePage(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="5">No pages available.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div> <!-- End table-responsive -->
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
    function confirmDeletePage(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-page?action=delete&id=' + id;
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