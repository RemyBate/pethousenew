<?php
// Database connection helper for PupNest
// Adjust credentials as needed for your local/hosted environment.

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'pupnest');
define('DB_USER', 'root');
define('DB_PASS', '');

function pupnest_get_pdo(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) {
    return $pdo;
  }
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];
  $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
  return $pdo;
}

// Ensure schema exists. For local convenience; on production, run SQL migrations instead.
function pupnest_ensure_schema(): void {
  $pdo = pupnest_get_pdo();
  $pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
}


