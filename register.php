<?php include 'layout_header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card card-sh">
      <div class="card-body">
        <h4 class="mb-3">Registrar</h4>
        <form method="post" action="auth/do_register.php" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
          <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" minlength="6" required>
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Criar conta</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'layout_footer.php'; ?>
