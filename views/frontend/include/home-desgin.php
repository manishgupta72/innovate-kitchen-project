   <?php
    require_once 'model/ContentModel.php';
    $database = new Database();
    $pdo = $database->connect();
    $contentModel = new ContentModel($pdo);

    // Fetch designs
    $designs = $contentModel->getContentByType(28);
    ?>
   <!-- News Section Start -->
   <section class="news-section fix section-padding pt-0">
       <div class="container">
           <div class="section-title-area">
               <div class="section-title">
                   <h6 class="wow fadeInUp">Types Of Kitchen Designs</h6>
                   <h2 class=" splt-txt wow" data-splitting>Exploring Popular Kitchen Designs <br> to Inspire Your Space</h2>
               </div>
               <div class="array-button wow fadeInUp" data-wow-delay=".5s">
                   <button class="array-prev"><i class="fal fa-arrow-left"></i></button>
                   <button class="array-next"><i class="fal fa-arrow-right"></i></button>
               </div>
           </div>
           <div class="swiper news-slider-2">
               <div class="swiper-wrapper">
                   <?php foreach ($designs as $design): ?>
                       <div class="swiper-slide">
                           <div class="news-slider-items">
                               <div class="news-content">
                                   <h3>
                                       <a href="our-design#design-<?= $design['id'] ?>"><?= htmlspecialchars($design['title']) ?></a>

                                   </h3>
                                   <p>
<?php
    // Get raw description (first <p> or fallback)
    $desc = $design['description'];
    if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $desc, $matches)) {
        $text = strip_tags($matches[1]);
    } else {
        $text = strip_tags($desc);
    }

    // Take at most 30 words (optional)
    $words = preg_split('/\s+/', $text);
    $words = array_slice($words, 0, 30);

    // Chunk into lines of 4 words each
    $chunks = array_chunk($words, 6);

    // Output each chunk as a line
    foreach ($chunks as $chunk) {
        echo implode(' ', $chunk) . '<br>';
    }

    // Optionally add "..." at the very end if you truncated:
    if (count($words) === 30) {
        echo '...';
    }
?>
</p>




                                   <a href="our-design#design-<?= $design['id'] ?>" class="link-btn">
                                       Read more <i class="fas fa-long-arrow-right"></i>
                                   </a>


                               </div>
                               <div class="news-image bg-cover" style="background-image: url('<?= UPLOAD_CONTENT . htmlspecialchars($design['upload_image2']) ?>');"></div>
                           </div>
                       </div>
                   <?php endforeach; ?>

               </div>
           </div>
       </div>
   </section>