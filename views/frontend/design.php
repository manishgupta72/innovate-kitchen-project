<?php include 'include/header.php'; ?>
<?php include 'include/mobile-side-bar.php'; ?>
<?php include 'include/menu.php'; ?>

<?php
require_once 'model/ContentModel.php';
$database = new Database();
$pdo = $database->connect();
$contentModel = new ContentModel($pdo);

// Fetch designs
$designs = $contentModel->getContentByType(28);
?>

<div class="ScrollSmoother-content">
    <section class="portfolio-section fix section-padding">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6 class="wow fadeInUp">Our Kitchen Design</h6>
                <h2 class="splt-txt wow" data-splitting>Types of Kitchen Designs We Deliver</h2>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    At Innovate Kitchen, we specialize in a wide range of modular kitchen designs tailored to fit
                    various spaces and lifestyles. <br>
                    Each design is crafted to maximize efficiency, style, and comfort, ensuring your kitchen becomes a
                    functional and beautiful heart of your home. <br>
                    Here are the popular kitchen design types we offer along with their benefits:
                </p>
            </div>

            <div class="portfolio-wrapper-3 pt-5">
                <div class="row g-4 align-items-center">
                    <?php foreach ($designs as $index => $design): ?>
                        <?php $isImageLeft = $index % 2 !== 0; ?>

                        <?php if ($isImageLeft): ?>
                            <div class="col-lg-6 mt-lg-5">
                                <div class="project-image ms-0 wow fadeInUp" data-wow-delay=".4s">
                                    <img src="<?= UPLOAD_CONTENT . htmlspecialchars($design['upload_image']) ?>" alt="img">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-lg-6 <?= $isImageLeft ? 'mt-lg-0' : 'mt-lg-5' ?>" id="design-<?= $design['id'] ?>">

                            <div class="portfolio-content">
                                <h6 class="wow fadeInUp"><?= htmlspecialchars($design['title']) ?></h6>
                                <?= $design['description'] ?>
                            </div>
                        </div>

                        <?php if (!$isImageLeft): ?>
                            <div class="col-lg-6 mt-lg-5 wow fadeInUp" data-wow-delay=".4s">
                                <div class="project-image">
                                    <img src="<?= UPLOAD_CONTENT . htmlspecialchars($design['upload_image']) ?>" alt="img">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/footer-section.php'; ?>
</div>

<?php include 'include/footer.php'; ?>