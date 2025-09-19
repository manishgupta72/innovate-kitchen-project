    <?php
    require_once 'model/MediaModel.php';


    $mediaModel = new MediaModel($pdo);
    $bannerImages = $mediaModel->getMediaByType(14); // Fetch banners where media_type = 14

    ?>
    <!-- Hero Section Start -->
    <section class="hero-section hero-3 fix">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <span class="wow fadeInUp text-light ">Welcome To Innovate Kitchen</span>
                        <h2 class="wow fadeInUp text-theme" data-wow-delay=".3s">Personalize Your Kitchen Space <br> with Expert Interiors </h2>
                        <p class="wow fadeInUp text-light" data-wow-delay=".5s">
                            At Innovate Kitchen, we have proudly served clients for over a decade, delivering top-notch modular kitchen solutions that combine functionality with elegant design.
                            Our experience ensures a strong track record of transforming ordinary kitchens into personalized spaces tailored to your lifestyle and needs.
                        </p>
                        <div class="hero-button">
                            <a href="index-3.html" class="theme-btn radius-none padding-style wow fadeInUp" data-wow-delay=".7s">
                                Read More
                                <i class="fas fa-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-slider-items">
                        <div class="swiper hero-slider">
                            <div class="swiper-wrapper">
                                <?php
                                $count = 1;
                                foreach ($bannerImages as $banner) {
                                ?>
                                    <div class="swiper-slide">
                                        <div class="hero-image">
                                            <img src="<?= UPLOAD_MEDIA . htmlspecialchars($banner['upload_file']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>">
                                        </div>
                                    </div>
                                <?php
                                    $count++;
                                }
                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>