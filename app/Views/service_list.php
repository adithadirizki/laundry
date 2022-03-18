<?= $this->extend("layout/wrapper") ?>

<?= $this->section("header") ?>
<link href="<?= base_url("assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css") ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
   <div class="card-body">
      <?php
      $session = session();

      $errors = $session->getFlashdata("errors");
      $message = $session->getFlashdata("message");
      $message_type = $session->getFlashdata("message_type");

      if ($message)
         echo '<div id="alert-notification" class="alert alert-' . $message_type . ' alert-dismissible border-0 fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . '</div>';
      ?>
      <div class="table-responsive">
         <table id="services-datatable" class="table table-striped table-bordered no-wrap w-100">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Layanan</th>
                  <th>Harga</th>
                  <th>Tgl. Dibuat</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>Layanan</th>
                  <th>Harga</th>
                  <th>Tgl. Dibuat</th>
                  <th>Aksi</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<!-- Modal Add Service -->
<div id="modal-add-service" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-add-serviceLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <form id="form-add-service" action="<?= base_url("/service-add") ?>" method="POST">
            <div class="modal-header modal-colored-header bg-primary">
               <h4 class="modal-title" id="modal-add-serviceLabel">Tambah Layanan
               </h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="add_service_name">Nama Layanan <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="service_name" id="add_service_name" maxlength="50" value="<?= old('service_name') ?>" placeholder="Cuci Pakaian" required>
                  <small class="text-danger"><?= $errors['service_name'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="add_service_price">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="service_price" id="add_service_price" class="form-control" value="<?= old('service_price') ?>" placeholder="10.000" required>
                  </div>
                  <small class="text-danger"><?= $errors['service_price'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="add_unit_price">Satuan Harga <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="unit_price" id="add_unit_price" maxlength="15" value="<?= old('unit_price') ?>" placeholder="kg" required>
                  <small class="text-danger"><?= $errors['unit_price'] ?? null ?></small>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Tambahkan</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- End Modal Add Service -->
<!-- Modal Edit Service -->
<div id="modal-edit-service" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-edit-serviceLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <form id="form-edit-service" action="<?= base_url("/service-edit") ?>" method="POST">
            <div class="modal-header modal-colored-header bg-primary">
               <h4 class="modal-title" id="modal-edit-serviceLabel">Edit Layanan
               </h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <input class="form-control" type="hidden" name="id" value="<?= old('id') ?>" required>
               <div class="form-group">
                  <label for="edit_service_name">Nama Layanan <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="service_name" id="edit_service_name" maxlength="50" value="<?= old('service_name') ?>" placeholder="Cuci Pakaian" required>
                  <small class="text-danger"><?= $errors['service_name'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="edit_service_price">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="service_price" id="edit_service_price" class="form-control" value="<?= old('service_price') ?>" placeholder="10.000" required>
                  </div>
                  <small class="text-danger"><?= $errors['service_price'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="edit_unit_price">Satuan Harga <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="unit_price" id="edit_unit_price" maxlength="15" value="<?= old('unit_price') ?>" placeholder="kg" required>
                  <small class="text-danger"><?= $errors['unit_price'] ?? null ?></small>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- End Modal Edit Service -->
<!-- Modal Delete Service -->
<div id="modal-delete-service" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
         <div class="modal-body p-4">
            <div class="text-center">
               <h4 class="font-weight-bold mt-2">Hapus Layanan</h4>
               <form id="form-delete-service" action="<?= base_url("/service-delete") ?>" method="POST">
                  <input type="hidden" name="id">
                  <p class="mt-3">Anda yakin ingin menghapus layanan ini?</p>
                  <div class="d-flex align-items-center justify-content-between my-2">
                     <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                     <button type="submit" class="btn btn-success">Yakin</button>
                  </div>
               </form>
            </div>
         </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div>
<!-- End Modal Delete Service -->
<?= $this->endSection() ?>

<?= $this->section("footer") ?>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/dataTables.buttons.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/buttons.html5.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/buttons.print.min.js") ?>"></script>
<script>
   $(document).ready(function() {
      $('body').tooltip({
         selector: "[data-tooltip]"
      })

      var servicesDatatable = $("#services-datatable").DataTable({
         responsive: true,
         processing: true,
         serverSide: true,
         dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
         buttons: [{
               text: feather.icons['plus'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'Tambah Layanan',
               className: "btn btn-outline-primary <?= session()->get('role') === "admin" ? null : "d-none" ?>",
               action: function() {
                  $('#modal-add-service').modal('show');
               },
            }, {
               extend: 'print',
               text: feather.icons['printer'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'Print',
               className: "btn btn-outline-primary",
               autoPrint: false,
               exportOptions: {
                  columns: [0, 1, 2, 3]
               }
            },
            {
               extend: 'csv',
               text: feather.icons['file-text'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'CSV',
               className: "btn btn-outline-primary",
               exportOptions: {
                  columns: [0, 1, 2, 3]
               }
            }
         ],
         ajax: {
            url: "<?= base_url("/service-list") ?>",
            type: "post",
            dataType: "json"
         },
         order: [
            [0, 'desc']
         ],
         columns: [{
               data: "id",
               mRender: function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               className: "text-center"
            },
            {
               data: "service_name"
            },
            {
               data: null,
               className: "text-center",
               mRender: function(data) {
                  return `${formatRupiah(data.service_price, "Rp")}/${data.unit_price}`;
               }
            },
            {
               data: "created_at",
               className: "text-center"
            },
            {
               data: "id",
               mRender: function(id, row, data) {
                  return `<button class="btn btn-sm btn-edit-service text-primary" data-tooltip title="Edit" data-id="${id}" data-service_name="${data.service_name}" data-service_price="${data.service_price}" data-unit_price="${data.unit_price}" data-toggle="modal" data-target="#modal-edit-service">${feather.icons['edit'].toSvg({
                     width: 18,
                     height: 18
                  })}</button><button class="btn btn-sm btn-delete-service text-danger" data-tooltip title="Hapus" data-id="${id}" data-toggle="modal" data-target="#modal-delete-service">${feather.icons['trash'].toSvg({
                     width: 18,
                     height: 18
                  })}</button>`;
               },
               className: "text-center",
               orderable: false,
               visible: <?= session()->get('role') === "admin" ? "true" : "false" ?>
            },
         ],
      })

      $(document).on("submit", "#form-add-service, #form-edit-service", function(e) {
         var service_price = $(this).find("input[name=service_price]");
         service_price.val(Number(service_price.val().replace(/\D/g, '')));
      })

      $(document).on('keyup', 'input[name=service_price]', function() {
         var value = $(this).val().replace(/[^\d]/g, '').toString();
         var price = formatRupiah(value);
         $(this).val(price)
      })

      $(document).on("click", ".btn-edit-service", function() {
         var data = $(this).data();
         var form_edit_service = $("#form-edit-service");
         form_edit_service.find("input[name=id]").val(data.id)
         form_edit_service.find("input[name=service_name]").val(data.service_name)
         form_edit_service.find("input[name=service_price]").val(formatRupiah(data.service_price.toString()))
         form_edit_service.find("input[name=unit_price]").val(data.unit_price)
      })

      $(document).on("click", ".btn-delete-service", function() {
         var id = $(this).data("id");
         $('#form-delete-service [name=id]').val(id);
      })

      setTimeout(() => {
         $("#alert-notification").alert("close");
      }, 5000);
   })
</script>
<?= $this->endSection() ?>