<?php
require_once __DIR__ . '/../config.php';
rate_limit(); verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) exit('E-mail inválido.');

$db = pdo();
$stmt = $db->prepare("SELECT id, name FROM users WHERE email=?");
$stmt->execute([$email]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$u) exit('Conta não encontrada.');

$token = bin2hex(random_bytes(16));
$exp = (new DateTime('+30 minutes'))->format('c');
$db->prepare("INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?,?,?,?)")
  ->execute([$u['id'], $token, $exp, date('c')]);

$html = file_get_contents(__DIR__.'/../templates/reset.html');
$link = app_url('reset.php');
$html = str_replace(['{{name}}','{{token}}','{{email}}','{{link}}','{{expires}}'],
  [htmlspecialchars($u['name']), $token, $email, $link, $exp], $html);
send_mail($email, 'Redefinição de senha - MicAuth', $html);

redirect('../reset.php');
