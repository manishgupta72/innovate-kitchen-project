<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ProjectModel.php';
$database = new Database();
$pdo = $database->connect();
$projectModel = new ProjectModel($pdo);

// Fetch all projects
$projects = $projectModel->getAllProjects();
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">📋 Manage Projects</h4>
                    <a href="add-project" class="btn btn-primary">➕ Add Project</a>
                </div>



                <div class="card shadow-sm border-0">
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>📌 Title</th>
                                    <th>📝 Description</th>
                                    <th>📁 File</th>
                                    <th>🔗 URL</th>
                                    <th>📅 Date</th>
                                    <th>⚙️ Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($projects)): ?>
                                    <?php foreach ($projects as $index => $project): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($project['title']) ?></td>
                                            <td><?= strip_tags(substr($project['description'], 0, 100)) ?><?= strlen($project['description']) > 100 ? '...' : '' ?></td>
                                            <td>
                                                <?php if (!empty($project['upload_file'])): ?>
                                                    <img src="<?= UPLOAD_PROJECT . htmlspecialchars($project['upload_file']) ?>" alt="Project" width="80" class="img-thumbnail">
                                                <?php else: ?>
                                                    <span class="text-muted">No file</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($project['url'])): ?>
                                                    <a href="<?= htmlspecialchars($project['url']) ?>" target="_blank">Visit</a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($project['date'] ?? '-') ?></td>

                                            <td>
                                                <a href="edit-project?id=<?= $project['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-pen-to-square fa-lg"></i>
                                                </a>
                                                <a href="#" onclick="confirmDeleteProjectItem(<?= $project['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No project records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>
<script>
    function confirmDeleteProjectItem(id) {
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-project?action=delete&id=' + id;
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