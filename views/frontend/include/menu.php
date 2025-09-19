<!-- Header Area Start -->
<header>
    <div id="header-sticky" class="header-1 header-2">
        <div class="container-fluid">
            <div class="mega-menu-wrapper">
                <div class="header-main">
                    <div class="logo">
                        <a href="home" class="header-logo-2">
                            <img src="<?php echo UPLOAD_IMG_ADMIN . htmlspecialchars($settings['website_logo']); ?>" alt="logo-img" width="230px">
                        </a>
                    </div>
                    <div class="header-left">
                        <div class="mean__menu-wrapper">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>

                                        <li>
                                            <a href="home">Home</a>
                                        </li>
                                        <li>
                                            <a href="about-us">About Us</a>
                                        </li>
                                        <li>
                                            <a href="our-design">Our Designs</a>
                                        </li>
                                        <li>
                                            <a href="our-project">Our Projects</a>
                                        </li>
                                        <li>
                                            <a href="contact">Contact Us</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="header-right d-flex justify-content-end align-items-center">
                        <!-- <a href="#0" class="search-trigger search-icon"><i class="fal fa-search"></i></a> -->
                        <div class="icon-items">
                            <div class="icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="content">
                                <p>Requesting A Call:</p>
                                <h4><a href="tel:<?php echo htmlspecialchars($contact['contact_number']); ?>"><?php echo htmlspecialchars($contact['contact_number']); ?></a></h4>
                            </div>
                        </div>
                        <div class="header__hamburger d-xl-none my-auto">
                            <div class="sidebar__toggle">
                                <i class="far fa-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>