<?php include 'layout_header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card card-sh">
      <div class="card-body">
        <h4 class="mb-3">Reenviar verificação</h4>
        <form method="post" action="auth/do_resend.php">
          <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-warning" type="submit">Reenviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'layout_footer.php'; ?>
