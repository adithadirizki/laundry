<?= $this->extend("layout/wrapper") ?>

<?= $this->section("header") ?>
<link href="<?= base_url("assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css") ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
   <div class="card-body">
      <?php
      $session = session();

      $message = $session->getFlashdata("message");
      $message_type = $session->getFlashdata("message_type");

      if ($message)
         echo '<div id="alert-notification" class="alert alert-' . $message_type . ' alert-dismissible border-0 fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . '</div>';
      ?>
      <div class="table-responsive">
         <table id="costumers-datatable" class="table table-striped table-bordered no-wrap w-100">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Kontak</th>
                  <th>L/P</th>
                  <th>Alamat</th>
                  <th>Tgl. Terdaftar</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Kontak</th>
                  <th>L/P</th>
                  <th>Alamat</th>
                  <th>Tgl. Terdaftar</th>
                  <th>Aksi</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<div id="modal-delete-costumer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
         <div class="modal-body p-4">
            <div class="text-center">
               <h4 class="font-weight-bold mt-2">Hapus Pengguna</h4>
               <form id="form-delete-costumer" action="<?= base_url("/costumer-delete") ?>" method="POST">
                  <input type="hidden" name="id">
                  <p class="mt-3">Anda yakin ingin menghapus pengguna ini?</p>
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

      var costumersDatatable = $("#costumers-datatable").DataTable({
         responsive: true,
         processing: true,
         serverSide: true,
         dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
         buttons: [{
               text: feather.icons['plus'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'Tambah Pelanggan',
               className: "btn btn-outline-primary",
               action: function() {
                  window.location.href = "<?= base_url("/costumer-add") ?>";
               }
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
                  columns: [0, 1, 2, 3, 4, 5]
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
                  columns: [0, 1, 2, 3, 4, 5]
               }
            }
         ],
         ajax: {
            url: "<?= base_url("/costumer-list") ?>",
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
               data: "costumer_name"
            },
            {
               data: "costumer_contact"
            },
            {
               data: "costumer_gender",
               className: "text-center",
               mRender: function(costumer_gender) {
                  return costumer_gender === "male" ? "L" : "P"
               }
            },
            {
               data: "costumer_address",
               className: "text-center"
            },
            {
               data: "created_at",
               className: "text-center"
            },
            {
               data: "id",
               mRender: function(id, row, data) {
                  <?php if (session()->get('role') === "admin") : ?>
                     return `<a href="<?= base_url("/costumer-detail") ?>/${id}" class="btn btn-sm text-primary" data-tooltip title="Edit">${feather.icons['edit'].toSvg({
                        width: 18,
                        height: 18
                     })}</a></button><button class="btn btn-sm btn-delete-costumer text-danger" data-tooltip title="Hapus" data-id="${id}" data-toggle="modal" data-target="#modal-delete-costumer">${feather.icons['trash'].toSvg({
                        width: 18,
                        height: 18
                     })}</button>`;
                  <?php else : ?>
                     return `<a href="<?= base_url("/costumer-detail") ?>/${id}" class="btn btn-sm text-primary" data-tooltip title="Edit">${feather.icons['edit'].toSvg({
                        width: 18,
                        height: 18
                     })}</a>`;
                  <?php endif; ?>
               },
               className: "text-center",
               orderable: false,
            },
         ],
      })

      $(document).on("click", ".btn-delete-costumer", function() {
         var id = $(this).data("id");
         $('#form-delete-costumer [name=id]').val(id);
      })

      setTimeout(() => {
         $("#alert-notification").alert("close");
      }, 5000);
   })
</script>
<?= $this->endSection() ?>