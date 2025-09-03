<?php
// db.php — conexão SQLite e migrações
declare(strict_types=1);

function pdo(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;
  $dbDir = __DIR__ . '/data';
  if (!is_dir($dbDir)) mkdir($dbDir, 0777, true);
  $dsn = 'sqlite:' . $dbDir . '/micauth.sqlite';
  $pdo = new PDO($dsn);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('PRAGMA foreign_keys = ON;');
  return $pdo;
}

function migrate(): void {
  $db = pdo();
  $db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,
    password_hash TEXT NOT NULL,
    email_verified_at TEXT DEFAULT NULL,
    verify_token TEXT DEFAULT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL
  );");
  $db->exec("CREATE TABLE IF NOT EXISTS password_resets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token TEXT NOT NULL,
    expires_at TEXT NOT NULL,
    created_at TEXT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
  );");
  $db->exec("CREATE TABLE IF NOT EXISTS login_attempts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT,
    ip TEXT NOT NULL,
    created_at TEXT NOT NULL
  );");
}
