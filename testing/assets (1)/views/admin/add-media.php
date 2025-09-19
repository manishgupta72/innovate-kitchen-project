<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/MediaModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();

$mediaModel = new MediaModel($pdo);
$mediaId = $_GET['id'] ?? null;
$mediaData = $mediaId ? $mediaModel->getMediaById($mediaId) : null;
$isEdit = $mediaData !== null;

$masterDataModel = new MasterDataModel($pdo);
$mediaTypes = $masterDataModel->getMasterDataByType(1);
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">🖼️ <?= $isEdit ? 'Edit Media Item' : 'Add Media Item'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>" . $_SESSION['message'] . "</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-media?id=' . $mediaId : 'add-media'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $mediaId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">📂 Media Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="media_type" required>
                                                <option value="">Select Media Type</option>
                                                <?php foreach ($mediaTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>" <?= (isset($mediaData['media_type']) && $mediaData['media_type'] == $type['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📝 Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($mediaData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🧾 Description</label>
                                            <textarea class="form-control" name="description" rows="3" placeholder="Enter a description"><?= htmlspecialchars($mediaData['description'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🔗 URL</label>
                                            <input type="url" class="form-control" name="url" value="<?= htmlspecialchars($mediaData['url'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📅 Date</label>
                                            <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($mediaData['date'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">📁 Upload File(s)</label>
                                            <div class="dropzone rounded bg-white border border-dashed p-4 text-center" id="uploadZone">
                                                <div class="dz-message">
                                                    <span class="dz-button">Click or drag files here to upload</span>
                                                </div>
                                                <input type="file" name="upload_file" id="uploadFile" style="display: none;">
                                            </div>

                                            <?php if ($isEdit && !empty($mediaData['upload_file'])): ?>
                                                <input type="hidden" name="existing_upload_file" value="<?= htmlspecialchars($mediaData['upload_file']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_MEDIA . htmlspecialchars($mediaData['upload_file']); ?>" alt="Uploaded File" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Media Item' : '➕ Add Media Item'; ?>
                                        </button>
                                        <a href="manage-media" class="btn btn-secondary px-4">Cancel</a>
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