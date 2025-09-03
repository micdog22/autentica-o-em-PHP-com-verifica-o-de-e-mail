<?php include 'layout_header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card card-sh">
      <div class="card-body">
        <h4 class="mb-3">Entrar</h4>
        <form method="post" action="auth/do_login.php" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
          <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <a href="forgot.php" class="text-small">Esqueci minha senha</a>
            <button class="btn btn-primary" type="submit">Entrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'layout_footer.php'; ?>
