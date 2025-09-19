<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>

<?php
require_once 'model/ArticleModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$articleModel = new ArticleModel($pdo);

$articles = $articleModel->getAllArticles();

// Fetch article types from master_data where master_type = 7
$masterDataModel = new MasterDataModel($pdo);
$articleTypesData = $masterDataModel->getMasterDataByType(8);
$articleTypesMapping = [];
foreach ($articleTypesData as $type) {
    $articleTypesMapping[$type['id']] = $type['name'];
}
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="card">
                            <header class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h2 class="card-title">Manage Articles</h2>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="add-article" class="pull-right btn btn-primary"><i class="fas fa-plus"></i> Add
                                            New Article</a>
                                    </div>
                                </div>
                            </header>
                            <div class="card-body">
                                <?php if (isset($_SESSION['message'])) : ?>
                                    <div id="alert-message"
                                        class="alert alert-<?= $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show"
                                        role="alert">
                                        <?= $_SESSION['message']; ?>
                                    </div>
                                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                                <?php endif; ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                                        <thead>
                                            <tr>
                                                <th>Articles Type</th>
                                                <th>Title</th>
                                                <th>Publish Date</th>
                                                <th>Thumb Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($articles) && !empty($articles)) { ?>
                                                <?php foreach ($articles as $item) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($articleTypesMapping[$item['articles_type']] ?? ''); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></td>
                                                        <td><?php echo htmlspecialchars($item['publish_date'] ?? ''); ?></td>
                                                        <td>
                                                            <?php if (!empty($item['thumb_image'])): ?>
                                                                <img src="<?php echo UPLOAD_ARTICLE . 'thumbs/' . htmlspecialchars($item['thumb_image']); ?>"
                                                                    alt="Thumb Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="edit-article?id=<?php echo $item['id']; ?>" data-toggle="tooltip"
                                                                title="Edit Article">
                                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                                            </a>
                                                            <span class="mx-1">|</span>
                                                            <a href="#" onclick="confirmDeleteArticle(<?php echo $item['id']; ?>)"
                                                                data-toggle="tooltip" title="Delete Article">
                                                                <i class="mdi mdi-trash-can-outline"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7">No articles available.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div> <!-- End table-responsive -->
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>

<script>
    function confirmDeleteArticle(id) {
        $('#deleteModal').modal('show');
        document.getElementById('deleteConfirmBtn').onclick = function() {
            window.location.href = 'manage-article?action=delete&id=' + id;
        };
    }
</script>