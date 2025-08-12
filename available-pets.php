<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Available Pets - PupNest</title>
    <meta name="description" content="View our available pets for adoption">
    <meta name="keywords" content="pets, adoption, dogs, cats, animals">

    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/header.php' ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title" data-aos="fade">
            <div class="container">
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Available Pets</li>
                    </ol>
                </nav>
                <h1>Available Pets</h1>
            </div>
        </div>

        <!-- Available Pets Section -->
        <section id="available-pets" class="available-pets section">
            <div class="container">
                <?php require_once __DIR__ . '/includes/pets-data.php'; $pets = getAllPets(); ?>
                <div class="row gx-4 gy-5 justify-content-center">
                  <?php foreach ($pets as $index => $pet): ?>
                    <?php if (!isset($pet['images']) || count($pet['images']) === 0) continue; ?>
                    <?php $galleryId = htmlspecialchars($pet['slug']) . '-gallery'; ?>
                    <div class="col-md-6 col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo 100 + ($index % 5) * 50; ?>">
                      <div class="card border-0 shadow-sm">
                        <div class="row g-0 align-items-stretch">
                          <div class="col-md-6">
                            <a href="<?php echo htmlspecialchars($pet['images'][0]); ?>" class="glightbox" data-gallery="<?php echo $galleryId; ?>" aria-label="Open <?php echo htmlspecialchars($pet['name']); ?>'s photo gallery">
                              <img src="<?php echo htmlspecialchars($pet['images'][0]); ?>" class="img-fluid w-100 h-100" alt="<?php echo htmlspecialchars($pet['name']); ?>" style="object-fit: cover; min-height: 100%" />
                            </a>
                            <?php for ($i = 1; $i < count($pet['images']); $i++): ?>
                              <a href="<?php echo htmlspecialchars($pet['images'][$i]); ?>" class="glightbox" data-gallery="<?php echo $galleryId; ?>" style="display:none"></a>
                            <?php endfor; ?>
                          </div>
                          <div class="col-md-6 d-flex">
                            <div class="p-4 d-flex flex-column justify-content-center">
                              <h2 class="mb-2"><?php echo htmlspecialchars($pet['name']); ?></h2>
                              <p class="mb-2"><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                              <p class="mb-3"><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?></p>
                              <p class="mb-4"><?php echo htmlspecialchars($pet['description']); ?> Click the photo to view more pictures of <?php echo htmlspecialchars($pet['name']); ?>.</p>
                              <div>
                                <a href="contact.php" class="btn btn-primary me-2">Adopt <?php echo htmlspecialchars($pet['name']); ?></a>
                                <a href="<?php echo htmlspecialchars($pet['images'][0]); ?>" class="btn btn-outline-primary glightbox" data-gallery="<?php echo $galleryId; ?>">View Gallery</a>
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