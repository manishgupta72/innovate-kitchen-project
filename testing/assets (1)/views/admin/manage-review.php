<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ReviewModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$reviewModel = new ReviewModel($pdo);

$reviews = $reviewModel->getAllReviews();

// Fetch review types from master_data where master_type = 9
$masterDataModel = new MasterDataModel($pdo);
$reviewTypesData = $masterDataModel->getMasterDataByType(9);
$reviewTypesMapping = [];
foreach ($reviewTypesData as $type) {
    $reviewTypesMapping[$type['id']] = $type['name'];
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
                                <h4 class="card-title mb-0"> Manage Review</h4>
                                <a href="add-review" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Review
                                </a>
                            </header>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table display nowrap table-bordered table-hover w-100" id="datatable1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Review Type</th>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>Publish Date</th>
                                                <th>Author Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($reviews) && !empty($reviews)) { ?>
                                                <?php foreach ($reviews as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($reviewTypesMapping[$item['review_type']] ?? ''); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td><?php echo htmlspecialchars($item['author_name'] ?? ''); ?></td>

                                                        <td><?php echo htmlspecialchars($item['publish_date'] ?? ''); ?></td>
                                                        <td>
                                                            <?php if (!empty($item['author_image'])): ?>
                                                                <img src="<?php echo UPLOAD_REVIEW . htmlspecialchars($item['author_image']); ?>"
                                                                    alt="Author Image"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <a href="edit-review?id=<?= $item['id'] ?>" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                                            </a>
                                                            <a href="#" onclick="confirmDeleteMediaItem(<?= $item['id'] ?>)" class="text-danger" data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7">No reviews available.</td>
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
    function confirmDeleteReview(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-review?action=delete&id=' + id;
        };
    }
</script>