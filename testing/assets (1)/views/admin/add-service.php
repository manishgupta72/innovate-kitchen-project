<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/ServiceModel.php';
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();

$serviceModel = new ServiceModel($pdo);
$masterDataModel = new MasterDataModel($pdo);

$servicesTypes = $masterDataModel->getMasterDataByType(5);
$displayTypes = $masterDataModel->getMasterDataByType(7);

$serviceId = $_GET['id'] ?? null;
$serviceData = $serviceId ? $serviceModel->getServiceById($serviceId) : null;
$isEdit = $serviceData !== null;
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <section class="card shadow-sm border-0">
                            <header class="card-header d-flex justify-content-between align-items-center bg-light">
                                <h4 class="mb-0"><?= $isEdit ? '🛠️ Edit Service' : '➕ Add Service'; ?></h4>
                            </header>
                            <div class="card-body p-4 bg-light">
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo "<div class='alert alert-{$_SESSION['message_type']}'>" . $_SESSION['message'] . "</div>";
                                    unset($_SESSION['message'], $_SESSION['message_type']);
                                }
                                ?>
                                <form action="<?= $isEdit ? 'edit-service?id=' . $serviceId : 'add-service'; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="id" value="<?= $serviceId; ?>">
                                    <?php endif; ?>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">🧩 Services Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="services_type" required>
                                                <option value="">Select Services Type</option>
                                                <?php foreach ($servicesTypes as $type): ?>
                                                    <option value="<?= $type['id']; ?>" <?= ($serviceData['services_type'] ?? '') == $type['id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($type['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📝 Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($serviceData['title'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🆔 Sub Title</label>
                                            <input type="text" class="form-control" name="sub_title" value="<?= htmlspecialchars($serviceData['sub_title'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">🧾 Display Type</label>
                                            <select class="form-select" name="display_type">
                                                <option value="">Select Display Type</option>
                                                <?php foreach ($displayTypes as $type): ?>
                                                    <option value="<?= $type['id']; ?>" <?= ($serviceData['display_type'] ?? '') == $type['id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($type['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <?php if ($isEdit): ?>
                                            <div class="col-md-6">
                                                <label class="form-label">🔗 Slug <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="slug" value="<?= htmlspecialchars($serviceData['slug']); ?>" required>
                                            </div>
                                        <?php endif; ?>

                                        <div class="col-md-6">
                                            <label class="form-label">🔗 URL</label>
                                            <input type="url" class="form-control" name="url" value="<?= htmlspecialchars($serviceData['url'] ?? ''); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">📊 Display Order</label>
                                            <input type="number" class="form-control" name="display_order" value="<?= htmlspecialchars($serviceData['display_order'] ?? 0); ?>">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">📝 Description</label>
                                            <textarea class="form-control" name="description" id="summernote"><?= htmlspecialchars($serviceData['description'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">🖼️ Thumb Image</label>
                                            <input type="file" class="form-control" name="thumb_image" id="thumbImage">

                                            <?php if ($isEdit && !empty($serviceData['thumb_image'])): ?>
                                                <input type="hidden" name="existing_thumb_image" value="<?= htmlspecialchars($serviceData['thumb_image']); ?>">
                                                <div class="mt-3">
                                                    <img src="<?= UPLOAD_SERVICE . htmlspecialchars($serviceData['thumb_image']); ?>" alt="Thumb" class="img-thumbnail" width="150">
                                                </div>
                                            <?php endif; ?>
                                        </div>


                                        <div class="col-md-12">
                                            <label class="form-label">💬 Add Q&A</label>
                                            <div id="qa-container">
                                                <?php if ($isEdit && !empty($serviceData['qa_data'])):
                                                    $qaData = json_decode($serviceData['qa_data'], true);
                                                    foreach ($qaData as $index => $qa): ?>
                                                        <div class="qa-item mb-3 border p-3 bg-white">
                                                            <input type="text" name="qa[<?= $index ?>][question]" class="form-control mb-2" placeholder="Enter Question" value="<?= htmlspecialchars($qa['question']) ?>" required>
                                                            <input type="text" name="qa[<?= $index ?>][answer]" class="form-control" placeholder="Enter Answer" value="<?= htmlspecialchars($qa['answer']) ?>" required>
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 removeQA">Remove</button>
                                                        </div>
                                                <?php endforeach;
                                                endif; ?>
                                            </div>
                                            <button type="button" id="addQA" class="btn btn-outline-primary mt-2">➕ Add QA</button>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-success px-4 me-2">
                                            <?= $isEdit ? '💾 Update Service' : '➕ Add Service'; ?>
                                        </button>
                                        <a href="manage-service" class="btn btn-secondary px-4">Cancel</a>
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
    document.getElementById('addQA').addEventListener('click', function() {
        let container = document.getElementById('qa-container');
        let index = container.getElementsByClassName('qa-item').length;

        let div = document.createElement('div');
        div.classList.add('qa-item', 'mb-3', 'border', 'p-3', 'bg-white');

        div.innerHTML = `
            <input type="text" name="qa[${index}][question]" class="form-control mb-2" placeholder="Enter Question" required>
            <input type="text" name="qa[${index}][answer]" class="form-control" placeholder="Enter Answer" required>
            <button type="button" class="btn btn-sm btn-danger mt-2 removeQA">Remove</button>
        `;
        container.appendChild(div);
        div.querySelector('.removeQA').addEventListener('click', function() {
            div.remove();
        });
    });

    document.querySelectorAll('.removeQA').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
</script>