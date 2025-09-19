<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/TeamModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$teamModel = new TeamModel($pdo);
$teamId = $_GET['id'] ?? null;
$teamData = $teamId ? $teamModel->getTeamById($teamId) : null;
$isEdit = $teamData !== null;
$masterDataModel = new MasterDataModel($pdo);
$teamTypes = $masterDataModel->getMasterDataByType(4);
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">👥 <?= $isEdit ? 'Edit Team Member' : 'Add Team Member'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>" . $_SESSION['message'] . "</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-team?id=' . $teamId : 'add-team'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $teamId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">👤 Team Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="team_type" required>
                                                <option value="">Select Team Type</option>
                                                <?php foreach ($teamTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>" <?= (isset($teamData['team_type']) && $teamData['team_type'] == $type['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📝 Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($teamData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">💼 Designation <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="designation" value="<?= htmlspecialchars($teamData['designation'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🔗 URL</label>
                                            <input type="url" class="form-control" name="url" value="<?= htmlspecialchars($teamData['url'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🧾 Description</label>
                                            <textarea class="form-control" name="description" rows="3" placeholder="Enter a description"><?= htmlspecialchars($teamData['description'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">📁 Upload Image</label>
                                            <input type="file" class="form-control" name="upload_image">
                                            <?php if ($isEdit && !empty($teamData['upload_image'])): ?>
                                                <input type="hidden" name="existing_upload_image" value="<?= htmlspecialchars($teamData['upload_image']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_TEAM . htmlspecialchars($teamData['upload_image']); ?>" alt="Team Image" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Team Member' : '➕ Add Team Member'; ?>
                                        </button>
                                        <a href="manage-team" class="btn btn-secondary px-4">Cancel</a>
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