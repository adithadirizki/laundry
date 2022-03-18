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
         <table id="users-datatable" class="table table-striped table-bordered no-wrap w-100">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Username</th>
                  <th>Nama</th>
                  <th>Role</th>
                  <th>Tgl. Terdaftar</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>Username</th>
                  <th>Nama</th>
                  <th>Role</th>
                  <th>Tgl. Terdaftar</th>
                  <th>Aksi</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<div id="modal-delete-user" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
         <div class="modal-body p-4">
            <div class="text-center">
               <h4 class="font-weight-bold mt-2">Hapus Pengguna</h4>
               <form id="form-delete-user" action="<?= base_url("/user-delete") ?>" method="POST">
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

      var usersDatatable = $("#users-datatable").DataTable({
         responsive: true,
         processing: true,
         serverSide: true,
         dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
         buttons: [{
               text: feather.icons['plus'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'Tambah Pengguna',
               className: "btn btn-outline-primary",
               action: function() {
                  window.location.href = "<?= base_url("/user-add") ?>";
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
                  columns: [0, 1, 2, 3, 4]
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
                  columns: [0, 1, 2, 3, 4]
               }
            }
         ],
         ajax: {
            url: "<?= base_url("/user-list") ?>",
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
               data: "username"
            },
            {
               data: "name"
            },
            {
               data: "role",
               className: "text-center"
            },
            {
               data: "created_at",
               className: "text-center"
            },
            {
               data: "id",
               mRender: function(id, row, data) {
                  return `<a href="<?= base_url("/user-detail") ?>/${id}" class="btn btn-sm text-primary" data-tooltip title="Edit">${feather.icons['edit'].toSvg({
                     width: 18,
                     height: 18
                  })}</a></button><button class="btn btn-sm btn-delete-user text-danger" data-tooltip title="Hapus" data-id="${id}" data-toggle="modal" data-target="#modal-delete-user">${feather.icons['trash'].toSvg({
                     width: 18,
                     height: 18
                  })}</button>`;
               },
               className: "text-center",
               orderable: false
            },
         ]
      })

      $(document).on("click", ".btn-delete-user", function() {
         var id = $(this).data("id");
         $('#form-delete-user [name=id]').val(id);
      })

      setTimeout(() => {
         $("#alert-notification").alert("close");
      }, 5000);
   })
</script>
<?= $this->endSection() ?>