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
                <?php 
                  require_once __DIR__ . '/pets-data.php'; 
                  $allSlugs = getBreedSlugs(); 
                  // Featured breeds (name => slug)
                  $featured = [
                    'French Bulldog' => 'french-bulldog',
                    'Labrador Retriever' => 'labrador-retriever',
                    'Golden Retriever' => 'golden-retriever',
                    'German Shepherd Dog' => 'german-shepherd',
                    'Poodle' => 'poodle',
                    'Dachshund' => 'dachshund',
                    'Bulldog' => 'bulldog',
                    'Beagle' => 'beagle',
                    'Rottweiler' => 'rottweiler',
                    'German Shorthaired Pointer' => 'german-shorthaired-pointer',
                    'Pembroke Welsh Corgi' => 'pembroke-welsh-corgi',
                    'Australian Shepherd' => 'australian-shepherd',
                    'Yorkshire Terrier' => 'yorkshire-terrier',
                    'Cavalier King Charles Spaniel' => 'cavalier-king-charles-spaniel',
                    'Doberman Pinscher' => 'doberman-pinscher',
                    'Cane Corso' => 'cane-corso',
                    'Miniature Schnauzer' => 'miniature-schnauzer',
                    'Boxer' => 'boxer',
                    'Great Dane' => 'great-dane',
                    'Shih Tzu' => 'shih-tzu',
                  ];
                  // Render featured first (only if images exist)
                  foreach ($featured as $label => $slug) {
                    if (!in_array($slug, $allSlugs, true)) { continue; }
                    if (count(scanPetImages($slug)) === 0) { continue; }
                ?>
                  <li><a href="breed.php?breed=<?php echo htmlspecialchars($slug); ?>"><?php echo htmlspecialchars($label); ?></a></li>
                <?php }
                  // Render the rest
                  foreach ($allSlugs as $slug) {
                    if (in_array($slug, $featured, true)) { continue; }
                    $label = getDisplayNameForSlug($slug);
                    echo '<li><a href="breed.php?breed=' . htmlspecialchars($slug) . '">' . htmlspecialchars($label) . '</a></li>';
                  }
                ?>
              </ul>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="reviews.php"><span>Reviews</span>
            <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li><a href="reviews.php?section=adoption-stories">Adoption Stories</a></li>
            <li><a href="reviews.php?section=testimonials">Customer Testimonials</a></li>
            <li><a href="reviews.php?section=google-reviews">Google Reviews</a></li>
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


