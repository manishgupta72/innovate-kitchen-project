<?php include 'include/header.php' ?>
<?php include 'include/mobile-side-bar.php' ?>
<?php include 'include/menu.php' ?>
<?php
require_once 'model/MediaModel.php';


$mediaModel = new MediaModel($pdo);

// Fetch partner images where media_type = 16
$projectData  = $mediaModel->getMediaByType(27);
?>
<div class="ScrollSmoother-content">

    <!-- Portfolio Section Start -->
    <section class="portfolio-section fix section-padding">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6 class="wow fadeInUp">Our Projects </h6>
                <h2 class="splt-txt wow" data-splitting>Experience Luxury with <br> Interior Artistry</h2>
            </div>
        </div>
        <div class="portfolio-wrapper style-2">
            <div class="row g-4">
                <?php foreach ($projectData as $project): ?>
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                        <div class="portfolio-items cursor-pointer">
                            <div class="portfolio-image bg-cover" style="background-image: url('<?= UPLOAD_MEDIA . htmlspecialchars($project['upload_file']) ?>');">
                                <div class="portfolio-content ">

                                    <h3>
                                        <a href="#"><?= htmlspecialchars($project['title']) ?></a>
                                    </h3>
                                    <p>
                                        <?= htmlspecialchars($project['description']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <?php include 'include/footer-section.php' ?>
</div>
<?php include 'include/footer.php' ?>