<?= $this->extend("layout/wrapper") ?>

<?= $this->section("content") ?>
<div class="row">
   <div class="col-md-6">
      <?php
      $session = session();

      $errors = $session->getFlashdata("errors");
      $message = $session->getFlashdata("message");
      $message_type = $session->getFlashdata("message_type");

      if ($message)
         echo '<div class="alert alert-' . $message_type . ' alert-dismissible border-0 fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . '</div>';
      ?>
   </div>
   <div class="w-100"></div>
   <div class="col-md-6 m-auto">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Tambah Pengguna</h3>
            <form id="form-add-user" action="<?= base_url("/user-add") ?>" method="POST">
               <div class="form-group">
                  <label for="username">Username <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="username" id="username" maxlength="25" value="<?= old("username") ?>" placeholder="johndoe" required>
                  <small class="text-danger"><?= $errors['username'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="name">Nama <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="name" id="name" maxlength="50" value="<?= old("name") ?>" placeholder="John Doe" required>
                  <small class="text-danger"><?= $errors['name'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="role">Role <span class="text-danger">*</span></label>
                  <select class="form-control" name="role" id="role" required>
                     <option value="admin">Admin</option>
                     <option value="staff">Staff</option>
                  </select>
                  <small class="text-danger"><?= $errors['role'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="password">Password <span class="text-danger">*</span></label>
                  <input class="form-control" type="password" name="password" id="password" minlength="6" value="<?= old("password") ?>" placeholder="*****" required>
                  <small class="text-danger"><?= $errors['password'] ?? null ?></small>
               </div>
               <div class="text-right">
                  <button class="btn btn-primary" type="submit">Tambahkan</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer") ?>
<script>
   $(document).ready(function() {
      $("#form-add-user [name=role]").val("<?= old("role") ?? "admin" ?>").change();
   })
</script>
<?= $this->endSection() ?>