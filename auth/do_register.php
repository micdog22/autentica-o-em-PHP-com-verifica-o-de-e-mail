<?php
require_once __DIR__ . '/../config.php';
rate_limit(); verify_csrf();

$name = trim($_POST['name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$pass = $_POST['password'] ?? '';

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
  exit('Dados inválidos.');
}

$db = pdo();
$stmt = $db->prepare("SELECT id FROM users WHERE email=?");
$stmt->execute([$email]);
if ($stmt->fetch()) exit('E-mail já registrado.');

$hash = password_hash($pass, PASSWORD_BCRYPT);
$token = bin2hex(random_bytes(16));
$now = date('c');
$stmt = $db->prepare("INSERT INTO users (email, name, password_hash, verify_token, created_at, updated_at) VALUES (?,?,?,?,?,?)");
$stmt->execute([$email, $name, $hash, $token, $now, $now]);

$link = app_url('verify.php');
$html = file_get_contents(__DIR__.'/../templates/verify.html');
$html = str_replace(['{{name}}','{{token}}','{{email}}','{{link}}'], [htmlspecialchars($name), $token, $email, $link], $html);
send_mail($email, 'Verifique seu e-mail - MicAuth', $html);

redirect('../login.php');
