<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reviews - PupNest</title>
    <meta name="description" content="Read reviews and adoption stories from our happy pet parents">
    <meta name="keywords" content="pet adoption reviews, adoption stories, testimonials">

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
                        <li class="current">Reviews</li>
                    </ol>
                </nav>
                <h1>Reviews & Stories</h1>
            </div>
        </div>

        <?php $section = isset($_GET['section']) ? $_GET['section'] : ''; ?>

        <!-- Adoption Stories Section -->
        <?php if ($section === '' || $section === 'adoption-stories'): ?>
        <section id="adoption-stories" class="adoption-stories section">
            <div class="container">
                <div class="section-header">
                    <h2>Adoption Stories</h2>
                    <p>Heartwarming stories from our successful adoptions</p>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="story-card">
                            <div class="story-content">
                                <h3>Max's Journey to His Forever Home</h3>
                                <p class="story-meta">By Sarah Johnson | January 15, 2024</p>
                                <p>When we first met Max, he was a shy and timid dog. Now, he's the most loving and playful companion we could ask for...</p>
                                <a href="#" class="read-more">Read Full Story</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="story-card">
                            <div class="story-content">
                                <h3>Luna's New Beginning</h3>
                                <p class="story-meta">By Michael Brown | February 1, 2024</p>
                                <p>Luna came to us as a rescue cat with trust issues. Today, she's the queen of our household...</p>
                                <a href="#" class="read-more">Read Full Story</a>
                            </div>
                        </div>
                    </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="story-card">
                      <div class="story-content">
                        <h3>From Shelter to Sunshine</h3>
                        <p class="story-meta">By Olivia Carter | March 8, 2024</p>
                        <p>After weeks of patient socialization, Daisy found a family who adores her goofy smile and endless zoomies.</p>
                        <a href="#" class="read-more">Read Full Story</a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="story-card">
                      <div class="story-content">
                        <h3>Charlie Finds a Running Buddy</h3>
                        <p class="story-meta">By Ethan Miller | April 2, 2024</p>
                        <p>Charlie loves long runs at the park with his new person and has already completed his first 5k!</p>
                        <a href="#" class="read-more">Read Full Story</a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="story-card">
                      <div class="story-content">
                        <h3>A Gentle Giant Named Bruno</h3>
                        <p class="story-meta">By Sophia Nguyen | May 10, 2024</p>
                        <p>Bruno's calm nature won over every heart at home. He guards the toddler's naps like a pro.</p>
                        <a href="#" class="read-more">Read Full Story</a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="story-card">
                      <div class="story-content">
                        <h3>Maple Learns to Smile Again</h3>
                        <p class="story-meta">By Daniel Ross | June 1, 2024</p>
                        <p>With patience and love, Maple blossomed into a cuddly lapdog who never misses a movie night.</p>
                        <a href="#" class="read-more">Read Full Story</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Testimonials Section -->
        <?php if ($section === '' || $section === 'testimonials'): ?>
        <section id="testimonials" class="testimonials section">
            <div class="container">
                <div class="section-header">
                    <h2>Customer Testimonials</h2>
                    <p>What our happy pet parents say about us</p>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p><i class="bi bi-quote quote-icon-left"></i>
                                    The adoption process was smooth and professional. The staff was incredibly helpful and caring.
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                            <div class="testimonial-author">
                                <div>
                                    <h3>John Smith</h3>
                                    <h4>Dog Parent</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p><i class="bi bi-quote quote-icon-left"></i>
                                    I couldn't be happier with my adoption experience. My new cat has brought so much joy to my life.
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                            <div class="testimonial-author">
                                <div>
                                    <h3>Emily Davis</h3>
                                    <h4>Cat Parent</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p><i class="bi bi-quote quote-icon-left"></i>
                                    The follow-up care and support after adoption has been exceptional. Thank you PupNest!
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                            <div class="testimonial-author">
                                <div>
                                    <h3>Robert Wilson</h3>
                                    <h4>Dog Parent</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                  <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="testimonial-card">
                      <div class="testimonial-content">
                        <p><i class="bi bi-quote quote-icon-left"></i>
                            Incredible experience from start to finish. Our adoption counselor was patient and informative.
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                      </div>
                      <div class="testimonial-author">
                        <div>
                          <h3>Alex Carter</h3>
                          <h4>Dog Parent</h4>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="testimonial-card">
                      <div class="testimonial-content">
                        <p><i class="bi bi-quote quote-icon-left"></i>
                            We felt supported even after adoption with training tips and check-ins. Highly recommend!
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                      </div>
                      <div class="testimonial-author">
                        <div>
                          <h3>Priya Patel</h3>
                          <h4>Dog Parent</h4>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="testimonial-card">
                      <div class="testimonial-content">
                        <p><i class="bi bi-quote quote-icon-left"></i>
                            Our pup came home healthy and happy. The vet records and guidance were top-notch.
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                      </div>
                      <div class="testimonial-author">
                        <div>
                          <h3>Marco Silva</h3>
                          <h4>Dog Parent</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Google Reviews Section -->
        <?php if ($section === '' || $section === 'google-reviews'): ?>
        <section id="google-reviews" class="google-reviews section">
            <div class="container">
                <div class="section-header">
                    <h2>Google Reviews</h2>
                    <p>Our ratings and reviews on Google</p>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="google-review-card">
                            <div class="review-header">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-date">2 weeks ago</span>
                            </div>
                            <p class="review-text">Amazing place! The staff is very knowledgeable and caring. They helped me find the perfect companion for my family.</p>
                            <div class="reviewer">
                                <span>David Thompson</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="google-review-card">
                            <div class="review-header">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-date">1 month ago</span>
                            </div>
                            <p class="review-text">The adoption process was smooth and professional. They really care about matching the right pet with the right family.</p>
                            <div class="reviewer">
                                <span>Lisa Anderson</span>
                            </div>
                        </div>
                    </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="google-review-card">
                      <div class="review-header">
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <span class="review-date">3 months ago</span>
                      </div>
                      <p class="review-text">Wonderful staff and fantastic follow-up. We felt supported every step of the way.</p>
                      <div class="reviewer">
                        <span>Hannah Lee</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="google-review-card">
                      <div class="review-header">
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <span class="review-date">4 months ago</span>
                      </div>
                      <p class="review-text">Great matching process and thorough vetting. Our dog fits perfectly with our family.</p>
                      <div class="reviewer">
                        <span>Kevin Wright</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="google-review-card">
                      <div class="review-header">
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <span class="review-date">5 months ago</span>
                      </div>
                      <p class="review-text">Clean facility, kind volunteers, and a seamless adoption day. Highly recommend.</p>
                      <div class="reviewer">
                        <span>Naomi Brooks</span>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php require 'includes/footer.php' ?>
    <?php require 'includes/footerscripts.php' ?>
</body>
</html> 