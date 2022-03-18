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
            <h3 class="mb-4">Tambah Pelanggan</h3>
            <form id="form-add-costumer" action="<?= base_url("/costumer-add") ?>" method="POST">
               <div class="form-group">
                  <label for="costumer_name">Nama <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="costumer_name" id="costumer_name" maxlength="50" value="<?= old('costumer_name') ?>" placeholder="John Doe" required>
                  <small class="text-danger"><?= $errors['costumer_name'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="costumer_contact">No. Telp</label>
                  <input class="form-control" type="text" name="costumer_contact" id="costumer_contact" value="<?= old('costumer_contact') ?>" placeholder="0812xxxxxxxx">
                  <small class="text-danger"><?= $errors['costumer_contact'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="role">Jenis Kelamin <span class="text-danger">*</span></label>
                  <div class="custom-control custom-radio">
                     <input type="radio" id="male" name="costumer_gender" class="custom-control-input" value="male" <?= old('costumer_gender') === "male" ? "checked" : null ?> required>
                     <label class="custom-control-label" for="male">Laki - laki</label>
                  </div>
                  <div class="custom-control custom-radio">
                     <input type="radio" id="female" name="costumer_gender" class="custom-control-input" value="female" <?= old('costumer_gender') === "female" ? "checked" : null ?> required>
                     <label class="custom-control-label" for="female">Perempuan</label>
                  </div>
                  <small class="text-danger"><?= $errors['costumer_gender'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="costumer_address">Alamat</label>
                  <textarea class="form-control" name="costumer_address" id="costumer_address" cols="30" rows="2" placeholder="Jl. Jend Sudirman"><?= old('costumer_address') ?></textarea>
                  <small class="text-danger"><?= $errors['costumer_address'] ?? null ?></small>
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