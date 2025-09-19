<?php
require_once 'model/ProjectModel.php';


$projectModel = new ProjectModel($pdo);

// Fetch partner images where Project_type = 16
$projectData  = $projectModel->getProjectByType(29);
?>

<!-- Project Section Start -->
<section class="project-section fix section-padding pt-0">
    <div class="container">
        <div class="section-title text-center">
            <h6 class="wow fadeInUp">Recent Projects</h6>
            <h2 class="splt-txt wow" data-splitting>Elevate Your Home's Design <br> with Interior Mastery</h2>
        </div>
    </div>
    <div class="project-wrapper">
        <div class="swiper project-slider">
            <div class="swiper-wrapper">
                <?php foreach ($projectData as $project): ?>
                    <div class="swiper-slide">
                        <div class="project-carousel-items">
                            <div class="project-image">
                                <img src="<?= UPLOAD_PROJECT . htmlspecialchars($project['upload_file']) ?>" alt="project-img">
                                <div class="project-content">
                                    <h3>
                                        <a href="#"><?= htmlspecialchars($project['description']) ?></a>
                                    </h3>
                                    <p><?= htmlspecialchars($project['title']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="swiper-dot">
                <div class="dot"></div>
            </div>
        </div>
    </div>
</section>