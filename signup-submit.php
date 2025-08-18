<?php
// Basic signup handler: validates input, sends a confirmation email, then redirects back with success status.
// For production, replace mail() with SMTP/PHPMailer for reliability.

declare(strict_types=1);

// Tighten error handling in development; consider adjusting in production
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: signup.php');
  exit;
}

function sanitize(string $value): string {
  return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

$name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';
$returnTo = isset($_POST['return']) ? trim($_POST['return']) : 'index.php';

if ($name === '' || $email === '' || $password === '') {
  header('Location: signup.php?status=error&reason=missing');
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: signup.php?status=error&reason=email');
  exit;
}

// Persist user to database (creates table on first run)
require_once __DIR__ . '/includes/auth.php';
try {
  // Avoid duplicate email
  if (pupnest_find_user_by_email($email)) {
    header('Location: login.php?status=exists&return=' . urlencode($returnTo));
    exit;
  }
  pupnest_register_user($name, $email, $password);
} catch (Throwable $e) {
  header('Location: signup.php?status=error&reason=db');
  exit;
}

// Send confirmation email (SMTP if configured, else mail())
require_once __DIR__ . '/includes/mailer.php';
$subject = 'PupNest – Signup Confirmation';
$message = "Hello {$name},\r\n\r\n" .
  "Welcome to PupNest! Your account has been created successfully.\r\n" .
  "You can now sign in and start exploring available dogs, adoption stories, and more.\r\n\r\n" .
  "If you did not initiate this signup, please ignore this email.\r\n\r\n" .
  "– PupNest Team";

// Best-effort send; do not block UX if email fails on localhost
pupnest_send_mail($email, $name, $subject, $message);

// Build redirect back to signup with success and name for greeting
$qs = http_build_query([
  'status' => 'success',
  'name' => $name,
  'return' => $returnTo,
]);

header('Location: signup.php?' . $qs);
exit;


