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
   <div class="col-md-6">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Edit Pengguna</h3>
            <form id="form-edit-user" action="<?= session()->get('role') === "admin" ? base_url("/user-edit") : base_url("/profile") ?>" method="POST">
               <?php if (session()->get('role') === "admin") : ?>
                  <input class="form-control" type="hidden" name="id" id="id" value="<?= $data['id'] ?>">
               <?php endif; ?>
               <div class="form-group">
                  <label for="username">Username <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="username" id="username" value="<?= old("username") ?? $data['username'] ?>" disabled>
               </div>
               <div class="form-group">
                  <label for="name">Nama <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="name" id="name" maxlength="50" value="<?= old("name") ?? $data['name'] ?>" required>
                  <small class="text-danger"><?= $errors['name'] ?? null ?></small>
               </div>
               <?php if (session()->get('role') === "admin") : ?>
                  <div class="form-group">
                     <label for="role">Role <span class="text-danger">*</span></label>
                     <select class="form-control" name="role" id="role" required>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                     </select>
                     <small class="text-danger"><?= $errors['role'] ?? null ?></small>
                  </div>
               <?php endif; ?>
               <div class="form-group">
                  <label for="created_at">Tgl. Terdaftar <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="created_at" id="created_at" value="<?= $data['created_at'] ?>" disabled>
               </div>
               <div class="text-right">
                  <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Ganti Password</h3>
            <form id="form-edit-password" action="<?= session()->get('role') === "admin" ? base_url("/user-edit-password") : base_url("/profile-edit-password") ?>" method="POST">
               <?php if (session()->get('role') === "admin") : ?>
                  <input class="form-control" type="hidden" name="id" id="id" value="<?= $data['id'] ?>">
               <?php endif; ?>
               <div class="form-group">
                  <label for="password">Password Baru</label>
                  <input class="form-control" type="text" name="password" id="password" minlength="6" placeholder="Minimal 6 karakter." required>
                  <small class="text-danger"><?= $errors['password'] ?? null ?></small>
               </div>
               <div class="text-right">
                  <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
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
      $("#form-edit-user [name=role]").val("<?= old("role") ?? $data['role'] ?>").change();
   })
</script>
<?= $this->endSection() ?>