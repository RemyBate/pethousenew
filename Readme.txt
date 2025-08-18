PetHouseNew – Adoption Website

Overview
PetHouseNew is a PHP-based website that showcases adoptable dog breeds, adoption stories, customer reviews, and a contact workflow for prospective adopters. It builds on the Arsha Bootstrap template and adds dynamic content, a homepage slideshow, and a PHP form handler.

Key Features
- Homepage slideshow that always starts with mae.jpg, followed by local images from assets/img/slide.
- Available Breeds menu generated from the assets/img directory while automatically excluding non-breed folders (slide, slides, reviewers, team, stories).
- Unified contact form on Home and Contact pages; all fields required except Phone and Address.
- Server-side contact submission via PHP (mail()), with an option to switch to SMTP using PHPMailer.
- Reviews page with avatars and modal "Read Full Story" dialogs for adoption stories.
- About page with circular team headshots and short bios.
- Login/Signup pages with "Continue as guest" and Google sign-in option.

Requirements
- PHP 7.4 or higher (tested with XAMPP on Windows).
- A web server capable of running PHP (Apache via XAMPP or similar).
- Outbound email capability for PHP mail(); or SMTP credentials if using PHPMailer.

Quick Start (Local)
1) Place this project folder under your server root (e.g., C:\xampp\htdocs\pethouseNew or C:\xampp new\htdocs\pethouseNew).
2) Start Apache in XAMPP.
3) Visit http://localhost/pethouseNew/ in your browser.

Configuration
Contact Form (default mail())
- File: contact-submit.php
- The script validates required fields and sends an email to breedbabiesdaily@inbox.ru using PHP mail(). To change the recipient, edit the $to variable.

Optional: SMTP via PHPMailer
1) Download PHPMailer and place it under includes/phpmailer/ (or install via Composer).
2) In contact-submit.php, replace the mail() section with PHPMailer SMTP settings:
   Host, Port (465 or 587), Encryption (ssl/tls), Username, Password.
3) Get these values from your email provider or hosting control panel.

Slideshow Images
- Location: assets/img/slide and assets/img/mae.jpg
- Behavior: mae.jpg always appears first; other images from assets/img/slide follow in alphabetical order, skipping duplicates of mae.*.
- To update slides: add/remove images in assets/img/slide (JPG/PNG). Keep mae.jpg in assets/img.

Reviews, Stories, and Team Images
- Review avatars: assets/img/reviewers
- Story avatars: assets/img/stories
- Team headshots: assets/img/team (circular display, bios replace social links)

Available Breeds Menu
- Generated from subfolders under assets/img.
- Ignored folders so they do not appear as breeds: slide, slides, reviewers, team, stories.
- Add a new dog breed by creating a folder under assets/img/<breed-slug>/ with images.

Authentication Pages
- Files: login.php, signup.php
- Includes Email/Password form, Continue with Google, and Continue as guest (returns to the previous page when possible).

Project Structure (top-level)
- index.php ............... Homepage (slideshow + contact form)
- available-pets.php ...... Breeds listing page
- about.php ............... Our Team section
- reviews.php ............. Testimonials and Adoption Stories with modals
- contact.php ............. Full contact page
- contact-submit.php ...... PHP form handler
- includes/ ............... Shared header, footer, head, data helpers
- assets/ ................. CSS, JS, images (breeds, team, reviewers, stories, slide)
- tools/ .................. Maintenance PowerShell scripts

Maintenance Scripts (tools/)
- organize-breed-images.ps1 .... Arrange breed images per folder.
- cleanup-assets.ps1 ........... Remove unused/temporary assets (review carefully).
- cleanup-vendor.ps1 ........... Prune unused vendor files.
- minify-css.ps1 ............... Minify CSS for production builds.

Troubleshooting
- Email not sending: On local machines, PHP mail() may not be configured. Use SMTP with PHPMailer and valid credentials, or test on a host with mail enabled.
- Slideshow order: Ensure assets/img/mae.jpg exists and slide images are under assets/img/slide.
- Breeds dropdown shows extra folders: Confirm NON_PET_FOLDERS in includes/pets-data.php includes slide, slides, reviewers, team, stories.

Credits
- Base Template: Arsha by BootstrapMade – https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
- License: https://bootstrapmade.com/license/
- Vendors: Bootstrap, Bootstrap Icons, AOS, GLightbox (see assets/vendor/)

Changelog
- Customized template with slideshow, dynamic breeds, unified contact form + PHP handler, reviews/story avatars with modals, team bios/headshots, and basic auth pages.
