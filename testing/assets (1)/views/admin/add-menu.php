<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php
require_once 'model/MenuModel.php';
require_once 'model/CategoryModel.php';
require_once 'model/ProSerModel.php';
require_once 'model/PagesModel.php';

$database = new Database();
$pdo = $database->connect();

$categoryModel = new CategoryModel($pdo);
$productModel = new ProSerModel($pdo);
$pageModel = new PagesModel($pdo);

$categories = $categoryModel->getAllCategories(); // Fetch all categories
$products = $productModel->getAllProSer(); // Fetch all products
$pages = $pageModel->getAllPages(); // Fetch all pages

$menuId = $_GET['id'] ?? null;
$menuData = null;
if ($menuId) {
    $menuModel = new MenuModel($pdo);
    $menuData = $menuModel->getMenuItemById($menuId); // Fetch menu data for editing
}
?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>

    <section role="main" class="content-body content-body-modern">
        <section class="card">
            <div class="row m-2">
                <section class="card p-0 shadow">
                    <header class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="font-weight-semibold"><?php echo $menuId ? 'Edit' : 'Add'; ?> Menu Item</h4>
                            </div>
                            <div class="col-lg-6">
                                <a href="manage-menu" class="pull-right btn btn-primary">Manage Menu</a>
                            </div>
                        </div>
                    </header>
                    <div class="card-body">
                        <!-- Display Success/Error Messages -->
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<div class='alert {$_SESSION['message_type']}'>{$_SESSION['message']}</div>";
                            unset($_SESSION['message'], $_SESSION['message_type']);
                        }
                        ?>
                        <form action="add-menu" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="<?php echo $menuId ? 'update' : 'add'; ?>">
                            <input type="hidden" name="id" value="<?php echo $menuId ?? ''; ?>">

                            <div class="row">
                                <!-- Menu Type Dropdown -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="menuType">Menu Type</label>
                                        <select class="form-control" id="menuType" name="menu_type" required>
                                            <option value="">Select Menu Type</option>
                                            <option value="Product/Services">Product/Services</option>
                                            <option value="Page">Page</option>
                                            <option value="Link">Link</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Menu Name -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="menuName">Menu Name</label>
                                        <input type="text" class="form-control" id="menuName" name="menu_name" value="<?php echo htmlspecialchars($menuData['menu_name'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <!-- Menu Order By-->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="menuOrderBy">Menu Display Order</label>
                                        <input type="number" class="form-control" id="menuOrderBy" name="menu_order_by" value="<?php echo htmlspecialchars($menuData['menu_order_by'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="row category-rows">
                                    <!-- Menu Category Dropdown -->
                                    <div class="col-lg-4 mt-3">
                                        <div class="form-group">
                                            <label for="menuCat">Menu Category</label>
                                            <select class="form-control" id="menuCat" name="menu_cat">
                                                <?php foreach ($categories as $category) : ?>
                                                    <?php if ($category['Cat_Type'] == 'Category') : ?>
                                                        <option value="<?php echo $category['Cat_id']; ?>" <?php if (isset($menuData['menu_cat']) && $menuData['menu_cat'] == $category['Cat_id']) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($category['Cat_name']); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Menu Sub-Category Dropdown -->
                                    <div class="col-lg-4 mt-3">
                                        <div class="form-group">
                                            <label for="menuSubCat">Menu Sub-Category</label>
                                            <select class="form-control" id="menuSubCat" name="menu_sub_cat">
                                                <?php foreach ($categories as $category) : ?>
                                                    <?php if ($category['Cat_Type'] == 'Sub-Category') : ?>
                                                        <option value="<?php echo $category['Cat_id']; ?>" <?php if (isset($menuData['menu_sub_cat']) && $menuData['menu_sub_cat'] == $category['Cat_id']) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($category['Cat_name']); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Menu Sub-Sub-Category Dropdown -->
                                    <div class="col-lg-4 mt-3">
                                        <div class="form-group">
                                            <label for="menuSubSubCat">Menu Sub-Sub-Category</label>
                                            <select class="form-control" id="menuSubSubCat" name="menu_sub_sub_cat">
                                                <?php foreach ($categories as $category) : ?>
                                                    <?php if ($category['Cat_Type'] == 'Sub-Sub-Category') : ?>
                                                        <option value="<?php echo $category['Cat_id']; ?>" <?php if (isset($menuData['menu_sub_sub_cat']) && $menuData['menu_sub_sub_cat'] == $category['Cat_id']) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($category['Cat_name']); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Menu Product Dropdown -->
                                    <div class="col-lg-6 mt-3">
                                        <div class="form-group">
                                            <label for="menuProduct">Menu Product / Services</label>
                                            <select class="form-control" id="menuProduct" name="menu_product">
                                                <option value="">Select Product / Services</option>
                                                <?php foreach ($products as $product) : ?>
                                                    <option value="<?php echo $product['ps_id']; ?>" <?php if (isset($menuData['ps_name']) && $menuData['ps_name'] == $product['ps_id']) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($product['ps_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Page Dropdown -->
                                <div class="col-lg-6 mt-3 page-row">
                                    <div class="form-group">
                                        <label for="menuPage">Menu Page</label>
                                        <select class="form-control" id="menuPage" name="menu_page">
                                            <option value="">Select Page</option>
                                            <?php foreach ($pages as $page) : ?>
                                                <option value="<?php echo $page['page_id']; ?>" <?php if (isset($menuData['menu_page']) && $menuData['menu_page'] == $page['page_id']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($page['page_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Menu Link -->
                                <div class="col-lg-6 mt-3 link-row">
                                    <div class="form-group">
                                        <label for="menuLink">Menu Link</label>
                                        <input type="text" class="form-control" id="menuLink" name="menu_link" value="<?php echo htmlspecialchars($menuData['menu_link'] ?? ''); ?>">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-lg-12 text-right mt-3">
                                    <button type="submit" class="btn btn-primary"><?php echo $menuId ? 'Update' : 'Add'; ?> Menu Item</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </section>
    </section>
</div>

<?php include 'include/footer.php'; ?>