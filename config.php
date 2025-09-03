<?php
// config.php — bootstrap simples
declare(strict_types=1);
session_start();

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'America/Sao_Paulo');

// Carrega .env (mínimo viável)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
  foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    [$k,$v] = array_pad(explode('=', $line, 2), 2, '');
    $_ENV[trim($k)] = trim($v);
    putenv(trim($k).'='.trim($v));
  }
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/mailer.php';

// cria tabelas on-boot
migrate();

// cookie seguro
ini_set('session.cookie_httponly', '1');
if (!empty($_SERVER['HTTPS'])) ini_set('session.cookie_secure', '1');
