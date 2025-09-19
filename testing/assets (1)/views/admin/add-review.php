<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ReviewModel.php';
require_once 'model/MasterDataModel.php';

// Database connection
$database = new Database();
$pdo = $database->connect();

// Instantiate models
$reviewModel = new ReviewModel($pdo);
$masterDataModel = new MasterDataModel($pdo);

// Fetch review types from master_data where master_type = 9
$reviewTypes = $masterDataModel->getMasterDataByType(9);

$reviewId = $_GET['id'] ?? null;
$reviewData = $reviewId ? $reviewModel->getReviewById($reviewId) : null;
$isEdit = $reviewData !== null;
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">📝 <?= $isEdit ? 'Edit Review' : 'Add Review'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>" . $_SESSION['message'] . "</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-review?id=' . $reviewId : 'add-review'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $reviewId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">🔖 Review Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="review_type" required>
                                                <option value="">Select Review Type</option>
                                                <?php foreach ($reviewTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>" <?= (isset($reviewData['review_type']) && $reviewData['review_type'] == $type['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🏷️ Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($reviewData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">📝 Description</label>
                                            <textarea class="form-control" name="description" id="summernote" rows="4"><?= htmlspecialchars($reviewData['description'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">👤 Author Name</label>
                                            <input type="text" class="form-control" name="author_name" value="<?= htmlspecialchars($reviewData['author_name'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">⭐ Ratings</label>
                                            <input type="number" step="0.01" min="1" max="5" class="form-control" name="ratings" value="<?= htmlspecialchars($reviewData['ratings'] ?? ''); ?>" required>
                                            <small class="text-danger">Rating between 1 - 5 (decimals allowed)</small>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🖼️ Author Image</label>
                                            <input type="file" class="form-control" name="author_image" id="author_image">
                                            <?php if ($isEdit && !empty($reviewData['author_image'])): ?>
                                                <input type="hidden" name="existing_author_image" value="<?= htmlspecialchars($reviewData['author_image']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_REVIEW . htmlspecialchars($reviewData['author_image']); ?>" alt="Author Image" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📅 Publish Date</label>
                                            <input type="date" class="form-control" name="publish_date" value="<?= htmlspecialchars($reviewData['publish_date'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Review' : '➕ Add Review'; ?>
                                        </button>
                                        <a href="manage-review" class="btn btn-secondary px-4">Cancel</a>
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