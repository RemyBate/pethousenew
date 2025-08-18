<?php
require_once __DIR__ . '/db.php';

function pupnest_register_user(string $name, string $email, string $password): int {
  pupnest_ensure_schema();
  $pdo = pupnest_get_pdo();
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
  $stmt->execute([$name, strtolower($email), $hash]);
  return (int)$pdo->lastInsertId();
}

function pupnest_find_user_by_email(string $email): ?array {
  $pdo = pupnest_get_pdo();
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([strtolower($email)]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function pupnest_verify_login(string $email, string $password): ?array {
  $user = pupnest_find_user_by_email($email);
  if (!$user) return null;
  if (!password_verify($password, $user['password_hash'])) return null;
  return $user;
}


