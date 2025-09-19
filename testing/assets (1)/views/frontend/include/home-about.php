<?php
require_once 'model/MediaModel.php';

$mediaModel = new MediaModel($pdo);

// Fetch the single image where media_type = 3
$aboutImage1 = $mediaModel->getMediaByType(18);
$aboutImage2 = $mediaModel->getMediaByType(26);
$aboutImage1Url1 = !empty($aboutImage1) ? UPLOAD_MEDIA . htmlspecialchars($aboutImage1[0]['upload_file']) : 'https://placehold.co/350x226';
$aboutTitle = !empty($aboutImage1) ?  htmlspecialchars($aboutImage1[0]['title']) : '25';
$aboutImage1Url2 = !empty($aboutImage2) ? UPLOAD_MEDIA . htmlspecialchars($aboutImage2[0]['upload_file']) : 'https://placehold.co/479x300';
?>


<!-- About Section Start -->
<section class="about-section fix section-padding pt-0">
    <div class="container">
        <div class="about-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-left-items">
                        <div class="dot-shape float-bob-y">
                            <img src="<?= FRONTEND_ASSETS ?>img/about/dot.png" alt="shape-img">
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-5 col-md-5 wow fadeInUp" data-wow-delay=".3s">
                                <div class="about-counter-items">
                                    <h2><span class="count"><?= $aboutTitle ?></span>+</h2>
                                    <h5>
                                        Years Of <br> experience
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 wow fadeInUp" data-wow-delay=".5s">
                                <div class="about-image-1">
                                    <img src="<?= $aboutImage1Url1 ?>" alt="about-img">
                                </div>
                            </div>
                            <div class="col-lg-12 wow fadeInUp" data-wow-delay=".7s">
                                <div class="about-image-2 bg-cover" style="background-image: url('<?= $aboutImage1Url2 ?>');">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="about-content">
                        <div class="section-title">
                            <h6 class="wow fadeInUp">About US</h6>
                            <h2 class="splt-txt wow" data-splitting>
                                Meet Innovate Kitchen, <br> Your Trusted Kitchen Design Partner
                            </h2>
                        </div>

                        <p class="mt-3 mt-md-0 wow fadeInUp" data-wow-delay=".5s">
                            With over 25 years of experience, Innovate Kitchen transforms your kitchen into a stylish and functional space that enhances your lifestyle.
                            Our expert team is dedicated to delivering innovative, customized kitchen solutions tailored to your unique needs.
                        </p>
                        <p class="mt-4 wow fadeInUp" data-wow-delay=".7s">
                            We combine premium materials, advanced techniques, and creative design to ensure every project exceeds expectations. Let us help you create a kitchen that inspires and elevates everyday living.
                        </p>
                        <div class="about-button wow fadeInUp" data-wow-delay=".9s">
                            <a href="about-us" class="theme-btn">
                                Read More
                                <i class="fas fa-long-arrow-right"></i>
                            </a>
                        </div>
                        <div class="star-image">
                            <img src="<?= FRONTEND_ASSETS ?>img/hero/star.png" alt="shape-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>