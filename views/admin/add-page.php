<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/PageModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();

$pageModel = new PageModel($pdo);
$masterDataModel = new MasterDataModel($pdo);

$pageTypes = $masterDataModel->getMasterDataByType(6);
$pageId = $_GET['id'] ?? null;
$pageData = $pageId ? $pageModel->getPageById($pageId) : null;
$isEdit = $pageData !== null;
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">📄 <?= $isEdit ? 'Edit Page' : 'Add Page'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php if (isset($_SESSION['message'])): ?>
                                    <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                                        <?= $_SESSION['message']; ?>
                                    </div>
                                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                                <?php endif; ?>

                                <form action="<?= $isEdit ? 'edit-page?id=' . $pageId : 'add-page'; ?>" method="post" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $pageId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">📂 Page Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="page_type" required>
                                                <option value="">Select Page Type</option>
                                                <?php foreach ($pageTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>" <?= ($pageData['page_type'] ?? '') == $type['id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($type['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📝 Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($pageData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🧾 Sub Title</label>
                                            <input type="text" class="form-control" name="sub_title" value="<?= htmlspecialchars($pageData['sub_title'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🖋️ Description</label>
                                            <textarea class="form-control" id="summernote" name="description"><?= htmlspecialchars($pageData['description'] ?? ''); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2"><?= $isEdit ? '💾 Update Page' : '➕ Add Page'; ?></button>
                                        <a href="manage-page" class="btn btn-secondary px-4">Cancel</a>
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