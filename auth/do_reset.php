<?php
require_once __DIR__ . '/../config.php';
rate_limit(); verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$token = trim($_POST['token'] ?? '');
$pass = $_POST['password'] ?? '';
if (strlen($pass) < 6) exit('Senha muito curta.');

$db = pdo();
$stmt = $db->prepare("SELECT id FROM users WHERE email=?");
$stmt->execute([$email]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$u) exit('Conta não encontrada.');

$stmt = $db->prepare("SELECT id, expires_at FROM password_resets WHERE user_id=? AND token=? ORDER BY id DESC LIMIT 1");
$stmt->execute([$u['id'], $token]);
$pr = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pr || new DateTime() > new DateTime($pr['expires_at'])) exit('Token inválido ou expirado.');

$db->prepare("UPDATE users SET password_hash=?, updated_at=? WHERE id=?")
  ->execute([password_hash($pass, PASSWORD_BCRYPT), date('c'), $u['id']]);
$db->prepare("DELETE FROM password_resets WHERE id=?")->execute([$pr['id']]);

redirect('../login.php');
