<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>

<?php
require_once 'model/ArtistModel.php';

$database = new Database();
$pdo = $database->connect();
$artistModel = new ArtistModel($pdo);

// Fetch all artists
$artists = $artistModel->getAllArtists();
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>
    <section role="main" class="content-body content-body-modern">
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="card-title">Manage Artists</h2>
                            </div>
                            <div class="col-lg-6">
                                <a href="add-artist" class="pull-right btn btn-primary"><i class="fas fa-plus"></i>
                                    Add New Artist</a>
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
                                        <th>Artist Name</th>
                                        <th>Song IDs</th>
                                        <th>Artist Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($artists as $artist): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($artist['artist_name'] ?? 'null'); ?></td>
                                        <td><?php echo htmlspecialchars($artist['song_names'] ?? 'No Songs'); ?></td>
                                        <td>
                                            <?php if (!empty($artist['artist_image'])): ?>
                                            <img src="<?= UPLOAD_ARTIST . htmlspecialchars($artist['artist_image']); ?>"
                                                alt="Artist Image" style="width: 50px; height: 50px;">
                                            <?php else: ?>
                                            No Image
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit-artist?id=<?php echo $artist['artist_id']; ?>" class=""
                                                data-toggle="tooltip" title="Edit Artist">
                                                <i class="fa-regular fa-pen-to-square"></i>Edit</a>
                                            <a class="font-weight-extra-bold mx-1">|</a>
                                            <a href="#"
                                                onclick="confirmDeleteArtist(<?php echo $artist['artist_id']; ?>)"
                                                data-toggle="tooltip" title="Delete Artist">
                                                <i class="mdi mdi-trash-can-outline"></i>Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div> <!-- End table-responsive -->
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>

<script>
function confirmDeleteArtist(artist_id) {
    $('#deleteModal').modal('show');
    document.getElementById('deleteConfirmBtn').onclick = function() {
        window.location.href = 'manage-artist?action=delete&id=' + artist_id;
    };
}
</script>