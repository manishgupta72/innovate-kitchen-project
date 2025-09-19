<?php
include 'include/head.php';
include 'include/head-bar.php';
require_once 'model/MediaFileModel.php';
require_once 'model/MasterDataModel.php';
$database = new Database();
$pdo = $database->connect();
$mediaFileModel = new MediaFileModel($pdo);
$masterDataModel = new MasterDataModel($pdo);
$mediaFiles = $mediaFileModel->getAllMediaFiles();
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>

    <section role="main" class="content-body content-body-modern">
        <section class="card">
            <div class="row m-2">
                <section class="card p-0 shadow">
                    <header class="card-header">
                        <h4 class="font-weight-semibold">Manage Media Files</h4>
                    </header>
                    <div class="card-body">
                        <!-- Display Success/Error Messages for General Settings -->
                        <?php
                        if (isset($_SESSION['message'])) {
                            $messageTypeClass = ($_SESSION['message_type'] === 'success') ? 'alert-success' : 'alert-danger';
                            echo "<div class='alert {$messageTypeClass}'>{$_SESSION['message']}</div>";

                            // Clear the message from the session
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        }
                        ?>
                        <!-- Form for Adding/Editing Media Files -->
                        <form action="media-file" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add" id="formAction">
                            <input type="hidden" name="id" value="" id="mediaId">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="mediaName">Media Name</label>
                                        <input type="text" class="form-control" id="mediaName" name="media_name"
                                            placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="mediaType">Media Type</label>
                                        <select class="form-control" id="mediaType" name="media_type" required>
                                                <option value="Banner">Banner</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="mediaFile">Upload Media</label>
                                        <input type="file" class="form-control" id="mediaUpload" name="media_file">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>

                        </form>

                        <hr class="my-5">

                        <!-- Table for Displaying Media Files -->
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0"
                                id="mediaFilesTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>File</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mediaFiles as $file) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($file['media_name']); ?></td>
                                            <td><?php echo htmlspecialchars($file['media_type']); ?></td>

                                            <td><img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($file['media_upload']); ?>"
                                                    height="100" alt="" /></td>
                                            <td>

                                                    <a href="#" onclick="confirmDeleteMedia(<?php echo $file['id']; ?>)"
                                                        class="" data-toggle="tooltip" title="Delete Image"><i
                                                            class="fa-regular fa-trash-can"></i>Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </section>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>
<script>
    function confirmDeleteMedia(id) {
    // Show delete confirmation modal
    $('#deleteModal').modal('show');
    document.getElementById('deleteConfirmBtn').onclick = function() {
        window.location.href = 'media-file?action=delete&id=' + id;
    };
}
</script>