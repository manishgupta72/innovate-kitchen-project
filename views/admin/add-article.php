<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php
require_once 'model/ArticleModel.php';
require_once 'model/MasterDataModel.php';

// Database connection
$database = new Database();
$pdo = $database->connect();

// Instantiate models
$articleModel = new ArticleModel($pdo);
$masterDataModel = new MasterDataModel($pdo);

// Fetch article types from master_data where master_type = 8
$articleTypes = $masterDataModel->getMasterDataByType(8);

$articleId = $_GET['id'] ?? null;
$articleData = $articleId ? $articleModel->getArticleById($articleId) : null;
$isEdit = $articleData !== null;
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>
    <section role="main" class="content-body content-body-modern">
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><?= $isEdit ? 'Edit Article' : 'Add Article'; ?></h2>
                    </header>
                    <div class="card-body">
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<div class='alert alert-{$_SESSION['message_type']}'>{$_SESSION['message']}</div>";
                            unset($_SESSION['message'], $_SESSION['message_type']);
                        }
                        ?>
                        <form action="<?= $isEdit ? 'edit-article?id=' . $articleId : 'add-article'; ?>" method="post"
                            enctype="multipart/form-data" class="form-horizontal form-bordered">
                            <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                            <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $articleId; ?>">
                            <?php endif; ?>

                            <!-- Articles Type Dropdown -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="articles_type">
                                    Articles Type <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-6">
                                    <select class="form-control" id="articles_type" name="articles_type" required>
                                        <option value="">Select Articles Type</option>
                                        <?php foreach ($articleTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type['id']); ?>"
                                            <?= (isset($articleData['articles_type']) && $articleData['articles_type'] == $type['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($type['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Title -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="title">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="<?= htmlspecialchars($articleData['title'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Sub Title -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="sub_title">
                                    Sub Title
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="sub_title" name="sub_title"
                                        value="<?= htmlspecialchars($articleData['sub_title'] ?? ''); ?>">
                                </div>
                            </div>

                            <?php if ($isEdit): ?>
                            <!-- Slug Field (Only visible when editing) -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="slug">
                                    Slug <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="<?= htmlspecialchars($articleData['slug'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Description Editor -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="description">
                                    Description
                                </label>
                                <div class="col-lg-6">
                                    <textarea class="form-control" id="description" name="description"
                                        placeholder="Enter description"><?= htmlspecialchars($articleData['description'] ?? ''); ?></textarea>
                                </div>
                            </div>


                            <!-- Thumb Image (Single Image) -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="thumb_image">
                                    Thumb Image (S)
                                </label>
                                <div class="col-lg-6">
                                    <input type="file" class="form-control" id="thumb_image" name="thumb_image">
                                    <?php if ($isEdit && !empty($articleData['thumb_image'])): ?>
                                    <input type="hidden" name="existing_thumb_image"
                                        value="<?= htmlspecialchars($articleData['thumb_image']); ?>">
                                    <div class="mt-3">
                                        <img src="<?= UPLOAD_ARTICLE . '/thumbs/' . htmlspecialchars($articleData['thumb_image']); ?>"
                                            alt="Thumb Image" width="150">
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Upload Images (Multiple) -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="upload_images">
                                    Upload Images (Multiple)
                                </label>
                                <div class="col-lg-6">
                                    <input type="file" class="form-control" id="upload_images" name="upload_images[]"
                                        multiple>
                                    <?php if ($isEdit && !empty($articleData['upload_images'])): ?>
                                    <?php
                                        $existingFiles = json_decode($articleData['upload_images'], true);
                                        if (!is_array($existingFiles)) {
                                            $existingFiles = [];
                                        }
                                        ?>
                                    <input type="hidden" name="existing_upload_images"
                                        value="<?= htmlspecialchars($articleData['upload_images']); ?>">
                                    <div id="existing-preview" class="mt-3">
                                        <?php foreach ($existingFiles as $file): ?>
                                        <div style="display:inline-block; position:relative; margin-right:10px;">
                                            <img src="<?= UPLOAD_ARTICLE . htmlspecialchars($file); ?>"
                                                alt="Uploaded Image" width="150">
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
                                    <div id="upload-preview" class="mt-3"></div>
                                </div>
                            </div>

                            <!-- Publish Date -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="publish_date">
                                    Publish Date
                                </label>
                                <div class="col-lg-6">
                                    <input type="date" class="form-control" id="publish_date" name="publish_date"
                                        value="<?= htmlspecialchars($articleData['publish_date'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="form-group row">
                                <div class="col-lg-9 offset-lg-3">
                                    <button type="submit"
                                        class="btn btn-primary"><?= $isEdit ? 'Update Article' : 'Add Article'; ?></button>
                                    <a href="manage-article" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
<?php include 'include/footer.php'; ?>
<!-- Initialize Summernote for the description editor -->
<script>
$(document).ready(function() {
    $('#description').summernote({
        height: 150,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>

<!-- JavaScript for multiple images preview (similar to gallery) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('upload_images');
    const previewContainer = document.getElementById('upload-preview');

    let dt = new DataTransfer();

    input.addEventListener('change', function(e) {
        previewContainer.innerHTML = '';
        dt = new DataTransfer();

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            dt.items.add(file);

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
                    let newDt = new DataTransfer();
                    for (let j = 0; j < dt.files.length; j++) {
                        if (j !== i) {
                            newDt.items.add(dt.files[j]);
                        }
                    }
                    dt = newDt;
                    input.files = dt.files;
                    previewDiv.remove();
                });

                previewDiv.appendChild(img);
                previewDiv.appendChild(btn);
                previewContainer.appendChild(previewDiv);
            }
            reader.readAsDataURL(file);
        }
        input.files = dt.files;
    });
});
</script>