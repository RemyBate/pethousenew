<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = (string)($_POST['password'] ?? '');
$returnTo = trim($_POST['return'] ?? 'index.php');

if ($email === '' || $password === '') {
  header('Location: login.php?status=error&reason=missing');
  exit;
}

$user = pupnest_verify_login($email, $password);
if (!$user) {
  header('Location: login.php?status=error&reason=invalid');
  exit;
}

// Minimal session login
session_start();
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];

header('Location: ' . ($returnTo !== '' ? $returnTo : 'index.php'));
exit;


