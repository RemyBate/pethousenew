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
              <a
                href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>
            </div>
          </div>
          <div
            class="col-lg-6 order-1 order-lg-2 hero-img"
            data-aos="zoom-out"
            data-aos-delay="200">
            <img
              src="assets/img/mae.jpg"
              class="img-fluid animated hero-dog-image"
              alt="Mae the French Bulldog Puppy"
              title="Mae" />
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
            action="mailto:breedbabiesdaily@inbox.ru"
            method="post"
            enctype="text/plain"
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