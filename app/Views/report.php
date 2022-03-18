<?= $this->extend("layout/wrapper") ?>

<?= $this->section("content") ?>
<div class="row">
   <div class="col-md-6 m-auto">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Export laporan transaksi</h3>
            <form id="form-export-transaction" action="<?= base_url("/export") ?>" method="POST">
               <div class="form-group">
                  <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                  <input class="form-control" type="date" name="start_date" id="start_date" maxlength="50" value="<?= old("start_date") ?>" required>
                  <small class="text-danger"><?= $errors['start_date'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="end_date">Tanggal Akhir <span class="text-danger">*</span></label>
                  <input class="form-control" type="date" name="end_date" id="end_date" value="<?= old("end_date") ?>" required>
                  <small class="text-danger"><?= $errors['end_date'] ?? null ?></small>
               </div>
               <div class="text-right">
                  <button class="btn btn-primary" type="submit">Export</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>