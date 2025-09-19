   <!-- Offcanvas Area Start -->
   <div class="fix-area">
       <div class="offcanvas__info">
           <div class="offcanvas__wrapper">
               <div class="offcanvas__content">
                   <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                       <div class="offcanvas__logo">
                           <a href="index">
                               <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['website_logo']); ?>" alt="logo-img" width="200px">
                           </a>
                       </div>
                       <div class="offcanvas__close">
                           <button>
                               <i class="fas fa-times"></i>
                           </button>
                       </div>
                   </div>

                   <div class="mobile-menu fix mb-3"></div>
                   <div class="offcanvas__contact">
                       <h4>Contact Info</h4>
                       <ul>
                           <li class="d-flex align-items-center">
                               <div class="offcanvas__contact-icon">
                                   <i class="fal fa-map-marker-alt"></i>
                               </div>
                               <div class="offcanvas__contact-text">
                                   <a target="_blank" href="#"><?php echo htmlspecialchars($contact['address_line_1']); ?></a>
                               </div>
                           </li>
                           <li class="d-flex align-items-center">
                               <div class="offcanvas__contact-icon mr-15">
                                   <i class="fal fa-envelope"></i>
                               </div>
                               <div class="offcanvas__contact-text">
                                   <a href="mailto:<?php echo htmlspecialchars($contact['email_id']); ?>"><span class="mailto:<?php echo htmlspecialchars($contact['email_id']); ?>"><?php echo htmlspecialchars($contact['email_id']); ?></span></a>
                               </div>
                           </li>
                           <li class="d-flex align-items-center">
                               <div class="offcanvas__contact-icon mr-15">
                                   <i class="fal fa-clock"></i>
                               </div>
                               <div class="offcanvas__contact-text">
                                   <a target="_blank" href="#">Mod-friday, 09am -05pm</a>
                               </div>
                           </li>
                           <li class="d-flex align-items-center">
                               <div class="offcanvas__contact-icon mr-15">
                                   <i class="far fa-phone"></i>
                               </div>
                               <div class="offcanvas__contact-text">
                                   <a href="tel:<?php echo htmlspecialchars($contact['contact_number']); ?>"><?php echo htmlspecialchars($contact['contact_number']); ?></a>
                               </div>
                           </li>
                       </ul>
                       <div class="header-button mt-4">
                           <a href="contact.html" class="theme-btn text-center">
                               Contact Us
                           </a>
                       </div>
                       <div class="social-icon d-flex align-items-center">
                           <a href="<?php echo htmlspecialchars($contact['facebook_url']); ?>"><i class="fab fa-facebook-f"></i></a>
                           <a href="<?php echo htmlspecialchars($contact['x_url']); ?>"><i class="fab fa-twitter"></i></a>
                           <a href="<?php echo htmlspecialchars($contact['youtube_url']); ?>"><i class="fab fa-youtube"></i></a>
                           <a href="<?php echo htmlspecialchars($contact['linkedin_url']); ?>"><i class="fab fa-linkedin-in"></i></a>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   <div class="offcanvas__overlay"></div>