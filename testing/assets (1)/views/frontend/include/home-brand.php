<?php
require_once 'model/MediaModel.php';


$mediaModel = new MediaModel($pdo);

// Fetch partner images where media_type = 16
$partnerLogos = $mediaModel->getMediaByType(16);
?>
<!-- Brand Section Start -->
<div class="brand-section section-padding section-bg-2 fix">
    <div class="container">
        <div class="brand-wrapper">
            <div class="swiper brand-slider">
                <div class="swiper-wrapper">
                    <?php
                    foreach ($partnerLogos as $logo) { ?>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="<?= UPLOAD_MEDIA . htmlspecialchars($logo['upload_file']) ?>" alt="<?= htmlspecialchars($logo['title']) ?>">
                            </div>
                        </div>
                    <?php }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>