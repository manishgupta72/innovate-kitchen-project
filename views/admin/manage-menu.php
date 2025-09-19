<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>

<?php
require_once 'model/MenuModel.php'; // Adjust with the correct path
$database = new Database();
$pdo = $database->connect();
$menuModel = new MenuModel($pdo);

// Fetch menu data
$menus = $menuModel->getAllMenus(); // This method should return menus with category names instead of IDs

// Include the view file
//include 'views/admin/manage_menus.php';
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>

    <section role="main" class="content-body content-body-modern">
        <section class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                    <h4 class="font-weight-semibold">Manage Menus</h4>
                    </div>
                    <div class="col-lg-6">
                    <a href="add-menu" class="pull-right btn btn-primary"><i class="fa-solid fa-plus"></i> Add Menu</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Display any session messages -->
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert <?php echo $_SESSION['message_type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo $_SESSION['message']; ?>
                        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="menusDataTable">
                        <thead>
                            <tr>
                                <th>Menu Name</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Sub-Sub-Category</th>
                                <th>Product/Page/Link</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($menu['menu_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['sub_category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['sub_sub_category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['product_page_link_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['menu_order_by']); ?></td>
                                    <td>
                                        <a href="edit-menu?id=<?php echo $menu['menu_id']; ?>" class="edit-link" data-toggle="tooltip" title="Edit Menu">Edit</a>
                                        <a href="#" onclick="confirmDelete(<?php echo $menu['menu_id']; ?>)" class="delete-link" data-toggle="tooltip" title="Delete Menu">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </section>
</div>

<?php include 'include/delete-modal.php'; ?>
<?php include 'include/footer.php'; ?>
