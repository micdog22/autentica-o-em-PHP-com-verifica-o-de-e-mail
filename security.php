<?php
// security.php — CSRF, rate limiting e helpers
declare(strict_types=1);

function csrf_token(): string {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function verify_csrf(): void {
  $ok = isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
  if (!$ok) { http_response_code(419); exit('CSRF token inválido'); }
}

function rate_limit_key(): string {
  $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  return 'rl_' . md5($ip);
}
// 5 req / 15s por IP
function rate_limit(): void {
  $key = rate_limit_key();
  $now = time();
  $window = 15;
  $max = 5;
  $bucket = $_SESSION[$key] ?? [];
  $bucket = array_filter($bucket, fn($t) => $t > $now - $window);
  if (count($bucket) >= $max) {
    http_response_code(429);
    exit('Muitas requisições, aguarde alguns segundos.');
  }
  $bucket[] = $now;
  $_SESSION[$key] = $bucket;
}

function clean($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect(string $to): never { header("Location: $to"); exit; }
function app_url(string $path=''): string {
  $base = getenv('APP_URL') ?: (($_SERVER['REQUEST_SCHEME'] ?? 'http').'://'.($_SERVER['HTTP_HOST'] ?? 'localhost'));
  return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function require_login(): array {
  if (empty($_SESSION['user'])) redirect('login.php');
  return $_SESSION['user'];
}
