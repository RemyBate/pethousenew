<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Index - PupNest Bootstrap Template</title>
  <meta name="description" content="" />
  <meta name="keywords" content="" />

  <?php require 'includes/head.php' ?>
</head>

<body class="index-page">
  <?php require 'includes/header.php' ?>

  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <div class="container">
        <div class="row gy-4">
          <div
            class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
            data-aos="zoom-out">
            <h1>Find Your Perfect Companion</h1>
            <p>Give a loving home to a pet in need</p>
            <div class="d-flex">
              <a href="available-pets.php" class="btn-get-started">View Available Pets</a>
              <?php $localVideo = 'assets/video/dogs.mp4'; $videoHref = file_exists($localVideo) ? $localVideo : 'https://www.youtube.com/watch?v=Y7f98aduVJ8'; ?>
              <a
                href="<?php echo htmlspecialchars($videoHref); ?>"
                class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>
            </div>
          </div>
          <div
            class="col-lg-6 order-1 order-lg-2 hero-img"
            data-aos="zoom-out"
            data-aos-delay="200">
            <?php
              // Build slides dynamically: always show Mae first, then every image found in assets/img/slide
              $slides = [];
              $slideDir = 'assets/img/slide';

              // Resolve Mae image path robustly (supports multiple locations and extensions)
              $primaryPath = null;
              $primaryCandidates = [
                'assets/img/mae.jpg',
                'assets/img/mae.jpeg',
                'assets/img/mae.png',
                'assets/img/mae.webp',
              ];
              foreach ($primaryCandidates as $pp) {
                if (file_exists($pp)) { $primaryPath = $pp; break; }
              }
              if ($primaryPath === null && is_dir($slideDir)) {
                $exts = ['jpg','jpeg','png','webp','JPG','JPEG','PNG','WEBP'];
                foreach ($exts as $ext) {
                  $candidate = $slideDir . '/mae.' . $ext;
                  if (file_exists($candidate)) { $primaryPath = $candidate; break; }
                }
                if ($primaryPath === null) {
                  $matches = glob($slideDir . '/mae*.*', GLOB_NOSORT);
                  if (is_array($matches) && count($matches) > 0) { $primaryPath = $matches[0]; }
                }
              }
              if ($primaryPath !== null) {
                $slides[] = [
                  'src' => $primaryPath,
                  'alt' => 'Mae the French Bulldog Puppy',
                  'title' => 'Mae',
                  'animated' => true,
                ];
              }
              if (is_dir($slideDir)) {
                $patterns = [
                  $slideDir . '/*.jpg', $slideDir . '/*.jpeg', $slideDir . '/*.png', $slideDir . '/*.webp',
                  $slideDir . '/*.JPG', $slideDir . '/*.JPEG', $slideDir . '/*.PNG', $slideDir . '/*.WEBP',
                ];
                $allPaths = [];
                foreach ($patterns as $p) {
                  $matches = glob($p, GLOB_NOSORT);
                  if (is_array($matches)) { $allPaths = array_merge($allPaths, $matches); }
                }
                // Unique and natural sort
                $allPaths = array_values(array_unique($allPaths));
                natcasesort($allPaths);
                foreach ($allPaths as $path) {
                  $baseNoExt = strtolower(pathinfo($path, PATHINFO_FILENAME));
                  if ($primaryPath !== null && $baseNoExt === 'mae') { continue; }
                  $slides[] = [
                    'src' => $path,
                    'alt' => 'Adoption slideshow image',
                    'title' => '',
                    'animated' => false,
                  ];
                }
              }
            ?>
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000" data-bs-touch="true">
              <div class="carousel-indicators">
                <?php foreach ($slides as $i => $_): ?>
                  <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>"<?php echo $i === 0 ? ' class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
                <?php endforeach; ?>
              </div>
              <div class="carousel-inner">
                <?php foreach ($slides as $i => $slide): ?>
                  <div class="carousel-item<?php echo $i === 0 ? ' active' : ''; ?>">
                    <img
                      src="<?php echo htmlspecialchars($slide['src']); ?>"
                      class="d-block w-100 img-fluid<?php echo $slide['animated'] ? ' animated' : ''; ?> hero-dog-image"
                      alt="<?php echo htmlspecialchars($slide['alt']); ?>"<?php echo !empty($slide['title']) ? ' title="' . htmlspecialchars($slide['title']) . '"' : ''; ?>
                      <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy" fetchpriority="low"'; ?> decoding="async" />
                  </div>
                <?php endforeach; ?>
              </div>
              <?php if (count($slides) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /Hero Section -->
  </main>

  <section id="contact" class="contact section">
    <div class="container">
      <div class="section-header text-center mb-5">
        <h2>Contact Us</h2>
        <p>We would love to hear from you</p>
      </div>

      <div class="row">
        <!-- Left Column: Contact Info -->
        <div class="col-md-6">
          <div class="info-box mb-4">
            <h5><i class="bi bi-geo-alt-fill me-2"></i> Address</h5>
            <p>463 Deer Ln<br />Guffey, Colorado(CO), 80820</p>
          </div>
          <div class="info-box mb-4">
            <h5><i class="bi bi-telephone-fill me-2"></i> Phone</h5>
            <p>+1(720) 257-9612</p>
          </div>
          <div class="info-box">
            <h5><i class="bi bi-envelope-fill me-2"></i> Email</h5>
            <p>breedbabiesdaily@inbox.ru</p>
          </div>
        </div>

        <!-- Right Column: Contact Form -->
        <div class="col-md-6">
          <form
            action="contact-submit.php"
            method="post"
            class="php-email-form">
            <div class="form-group mb-3">
              <input
                type="text"
                name="name"
                class="form-control"
                placeholder="Your Name"
                required />
            </div>
            <div class="form-group mb-3">
              <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Your Email"
                required />
            </div>
            <div class="form-group mb-3">
              <input
                type="tel"
                name="phone"
                class="form-control"
                placeholder="Your Phone Number" />
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <input
                    type="text"
                    name="city"
                    class="form-control"
                    placeholder="City"
                    required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <input
                    type="text"
                    name="country"
                    class="form-control"
                    placeholder="Country"
                    required />
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <input
                type="text"
                name="address"
                class="form-control"
                placeholder="Address" />
            </div>
            <div class="form-group mb-3">
              <textarea
                name="message"
                class="form-control"
                rows="5"
                placeholder="Message"
                required></textarea>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary">
                Send Message
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>


  <?php require 'includes/footer.php' ?>
  <?php require 'includes/footerscripts.php' ?>

</body>

</html>