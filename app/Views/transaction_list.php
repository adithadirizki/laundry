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
         echo '<div id="alert-notification" class="alert alert-' . $message_type . ' alert-dismissible btransaction-0 fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . '</div>';
      ?>
      <div class="table-responsive">
         <table id="transactions-datatable" class="table table-striped table-btransactioned no-wrap w-100">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Kode Trx</th>
                  <th>Kode Pesanan</th>
                  <th>Pelanggan</th>
                  <th>Operator</th>
                  <th>Tgl. Transaksi</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>Kode Trx</th>
                  <th>Kode Pesanan</th>
                  <th>Pelanggan</th>
                  <th>Operator</th>
                  <th>Tgl. Transaksi</th>
                  <th>Aksi</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer") ?>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/dataTables.buttons.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/buttons.html5.min.js") ?>"></script>
<script src="<?= base_url("assets/extra-libs/datatables.net/js/buttons.print.min.js") ?>"></script>
<script>
   $(document).ready(function() {
      // tooltip
      $("body").tooltip({
         selector: "[data-tooltip]"
      });

      var transactionsDatatable = $("#transactions-datatable").DataTable({
         responsive: true,
         processing: true,
         serverSide: true,
         dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
         buttons: [{
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
            url: "<?= base_url("/transaction-list") ?>",
            type: "post",
            dataType: "json",
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
               data: "trx_code"
            },
            {
               data: "order_code"
            },
            {
               data: "costumer_name",
               mRender: function(costumer_name, row, data) {
                  return `<a href="<?= base_url('/costumer-detail') ?>/${data.transactioned_by}" class="text-primary">${costumer_name}</a>`;
               },
            },
            {
               data: "name",
               mRender: function(name, row, data) {
                  return `<a href="<?= base_url('/user-detail') ?>/${data.created_by}" class="text-primary">${name}</a>`;
               },
            },
            {
               data: "created_at",
               className: "text-center"
            },
            {
               data: "trx_code",
               mRender: function(trx_code, row, data) {
                  return `<a href="<?= base_url('/transaction-detail') ?>/${trx_code}" class="btn btn-sm btn-edit-transaction text-primary" data-tooltip title="Detail Transaksi">${feather.icons['file-text'].toSvg({
                     width: 18,
                     height: 18
                  })}</a>`;
               },
               className: "text-center",
               transactionable: false
            },
         ]
      })

      setTimeout(() => {
         $("#alert-notification").alert("close");
      }, 5000);
   })
</script>
<?= $this->endSection() ?>