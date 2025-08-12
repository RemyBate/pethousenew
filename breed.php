<?php
require_once __DIR__ . '/includes/pets-data.php';

$slug = isset($_GET['breed']) ? preg_replace('/[^a-z0-9\-]/i', '', $_GET['breed']) : '';
if ($slug === '') {
  header('Location: available-pets.php');
  exit;
}

$allImages = scanPetImages($slug);
// Exactly 4 images per dog. Show ALL curated subfolders; if none, fall back to top-level groups.
$imagesPerDog = 4;

// First, prefer curated subfolders: assets/img/<breed>/<dog-id>/[1..].jpg
$groups = [];
$breedDir = __DIR__ . '/assets/img/' . $slug;
if (is_dir($breedDir)) {
  $entries = scandir($breedDir) ?: [];
  sort($entries, SORT_NATURAL | SORT_FLAG_CASE);
  foreach ($entries as $entry) {
    if ($entry === '.' || $entry === '..') { continue; }
    $full = $breedDir . '/' . $entry;
    if (!is_dir($full)) { continue; }
    // Collect images within this dog folder
    $files = scandir($full) ?: [];
    $images = [];
    foreach ($files as $f) {
      if ($f === '.' || $f === '..') { continue; }
      $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg','jpeg','png','webp'], true)) {
        $images[] = 'assets/img/' . $slug . '/' . $entry . '/' . $f;
      }
    }
    sort($images, SORT_NATURAL | SORT_FLAG_CASE);
    if (count($images) > 0) {
      // Use the subfolder name as this dog's display name when available
      $dogName = ucwords(str_replace(['-', '_'], ' ', $entry));
      // Allow variable image counts per dog, but cap to $imagesPerDog for consistent gallery size
      $groups[] = [
        'name' => $dogName,
        'images' => array_slice($images, 0, $imagesPerDog),
      ];
    }
  }
}

// If there are no curated subfolders, fall back to top-level images grouped by 4
if (count($groups) === 0) {
  $usableCount = intdiv(count($allImages), $imagesPerDog) * $imagesPerDog;
  $usable = array_slice($allImages, 0, $usableCount);
  $fallbackGroups = array_chunk($usable, $imagesPerDog);
  foreach ($fallbackGroups as $g) {
    if (count($g) === $imagesPerDog) {
      $groups[] = [
        'name' => null,
        'images' => $g,
      ];
    }
  }
}
if (count($groups) === 0) {
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
        <div class="row gx-4 gy-5 custom-gx-3x justify-content-center">
          <?php foreach ($groups as $idx => $group): if (empty($group)) { continue; } $name = isset($group['name']) && $group['name'] ? $group['name'] : generateDogName($slug, $idx); $images = isset($group['images']) ? $group['images'] : $group; if (count($images) === 0) { continue; } $gallery = $slug . '-dog-' . $idx; $cover = $images[0]; ?>
            <div class="col-md-6 col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo 100 + ($idx % 5) * 50; ?>">
              <div class="card border-0 shadow-sm dog-card">
                <div class="row g-0 align-items-stretch">
                  <div class="col-md-6">
                    <a href="<?php echo htmlspecialchars($cover); ?>" class="glightbox" data-gallery="<?php echo htmlspecialchars($gallery); ?>" aria-label="Open <?php echo htmlspecialchars($name); ?>'s photo gallery">
                      <img src="<?php echo htmlspecialchars($cover); ?>" class="img-fluid w-100 h-100" alt="<?php echo htmlspecialchars($name); ?>" />
                    </a>
                    <?php for ($i = 1; $i < count($images); $i++): ?>
                      <a href="<?php echo htmlspecialchars($images[$i]); ?>" class="glightbox" data-gallery="<?php echo htmlspecialchars($gallery); ?>" style="display:none"></a>
                    <?php endfor; ?>
                  </div>
                  <div class="col-md-6 d-flex">
                    <div class="p-4 d-flex flex-column justify-content-center">
                      <h2 class="mb-2"><?php echo htmlspecialchars($name); ?></h2>
                      <p class="mb-2"><strong>Breed:</strong> <?php echo htmlspecialchars($displayName); ?></p>
                      <p class="mb-3"><strong>Age:</strong> Varies</p>
                      <p class="mb-4">Loving, healthy and ready to join a caring home. Click the photo to view a larger image of <?php echo htmlspecialchars($name); ?>.</p>
                      <div>
                        <a href="contact.php" class="btn btn-primary me-2">Adopt <?php echo htmlspecialchars($name); ?></a>
                        <a href="<?php echo htmlspecialchars($cover); ?>" class="btn btn-outline-primary glightbox" data-gallery="<?php echo htmlspecialchars($gallery); ?>">View Gallery</a>
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


