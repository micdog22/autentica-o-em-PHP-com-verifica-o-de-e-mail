<?php include 'layout_header.php'; $user = require_login(); ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card card-sh">
      <div class="card-body">
        <h4 class="mb-2">Bem-vindo, <?php echo clean($user['name']); ?> 👋</h4>
        <p class="text-muted">Seu e-mail: <strong><?php echo clean($user['email']); ?></strong></p>
        <hr>
        <p class="mb-0">Este é um dashboard simples. Use este projeto como base para o seu app.</p>
      </div>
    </div>
  </div>
</div>
<?php include 'layout_footer.php'; ?>
