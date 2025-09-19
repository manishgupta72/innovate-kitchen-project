<?php include 'include/header.php' ?>
<?php include 'include/mobile-side-bar.php' ?>
<?php include 'include/menu.php' ?>
<?php
require_once 'model/AdminModel.php';
require_once 'model/MediaModel.php';

$adminModel = new AdminModel($pdo);
$contactSettings = $adminModel->getContactSettings();
$generalSettings = $adminModel->getGeneralSettings();


$mediaModel = new MediaModel($pdo);

// Fetch the single image where media_type = 3
$singleImage = $mediaModel->getMediaByType(13);
$singleImageUrl = !empty($singleImage) ? UPLOAD_MEDIA . htmlspecialchars($singleImage[0]['upload_file']) : 'https://placehold.co/630x400'; // Default placeholder if no image found
?>
<div class="ScrollSmoother-content">

    <!-- Contact Info Section Start -->

    <section class="contact-Info-section section-padding pb-80 fix">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6 class="wow fadeInUp">Our Kitchen Desgin </h6>
                <!-- <h2 class="splt-txt wow" data-splitting>Experience Luxury with <br> Interior Artistry</h2> -->
            </div>
        </div>
        <div class="container">
            <div class="contact-info-wrapper">
                <div class="row g-0">
                    <div class="col-xl-6 col-lg-6">
                        <div class="google-map">
                            <iframe src="<?php echo htmlspecialchars($contact['google_map_iframe']); ?>" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 section-bg-2">
                        <div class="contact-info-content">
                            <h2 class="splt-txt wow" data-splitting>Contact Info</h2>
                            <p class="wow fadeInUp" data-wow-delay=".5s">Cloud computing is a model for delivering on-demand computing resources over the internet. It can benefit your </p>
                            <div class="contact-info-area">
                                <div class="row g-4">
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                                        <div class="contact-info-items">
                                            <div class="icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <div class="content">
                                                <h3 class="splt-txt wow" data-splitting>Phone</h3>
                                                <h6><a href="tel:<?php echo htmlspecialchars($contact['contact_number']); ?>" class="me-3"><?php echo htmlspecialchars($contact['contact_number']); ?></a><a href="tel:<?php echo htmlspecialchars($contact['contact_number']); ?>"><?php echo htmlspecialchars($contact['contact_number']); ?></a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".4s">
                                        <div class="contact-info-items">
                                            <div class="icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="content">
                                                <h3 class="splt-txt wow" data-splitting>Email</h3>
                                                <h6><a href="mailto:<?php echo htmlspecialchars($contact['email_id']); ?>" class="link"><?php echo htmlspecialchars($contact['email_id']); ?></a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".6s">
                                        <div class="contact-info-items">
                                            <div class="icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="content">
                                                <h3 class="splt-txt wow" data-splitting>Location</h3>
                                                <h6><?php echo htmlspecialchars($contact['address_line_1']); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".8s">
                                        <div class="contact-info-items">
                                            <div class="icon">
                                                <i class="fas fa-link"></i>
                                            </div>
                                            <div class="content">
                                                <h3 class="splt-txt wow" data-splitting>Website</h3>
                                                <h6>www.example.com</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section Start -->
    <section class="contact-section-4 fix section-padding pt-80">
        <div class="container">
            <div class="contact-wrapper-3" style="background-image: url('<?= $singleImageUrl ?>'); background-repeat: no-repeat; background-size: cover;">
                <div class="row">
                    <div class="col-lg-6 ">
                        <div class="contact-content px-3">
                            <div class="section-title">
                                <h6 class="wow fadeInUp">Contact Us</h6>
                                <h2 class="splt-txt wow" data-splitting>Get In Touch</h2>
                            </div>
                            <form action="<?= FRONTEND_ASSETS ?>php/contact-form.php" method="POST" class="contact-form-items mt-4 mt-md-0">
                                <div class="contact-form-success alert alert-success d-none mt-4">
                                    <strong>Success!</strong> Your message has been sent to us.
                                </div>

                                <div class="contact-form-error alert alert-danger d-none mt-4">
                                    <strong>Error!</strong> There was an error sending your message.
                                    <span class="mail-error-message text-1 d-block"></span>
                                </div>

                                <div class="row g-4">
                                    <div class="col-lg-6 wow fadeInUp">
                                        <div class="form-clt">
                                            <input type="text" value="" name="name" id="name" data-msg-required="Please enter your name." placeholder="Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp">
                                        <div class="form-clt">
                                            <input type="text" value="" name="email" id="email" placeholder="Your Email" data-msg-email="Please enter a valid email address." required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp">
                                        <div class="form-clt">
                                            <input type="text" value="" name="phone" id="number" placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp">
                                        <div class="form-clt">
                                            <input type="text" value="" name="company" id="subject" placeholder="Subject">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 wow fadeInUp">
                                        <div class="form-clt">
                                            <textarea maxlength="5000" placeholder="Enter Additional Information or Questions" rows="8" class="form-control text-3 h-auto border-width-2 border-radius-2 text-light border-color-grey-200 py-2" name="message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 wow fadeInUp">
                                        <button type="submit" class="theme-btn padding-style" data-clone-element="1">
                                            submit now <i class="fas fa-long-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/footer-section.php' ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('.contact-form-items');
        const successAlert = document.querySelector('.contact-form-success');
        const errorAlert = document.querySelector('.contact-form-error');
        const errorMsg = document.querySelector('.mail-error-message');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.response === 'success') {
                        successAlert.classList.remove('d-none');
                        errorAlert.classList.add('d-none');
                        form.reset();
                    } else {
                        successAlert.classList.add('d-none');
                        errorAlert.classList.remove('d-none');
                        errorMsg.textContent = data.errorMessage || "Something went wrong.";
                    }
                })
                .catch(error => {
                    successAlert.classList.add('d-none');
                    errorAlert.classList.remove('d-none');
                    errorMsg.textContent = error.toString();
                });
        });
    });
</script>

<?php include 'include/footer.php' ?>