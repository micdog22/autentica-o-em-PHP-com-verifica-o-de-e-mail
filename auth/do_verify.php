<?php
require_once __DIR__ . '/../config.php';
rate_limit(); verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$token = trim($_POST['token'] ?? '');

$db = pdo();
$stmt = $db->prepare("SELECT id, verify_token FROM users WHERE email=?");
$stmt->execute([$email]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$u || !$token || !hash_equals($u['verify_token'] ?? '', $token)) {
  exit('Token inválido.');
}
$db->prepare("UPDATE users SET email_verified_at=?, verify_token=NULL, updated_at=? WHERE id=?")
  ->execute([date('c'), date('c'), $u['id']]);
redirect('../login.php');
