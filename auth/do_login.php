<?php
require_once __DIR__ . '/../config.php';
rate_limit(); verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$pass = $_POST['password'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) exit('E-mail inválido.');

$db = pdo();
// rate por tentativa
$db->prepare("INSERT INTO login_attempts (email, ip, created_at) VALUES (?,?,?)")
  ->execute([$email, $_SERVER['REMOTE_ADDR'] ?? '', date('c')]);

// janela: 10 min, máx 8 tentativas por IP
$ten = (new DateTime('-10 minutes'))->format('c');
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$tries = $db->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip=? AND created_at>=?");
$tries->execute([$ip, $ten]);
if ((int)$tries->fetchColumn() > 8) { http_response_code(429); exit('Muitas tentativas. Tente novamente mais tarde.'); }

$stmt = $db->prepare("SELECT id, name, email, password_hash, email_verified_at FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || !password_verify($pass, $user['password_hash'])) exit('Credenciais inválidas.');

if (empty($user['email_verified_at'])) exit('Verifique seu e-mail antes de entrar.');

session_regenerate_id(true);
$_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email']];
redirect('../index.php');
