<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ServiceModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$serviceModel = new ServiceModel($pdo);

// Fetch all services
$services = $serviceModel->getAllServices();

// Fetch service types from master_data where master_type = 5
$masterDataModel = new MasterDataModel($pdo);
$serviceTypesData = $masterDataModel->getMasterDataByType(5);
$serviceTypesMapping = [];
foreach ($serviceTypesData as $type) {
    $serviceTypesMapping[$type['id']] = $type['name'];
}
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="card shadow-sm border-0">
                            <section class="card">

                                <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                    <h4 class="card-title mb-0">Manage Services</h4>
                                    <a href="add-service" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add New Service
                                    </a>
                                </header>
                                <div class="card-body">


                                    <div class="table-responsive">
                                        <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Services Type</th>
                                                    <th>Title</th>
                                                    <th>Slug</th>
                                                    <th>Display Order</th>
                                                    <th>Thumb Image</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (is_array($services) && !empty($services)) { ?>
                                                    <?php foreach ($services as $item) { ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($serviceTypesMapping[$item['services_type']] ?? ''); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>

                                                            <td><?php echo htmlspecialchars($item['slug'] ?? ''); ?></td>

                                                            <td><?php echo htmlspecialchars($item['display_order'] ?? ''); ?></td>
                                                            <!-- <td><?php echo ($item['display'] == 1) ? 'Yes' : 'No'; ?></td> -->
                                                            <td>
                                                                <?php if (!empty($item['thumb_image'])): ?>
                                                                    <img src="<?php echo UPLOAD_SERVICE . htmlspecialchars($item['thumb_image']); ?>"
                                                                        alt="Thumb Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                                <?php else: ?>
                                                                    No Image
                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <a href="edit-service?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="fas fa-pen-to-square fa-lg"></i>
                                                                </a>
                                                                <a href="#" onclick="confirmDeleteService(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                                    <i class="fas fa-trash fa-lg"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="9">No services available.</td>
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
    function confirmDeleteService(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-service?action=delete&id=' + id;
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