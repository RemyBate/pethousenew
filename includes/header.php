<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">
    <a href="index.php" class="logo d-flex align-items-center me-auto">
      <h1 class="sitename">PupNest</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
          <a href="available-pets.php"><span>Available Pets</span>
            <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li class="dropdown breed-dropdown">
              <a href="#"><span>Breeds</span> <i class="bi bi-chevron-right toggle-dropdown"></i></a>
              <ul class="breeds-grid">
                <?php require_once __DIR__ . '/pets-data.php'; $breedSlugs = getBreedSlugs(); foreach ($breedSlugs as $slug): ?>
                  <li><a href="breed.php?breed=<?php echo htmlspecialchars($slug); ?>"><?php echo htmlspecialchars(getDisplayNameForSlug($slug)); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="reviews.php"><span>Reviews</span>
            <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li><a href="reviews.php#adoption-stories">Adoption Stories</a></li>
            <li><a href="reviews.php#testimonials">Customer Testimonials</a></li>
            <li><a href="reviews.php#google-reviews">Google Reviews</a></li>
          </ul>
        </li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <a class="btn-getstarted" href="available-pets.php">Adopt Now</a>
  </div>
  </header>


