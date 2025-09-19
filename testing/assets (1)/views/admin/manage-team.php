<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/TeamModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$teamModel = new TeamModel($pdo);

$teamItems = $teamModel->getAllTeam();

$masterDataModel = new MasterDataModel($pdo);
$teamTypesData = $masterDataModel->getMasterDataByType(4); // Team types where master_type = 4
$teamTypesMapping = [];
foreach ($teamTypesData as $type) {
    $teamTypesMapping[$type['id']] = $type['name'];
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
                                <h4 class="card-title mb-0">Manage Team</h4>
                                <a href="add-team" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Item
                                </a>
                            </header>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Team Type</th>
                                                <th>Title</th>
                                                <th>Designation</th>
                                                <th>URL</th>
                                                <th>Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($teamItems) && !empty($teamItems)) { ?>
                                                <?php foreach ($teamItems as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($teamTypesMapping[$item['team_type']] ?? ''); ?>
                                                        </td>

                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td><?php echo htmlspecialchars($item['designation'] ?? ''); ?></td>

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
                                                                <img src="<?php echo UPLOAD_TEAM . htmlspecialchars($item['upload_image']); ?>"
                                                                    alt="Team Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="edit-team?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                                            </a>
                                                            <a href="#" onclick="confirmDeleteTeam(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7">No team members available.</td>
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
    function confirmDeleteTeam(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-team?action=delete&id=' + id;
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