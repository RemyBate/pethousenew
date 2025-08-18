<?php
// Simple form handler to send contact form submissions via email
// Collects: name, email, phone (optional), city, country, address (optional), message

// Security: Basic sanitization
function sanitize($value) {
    return trim(filter_var($value ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$recipient = 'breedbabiesdaily@inbox.ru';

$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$city = sanitize($_POST['city'] ?? '');
$country = sanitize($_POST['country'] ?? '');
$address = sanitize($_POST['address'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// Required fields validation
if ($name === '' || $email === '' || $city === '' || $country === '' || $message === '') {
    http_response_code(400);
    echo 'Please complete all required fields.';
    exit;
}

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Please provide a valid email address.';
    exit;
}

$subject = 'New Contact Form Submission';

$bodyLines = [
    "You have a new contact form submission:",
    '',
    "Name: $name",
    "Email: $email",
    ($phone !== '' ? "Phone: $phone" : null),
    "City: $city",
    "Country: $country",
    ($address !== '' ? "Address: $address" : null),
    '',
    "Message:",
    $message,
];

$body = implode("\r\n", array_values(array_filter($bodyLines, fn($l) => $l !== null)));

// Build headers
require_once __DIR__ . '/includes/mailer.php';
$success = pupnest_send_mail($recipient, 'PupNest Admin', $subject, $body, $email, $name);

if ($success) {
    // Simple thank-you page
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Thank You</title>';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container"><div class="alert alert-success mt-4">Thank you! Your message has been sent successfully.</div>';
    echo '<a class="btn btn-primary" href="index.php#contact">Back to Home</a> ';
    echo '<a class="btn btn-outline-secondary" href="contact.php">Go to Contact Page</a></div>';
    echo '</body></html>';
} else {
    http_response_code(500);
    echo 'Sorry, we could not send your message right now.';
}

?>


