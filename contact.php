<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Contact - PupNest</title>
  <meta name="description" content="Get in touch with PupNest" />
  <meta name="keywords" content="contact, pet adoption, support" />

  <?php require 'includes/head.php' ?>
</head>

<body>
  <?php require 'includes/header.php' ?>

  <main class="main">
    <div class="page-title" data-aos="fade">
      <div class="container">
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Contact</li>
          </ol>
        </nav>
        <h1>Contact</h1>
      </div>
    </div>

    <section id="contact" class="contact section">
      <div class="container">
        <div class="section-header text-center mb-5">
          <h2>Contact Us</h2>
          <p>We would love to hear from you</p>
        </div>

        <div class="row">
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

          <div class="col-md-6">
            <form action="mailto:breedbabiesdaily@inbox.ru" method="post" enctype="text/plain" class="php-email-form">
              <div class="form-group mb-3">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required />
              </div>
              <div class="form-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Your Email" required />
              </div>
              <div class="form-group mb-3">
                <textarea name="message" class="form-control" rows="5" placeholder="Message" required></textarea>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Send Message</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require 'includes/footer.php' ?>
  <?php require 'includes/footerscripts.php' ?>

</body>
</html>


