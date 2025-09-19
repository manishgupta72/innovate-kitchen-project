<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php include 'include/side-bar.php'; ?>
<?php
require_once 'model/MasterDataModel.php';

$database = new Database();
$pdo = $database->connect();
$masterDataModel = new MasterDataModel($pdo);

$masterData = $masterDataModel->getAllMasterData();
$masterTypes = [
    1 => 'Media Type',
    2 => 'Content Type',
    3 => 'Gallery Type',
    4 => 'Team Type',
    5 => 'Services Type',
    6 => 'Page Type',
    7 => 'Display Type',
    8 => 'Article Type',
    9 => 'Review Type',
    10 => 'Contact Type',

];
?>

<div class="app-container">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">


                        <div class="card">
                            <div class="row m-2">
                                <div class="card">
                                    <header class="card-header">
                                        <h4 class="font-weight-semibold">Master Data Setting</h4>
                                    </header>
                                    <div class="card-body">

                                        <div class="row form-group pb-3">
                                            <form id="masterDataForm" action="master-data-setting" method="post">
                                                <input type="hidden" name="action" value="add" id="formAction">
                                                <input type="hidden" name="id" value="" id="masterDataId">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="name">Name</label>
                                                            <input type="text" class="form-control" id="name" name="name"
                                                                placeholder="Name" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="masterType">Master Type</label>
                                                            <select class="form-control" id="masterType" name="master_type" required>
                                                                <?php foreach ($masterTypes as $key => $value): ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <!-- Optional description input group (commented out) -->
                                                        <!-- <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Description about the master if required"></textarea>
                                        </div> -->
                                                        <div class="mt-5">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-secondary">Reset</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <hr class="my-5">
                                            <div class="card">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped mb-0"
                                                            id="datatable-tabletools">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Master Type</th>
                                                                    <th>MID</th>
                                                                    <th style="width: 100px;">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (is_array($masterData) && !empty($masterData)) { ?>
                                                                    <?php foreach ($masterData as $data) { ?>
                                                                        <tr>
                                                                            <td><?php echo htmlspecialchars($data['name']); ?></td>
                                                                            <td><?php echo htmlspecialchars($masterTypes[$data['master_type']]); ?>
                                                                            </td>
                                                                            <td><?php echo htmlspecialchars($data['master_type']); ?></td>
                                                                            <td>
                                                                                <a href="#" onclick="editMasterData(<?php echo htmlspecialchars(json_encode($data)); ?>)" class="text-success me-2" data-bs-toggle="tooltip" title="Edit">
                                                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                                                </a>
                                                                            </td>

                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="4">No data available.</td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    function editMasterData(data) {
        document.getElementById('masterDataId').value = data.id;
        document.getElementById('name').value = data.name;
        // If you decide to use a description textarea uncomment the next line:
        // document.getElementById('description').value = data.description;
        document.getElementById('masterType').value = data.master_type;
        document.getElementById('formAction').value = 'edit';
    }


    function confirmDeleteMaster(id) {
        if (confirm('Are you sure you want to delete this master data entry?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'master-data-setting';

            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);

            var idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            form.appendChild(idInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php if (isset($_SESSION['message'])): ?>
    <script>
        window.addEventListener("load", function() {
            setTimeout(function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?= $_SESSION['message_type'] === 'success' ? 'success' : 'error' ?>',
                    title: <?= json_encode($_SESSION['message']) ?>,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }, 300);
        });
    </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>