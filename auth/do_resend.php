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
$db->prepare("UPDATE users SET verify_token=?, updated_at=? WHERE id=?")->execute([$token, date('c'), $u['id']]);

$link = app_url('verify.php');
$html = file_get_contents(__DIR__.'/../templates/verify.html');
$html = str_replace(['{{name}}','{{token}}','{{email}}','{{link}}'], [htmlspecialchars($u['name']), $token, $email, $link], $html);
send_mail($email, 'Seu novo código - MicAuth', $html);

redirect('../verify.php');
