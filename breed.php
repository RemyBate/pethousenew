<?php
require_once __DIR__ . '/includes/pets-data.php';

$slug = isset($_GET['breed']) ? preg_replace('/[^a-z0-9\-]/i', '', $_GET['breed']) : '';
if ($slug === '') {
  header('Location: available-pets.php');
  exit;
}

$images = scanPetImages($slug);
if (count($images) === 0) {
  header('Location: available-pets.php');
  exit;
}

$displayName = getDisplayNameForSlug($slug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title><?php echo htmlspecialchars($displayName); ?> - PupNest</title>
  <meta name="description" content="Available <?php echo htmlspecialchars($displayName); ?> dogs for adoption" />
  <meta name="keywords" content="<?php echo htmlspecialchars($displayName); ?>, dog breed, adoption" />
  <?php require 'includes/head.php' ?>
  <style>
    .dog-card img { object-fit: cover; min-height: 100%; }
  </style>
  </head>
<body>
  <?php require 'includes/header.php' ?>

  <main class="main">
    <div class="page-title" data-aos="fade">
      <div class="container">
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="available-pets.php">Available Pets</a></li>
            <li class="current"><?php echo htmlspecialchars($displayName); ?></li>
          </ol>
        </nav>
        <h1><?php echo htmlspecialchars($displayName); ?></h1>
      </div>
    </div>

    <section class="section">
      <div class="container">
        <div class="row gy-4 justify-content-center">
          <?php foreach ($images as $idx => $img): $name = generateDogName($slug, $idx); $gallery = $slug . '-dog-' . $idx; ?>
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="<?php echo 100 + ($idx % 5) * 50; ?>">
              <div class="card border-0 shadow-sm dog-card">
                <div class="row g-0 align-items-stretch">
                  <div class="col-md-6">
                    <a href="<?php echo htmlspecialchars($img); ?>" class="glightbox" data-gallery="<?php echo htmlspecialchars($gallery); ?>" aria-label="Open <?php echo htmlspecialchars($name); ?>'s photo gallery">
                      <img src="<?php echo htmlspecialchars($img); ?>" class="img-fluid w-100 h-100" alt="<?php echo htmlspecialchars($name); ?>" />
                    </a>
                  </div>
                  <div class="col-md-6 d-flex">
                    <div class="p-4 d-flex flex-column justify-content-center">
                      <h2 class="mb-2"><?php echo htmlspecialchars($name); ?></h2>
                      <p class="mb-2"><strong>Breed:</strong> <?php echo htmlspecialchars($displayName); ?></p>
                      <p class="mb-3"><strong>Age:</strong> Varies</p>
                      <p class="mb-4">Loving, healthy and ready to join a caring home. Click the photo to view a larger image of <?php echo htmlspecialchars($name); ?>.</p>
                      <div>
                        <a href="contact.php" class="btn btn-primary me-2">Adopt <?php echo htmlspecialchars($name); ?></a>
                        <a href="<?php echo htmlspecialchars($img); ?>" class="btn btn-outline-primary glightbox" data-gallery="<?php echo htmlspecialchars($gallery); ?>">View Gallery</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

  <?php require 'includes/footer.php' ?>
  <?php require 'includes/footerscripts.php' ?>
</body>
</html>


