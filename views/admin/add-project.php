<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ProjectModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();

$projectModel = new ProjectModel($pdo);
$masterDataModel = new MasterDataModel($pdo);

$projectId = $_GET['id'] ?? null;
$projectData = $projectId ? $projectModel->getProjectById($projectId) : null;
$isEdit = $projectData !== null;

$projectTypes = $masterDataModel->getMasterDataByType(11);
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header bg-light d-flex justify-content-between">
                                <h4 class="mb-0"><?= $isEdit ? '🛠️ Edit Project' : '➕ Add Project' ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php if (isset($_SESSION['message'])): ?>
                                    <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                                        <?= $_SESSION['message'] ?>
                                    </div>
                                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                                <?php endif; ?>
                                <form action="<?= $isEdit ? 'edit-project?id=' . $projectId : 'add-project'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $projectId; ?>">
                                    <?php endif; ?>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">🏷️ Project Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="project_type" required>
                                                <option value="">Select Project Type</option>
                                                <?php foreach ($projectTypes as $type): ?>
                                                    <option value="<?= $type['id'] ?>" <?= $type['id'] == ($projectData['project_type'] ?? '') ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($type['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">📌 Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($projectData['title'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">📝 Description</label>
                                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($projectData['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">🔗 URL</label>
                                            <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($projectData['url'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">📅 Date</label>
                                            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($projectData['date'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">📁 Upload File</label>
                                            <input type="file" class="form-control" name="upload_file">
                                            <?php if ($isEdit && !empty($projectData['upload_file'])): ?>
                                                <input type="hidden" name="existing_upload_file" value="<?= htmlspecialchars($projectData['upload_file']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_PROJECT . htmlspecialchars($projectData['upload_file']); ?>" alt="Uploaded File" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Project' : '➕ Add Project' ?>
                                        </button>
                                        <a href="manage-project" class="btn btn-secondary px-4">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>