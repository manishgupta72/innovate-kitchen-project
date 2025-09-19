<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ContentModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$contentModel = new ContentModel($pdo);

$contentId = $_GET['id'] ?? null;
$contentData = $contentId ? $contentModel->getContentById($contentId) : null;
$isEdit = $contentData !== null;

$masterDataModel = new MasterDataModel($pdo);
$contentTypes = $masterDataModel->getMasterDataByType(2);
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">📝 <?= $isEdit ? 'Edit Content' : 'Add Content'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>" . $_SESSION['message'] . "</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-content?id=' . $contentId : 'add-content'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $contentId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">📂 Content Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="content_type" required>
                                                <option value="">Select Content Type</option>
                                                <?php foreach ($contentTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>" <?= (isset($contentData['content_type']) && $contentData['content_type'] == $type['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📝 Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($contentData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🧾 Description</label>
                                            <textarea class="form-control" id="summernote" name="description"><?= htmlspecialchars($contentData['description'] ?? ''); ?></textarea>
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label">🔗 URL</label>
                                            <input type="url" class="form-control" name="url" value="<?= htmlspecialchars($contentData['url'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📅 Date</label>
                                            <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($contentData['date'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📁 Upload Image</label>
                                            <input type="file" class="form-control" name="upload_image" id="upload_image">

                                            <?php if ($isEdit && !empty($contentData['upload_image'])): ?>
                                                <input type="hidden" name="existing_upload_image" value="<?= htmlspecialchars($contentData['upload_image']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_CONTENT . htmlspecialchars($contentData['upload_image']); ?>" alt="Uploaded Image" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">📁 Upload Image 2</label>
                                            <input type="file" class="form-control" name="upload_image2" id="upload_image">

                                            <?php if ($isEdit && !empty($contentData['upload_image2'])): ?>
                                                <input type="hidden" name="existing_upload_image2" value="<?= htmlspecialchars($contentData['upload_image2']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_CONTENT . htmlspecialchars($contentData['upload_image2']); ?>" alt="Uploaded Image2" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Content' : '➕ Add Content'; ?>
                                        </button>
                                        <a href="manage-content" class="btn btn-secondary px-4">Cancel</a>
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