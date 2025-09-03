<?php require_once __DIR__.'/config.php'; ?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MicAuth-PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom fixed-top">
  <div class="container">
    <a class="navbar-brand brand" href="index.php">MicAuth-PHP</a>
    <div class="d-flex gap-2">
      <?php if (!empty($_SESSION['user'])): ?>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Sair</a>
      <?php else: ?>
        <a class="btn btn-outline-primary btn-sm" href="login.php">Entrar</a>
        <a class="btn btn-primary btn-sm" href="register.php">Registrar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container mt-5 pt-4">
