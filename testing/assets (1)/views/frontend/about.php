<?php include 'include/header.php' ?>
<?php include 'include/mobile-side-bar.php' ?>
<?php include 'include/menu.php' ?>
<?php
require_once 'model/MediaModel.php';
require_once 'model/AdminModel.php';
$counterModel = new AdminModel($pdo);

$mediaModel = new MediaModel($pdo);


$aboutImage2 = $mediaModel->getMediaByType(26);

$aboutImage1Url2 = !empty($aboutImage2) ? UPLOAD_MEDIA . htmlspecialchars($aboutImage2[0]['upload_file']) : 'https://placehold.co/479x300';



$counterSettings = $counterModel->getCounterSettings();

$counters = [
    [

        'value' => $counterSettings['count_no_1'],
        'label' => $counterSettings['counter_name_1']
    ],
    [

        'value' => $counterSettings['count_no_2'],
        'label' => $counterSettings['counter_name_2']
    ],
    [

        'value' => $counterSettings['count_no_3'],
        'label' => $counterSettings['counter_name_3']
    ],
    [

        'value' => $counterSettings['count_no_4'],
        'label' => $counterSettings['counter_name_4']
    ],
];

?>

<div class="ScrollSmoother-content">

    <!-- Service Section Start -->
    <section class="service-section fix section-padding section-bg-2 bg-cover pb-0">
        <!-- <div class="bg-shape">
            <img src="<?= FRONTEND_ASSETS ?>img/service/bg-shape.jpg" alt="shape-img">
        </div> -->
        <div class="container mt-5 py-5">

            <div class="service-wrapper">
                <div class="row g-4">
                    <div class="col-xl-9 col-lg-12">
                        <div class="service-left">
                            <div class="section-title">
                                <h6 class="wow fadeInUp">About Innovate Kitchen</h6>
                                <h2 class="splt-txt wow" data-splitting>
                                    Transforming Kitchens, Elevating<br> Lifestyles Since 2010
                                </h2>
                                <p class="mt-3">
                                    Since 2010, Innovate Modular Kitchen has been a trusted name in the modular kitchen industry, delivering customized kitchen solutions that combine style, functionality, and lasting quality. With over 15 years of experience, we specialize in transforming everyday kitchens into beautiful, efficient, and highly personalized spaces tailored to your unique needs.
                                </p>
                                <p class="mt-4">
                                    Our commitment to excellence is reflected in our choice of materials — we use premium marine-grade BWP ply and only branded, imported hardware, ensuring durability even under demanding conditions. Supported by a professional, well-managed team of skilled carpenters, designers, and installation experts, we guarantee precision and creativity at every step.
                                </p>
                                <p class="mt-4">
                                    From the first consultation through to the final installation, we listen closely to your requirements and preferences. Our advanced 3D design modeling helps you visualize your dream kitchen before production begins. Whether it’s a compact kitchen makeover or a luxurious modular installation, Innovate Modular Kitchen offers flexible, tailored designs to fit your lifestyle perfectly.
                                </p>
                                <p class="mt-4">
                                    Having delivered over 2000 kitchens with a strong after-sales support system and warranty, we pride ourselves on being a reliable partner for homeowners seeking innovative and high-quality kitchen interiors.
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 wow fadeInUp" data-wow-delay=".9s">
                        <div class="service-image bg-cover" style="background-image: url('<?= $aboutImage1Url2 ?>');"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/home-brand.php' ?>
    <!-- Achievement Section Start -->
    <section class="achievement-section fix section-bg-2 section-padding pt-0">
        <div class="shape-left">
            <img src="<?= FRONTEND_ASSETS ?>img/shape.png" alt="shape-img">
        </div>
        <div class="container">
            <div class="achievement-wrapper">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="achievement-content">
                            <div class="section-title">
                                <h6 class="wow fadeInUp">Why choose us</h6>
                                <h3 class="splt-txt wow" data-splitting>
                                    Experience, Quality & Customization You Can Trust
                                </h3>
                            </div>
                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay=".3s">
                                <strong>15+ Years of Expertise:</strong> We bring over 15 years of proven experience to deliver exceptional modular kitchen solutions.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay=".5s">
                                <strong>Premium Materials:</strong> Using marine-grade BWP ply and branded imported hardware ensures durability and long-lasting quality.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay=".7s">
                                <strong>100% Customized Designs:</strong> Every kitchen is tailored to fit your unique style, space, and functional needs.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay=".9s">
                                <strong>Skilled In-House Team:</strong> Our expert carpenters, designers, and installers work closely to ensure flawless execution.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay="1.1s">
                                <strong>Wide Range of Finishes & Colors:</strong> Choose from an extensive palette to perfectly complement your home décor.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay="1.3s">
                                <strong>Fast & Reliable Delivery:</strong> We guarantee a 25-day delivery timeline, so your kitchen is ready on time.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay="1.5s">
                                <strong>Strong After-Sales Support:</strong> Our commitment continues with reliable after-sales service and support.
                            </p>

                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay="1.7s">
                                <strong>Proven Track Record:</strong> Over 2000 kitchens delivered with satisfied customers attest to our quality and reliability.
                            </p>


                        </div>
                    </div>
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <div class="counter-area">
                            <div class="row g-4">
                                <?php foreach ($counters as $counter) { ?>
                                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                                        <div class="counter-item bg-dark">
                                            <div class="icon">
                                                <i class="flaticon-frame"></i>
                                            </div>
                                            <div class="content">
                                                <h2><span class="count"><?= htmlspecialchars($counter['value']) ?></span>+</h2>
                                                <p><?= htmlspecialchars($counter['label']) ?></p>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include 'include/footer-section.php' ?>
</div>
<?php include 'include/footer.php' ?>