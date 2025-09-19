<?php
require_once 'model/AdminModel.php';

$adminModel = new AdminModel($pdo);
$contactSettings = $adminModel->getContactSettings();
$generalSettings = $adminModel->getGeneralSettings();
?>


<!-- Footer Section Start -->
<footer class="footer-section footer-bg">
    <div class="footer-shape-3">
        <img src="<?= FRONTEND_ASSETS ?>img/footer-shape-3.png" alt="shape-img">
    </div>
    <div class="container">
        <div class="footer-widgets-wrapper">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <a href="index.html">
                                <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['website_logo']); ?>"
                                    alt="logo-img" width="200px">
                            </a>
                        </div>
                        <div class="footer-content">
                            <p>
                                It is a long established fact that a reader will be distracted
                            </p>
                            <div class="social-icon d-flex align-items-center">
                                <a href="<?php echo htmlspecialchars($contact['facebook_url']); ?>"><i
                                        class="fab fa-facebook-f"></i></a>
                                <a href="<?php echo htmlspecialchars($contact['x_url']); ?>"><i
                                        class="fab fa-twitter"></i></a>
                                <a href="<?php echo htmlspecialchars($contact['youtube_url']); ?>"><i
                                        class="fab fa-youtube"></i></a>
                                <a href="<?php echo htmlspecialchars($contact['linkedin_url']); ?>"><i
                                        class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                    <div class="single-footer-widget ml-100">
                        <div class="widget-head">
                            <h3>Quick links</h3>
                        </div>
                        <ul class="list-items">
                            <li>
                                <a href="about-us">
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="our-design">
                                    Our Design
                                </a>
                            </li>

                            <li>
                                <a href="our-project">
                                    Our Project
                                </a>
                            </li>
                            <li>
                                <a href="contact.html">
                                    Contact Us
                                </a>
                            </li>
                            <!-- <li>
                                 <a href="team.html">
                                     Team Members
                                 </a>
                             </li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 ps-md-5 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>More Service</h3>
                        </div>
                        <ul class="list-items">
                            <li>
                                <a href="service-details.html">
                                    SpaceCraft Studio
                                </a>
                            </li>
                            <li>
                                <a href="service-details.html">
                                    Interiorscape Design
                                </a>
                            </li>
                            <li>
                                <a href="service-details.html">
                                    Architecture Plus
                                </a>
                            </li>
                            <li>
                                <a href="service-details.html">
                                    Dream Home Designs
                                </a>
                            </li>
                            <li>
                                <a href="service-details.html">
                                    Interior Perfection
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".9s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Contact</h3>
                        </div>
                        <div class="footer-content">
                            <div class="contact-info-area">
                                <div class="contact-items">
                                    <div class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="content">
                                        <p>Address</p>
                                        <h6><?php echo htmlspecialchars($contact['address_line_1']); ?></h6>
                                    </div>
                                </div>
                                <div class="contact-items">
                                    <div class="icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="content">
                                        <p>Contact</p>
                                        <h4><a
                                                href="tel:<?= !empty($contactSettings['contact_number']) ? htmlspecialchars($contactSettings['contact_number']) : '001-1234-88888' ?>"><?= !empty($contactSettings['contact_number']) ? htmlspecialchars($contactSettings['contact_number']) : '001-1234-88888' ?></a>
                                        </h4>
                                    </div>
                                </div>

                                <div class="contact-items">
                                    <div class="icon px-3">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="content">
                                        <p>Email</p>
                                        <h4> <a
                                                href="mailto:<?php echo htmlspecialchars($contact['email_id']); ?>"><?php echo htmlspecialchars($contact['email_id']); ?></a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container text-center">
            <div class="footer-wrapper align-items-center justify-content-between">
                <p class="wow fadeInLeft color-2" data-wow-delay=".3s">
                    © <a href="home"><?= date('Y') ?>
                        <?= htmlspecialchars($generalSettings['system_name'] ?? 'RB Tech') ?></a> . All Rights Reserved
                    | Desgin By <a href="https://rbtech.in">RB Tech Solutions</a>
                </p>
            </div>
        </div>
    </div>
</footer>