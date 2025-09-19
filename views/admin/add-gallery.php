<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/GalleryModel.php';
require_once 'model/MasterDataModel.php';

// Database connection
$database = new Database();
$pdo = $database->connect();

// Instantiate models
$galleryModel = new Gallery($pdo);
$masterDataModel = new MasterDataModel($pdo);

// Fetch gallery types from master_data where master_type = 3
$galleryTypes = $masterDataModel->getMasterDataByType(3);

$galleryId = $_GET['id'] ?? null;
$galleryData = $galleryId ? $galleryModel->getGalleryItemById($galleryId) : null;
$isEdit = $galleryData !== null;
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">

                            <header class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light">
                                <h4 class="mb-0">📝 <?= $isEdit ? 'Edit Gallery' : 'Add Gallery'; ?></h4>
                            </header>
                            <div class="card-body">
                                <?php
                                // Display messages if any
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>{$_SESSION['message']}</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-gallery?id=' . $galleryId : 'add-gallery'; ?>" method="post"
                                    class="form-horizontal form-bordered" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $galleryId; ?>">
                                    <?php endif; ?>

                                    <!-- Gallery Type Dropdown -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2" for="gallery_type">
                                            Gallery Type <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <select class="form-control" id="gallery_type" name="gallery_type" required>
                                                <option value="">Select Gallery Type</option>
                                                <?php foreach ($galleryTypes as $type): ?>
                                                    <option value="<?= htmlspecialchars($type['id']); ?>"
                                                        <?= (isset($galleryData['gallery_type']) && $galleryData['gallery_type'] == $type['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($type['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2" for="title">Title <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="<?= htmlspecialchars($galleryData['title'] ?? ''); ?>" required>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2"
                                            for="description">Description</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Enter description"><?= htmlspecialchars($galleryData['description'] ?? ''); ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Upload File (Multiple Images) -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2" for="upload_file">Upload
                                            File(Multiple)</label>
                                        <div class="col-lg-6">
                                            <input type="file" class="form-control" id="upload_file" name="upload_file[]"
                                                multiple>
                                            <?php if ($isEdit && !empty($galleryData['upload_file'])): ?>
                                                <?php
                                                $existingFiles = json_decode($galleryData['upload_file'], true);
                                                if (!is_array($existingFiles)) {
                                                    $existingFiles = [];
                                                }
                                                ?>
                                                <input type="hidden" name="existing_upload_file"
                                                    value="<?= htmlspecialchars($galleryData['upload_file']); ?>">
                                                <div id="existing-preview" class="mt-3">
                                                    <?php foreach ($existingFiles as $file): ?>
                                                        <div style="display:inline-block; position:relative; margin-right:10px;">
                                                            <img src="<?= UPLOAD_GALLERY . htmlspecialchars($file); ?>"
                                                                alt="Uploaded File" width="150">
                                                            <div>
                                                                <label>
                                                                    <input type="checkbox" name="delete_existing_files[]"
                                                                        value="<?= htmlspecialchars($file); ?>"> Delete
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <!-- Container for new file previews -->
                                            <div id="upload-preview" class="mt-3"></div>
                                        </div>
                                    </div>


                                    <!-- Thumb Image (Single Image) -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2" for="thumb_image">Thumbnail
                                            Image</label>
                                        <div class="col-lg-6">
                                            <input type="file" class="form-control" id="thumb_image" name="thumb_image">
                                            <?php if ($isEdit && !empty($galleryData['thumb_image'])): ?>
                                                <input type="hidden" name="existing_thumb_image"
                                                    value="<?= htmlspecialchars($galleryData['thumb_image']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_GALLERY . 'thumbs/' . htmlspecialchars($galleryData['thumb_image']); ?>"
                                                        alt="Thumbnail Image" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- YouTube URL -->
                                    <div class="form-group row pb-4">
                                        <label class="col-lg-3 control-label text-lg-end pt-2" for="youtube_url">YouTube
                                            URL</label>
                                        <div class="col-lg-6">
                                            <input type="url" class="form-control" id="youtube_url" name="youtube_url"
                                                value="<?= htmlspecialchars($galleryData['youtube_url'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="form-group row">
                                        <div class="col-lg-9 offset-lg-3">
                                            <button type="submit"
                                                class="btn btn-primary"><?= $isEdit ? 'Update Gallery Item' : 'Add Gallery Item'; ?></button>
                                            <a href="manage-gallery" class="btn btn-default">Cancel</a>
                                        </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('upload_file');
        const previewContainer = document.getElementById('upload-preview');

        // Create a DataTransfer object to hold the selected files
        let dt = new DataTransfer();

        input.addEventListener('change', function(e) {
            // Clear preview container and reset DataTransfer
            previewContainer.innerHTML = '';
            dt = new DataTransfer();

            // Loop over each selected file
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                dt.items.add(file);

                // Create FileReader for preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.style.display = 'inline-block';
                    previewDiv.style.position = 'relative';
                    previewDiv.style.marginRight = '10px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';

                    // Create a delete button
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.innerText = 'X';
                    btn.style.position = 'absolute';
                    btn.style.top = '0';
                    btn.style.right = '0';
                    btn.style.background = 'red';
                    btn.style.color = 'white';
                    btn.style.border = 'none';
                    btn.style.cursor = 'pointer';
                    btn.addEventListener('click', function() {
                        // Remove the file from DataTransfer object:
                        let newDt = new DataTransfer();
                        for (let j = 0; j < dt.files.length; j++) {
                            if (j !== i) {
                                newDt.items.add(dt.files[j]);
                            }
                        }
                        dt = newDt;
                        input.files = dt.files;
                        // Remove the preview element
                        previewDiv.remove();
                    });

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(btn);
                    previewContainer.appendChild(previewDiv);
                }
                reader.readAsDataURL(file);
            }
            // Update the file input files with the new DataTransfer files
            input.files = dt.files;
        });
    });
</script>