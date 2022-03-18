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
      <select name="status" id="status" class="form-control w-auto mb-3">
         <option value="" selected>-- Semua Status --</option>
         <option value="0">Menunggu Pembayaran</option>
         <option value="1">Sudah Dibayar</option>
         <option value="2">Pesanan Dibatalkan</option>
      </select>
      <div class="table-responsive">
         <table id="orders-datatable" class="table table-striped table-bordered no-wrap w-100">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Kode Unik</th>
                  <th>Status</th>
                  <th>Pelanggan</th>
                  <th>Operator</th>
                  <th>Tgl. Pesanan</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>Kode Unik</th>
                  <th>Status</th>
                  <th>Pelanggan</th>
                  <th>Operator</th>
                  <th>Tgl. Pesanan</th>
                  <th>Aksi</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<!-- Modal Delete Order -->
<div id="modal-cancel-order" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
         <div class="modal-body p-4">
            <div class="text-center">
               <h4 class="font-weight-bold mt-2">Batalkan Pesanan</h4>
               <form id="form-cancel-order" action="<?= base_url("/order-cancel") ?>" method="POST">
                  <input type="hidden" name="id">
                  <p class="mt-3">Anda yakin ingin membatalkan pesanan ini?</p>
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
<!-- End Modal Delete Order -->
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

      var ordersDatatable = $("#orders-datatable").DataTable({
         responsive: true,
         processing: true,
         serverSide: true,
         dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
         buttons: [{
               text: feather.icons['plus'].toSvg({
                  class: 'mr-2',
                  width: 17,
                  height: 17
               }) + 'Buat Pesanan',
               className: "btn btn-outline-primary",
               action: function() {
                  window.location.href = "<?= base_url("/order-add") ?>";
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
            url: "<?= base_url("/order-list") ?>",
            type: "post",
            dataType: "json",
            data: (data) => {
               data.filter = {
                  status: $('#status').val()
               };
               return data;
            }
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
               data: "order_code"
            },
            {
               data: "order_status",
               mRender: function(order_status) {
                  if (order_status == 0) {
                     return `<div class="badge badge-pill badge-warning">Menunggu Pembayaran</div>`;
                  } else if (order_status == 1) {
                     return `<div class="badge badge-pill badge-success">Sudah Dibayar</div>`;
                  } else if (order_status == 2) {
                     return `<div class="badge badge-pill badge-danger">Pesanan Dibatalkan</div>`;
                  }
               },
               className: "text-center"
            },
            {
               data: "costumer_name",
               mRender: function(costumer_name, row, data) {
                  return `<a href="<?= base_url('/costumer-detail') ?>/${data.ordered_by}" class="text-primary">${costumer_name}</a>`;
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
               data: "id",
               mRender: function(id, row, data) {
                  var button = '';
                  if (data.order_status == 0) {
                     button += `<a href="<?= base_url('/order-detail') ?>/${id}" class="btn btn-sm btn-edit-order text-primary" data-tooltip title="Detail Pesanan">${feather.icons['shopping-cart'].toSvg({
                        width: 18,
                        height: 18
                     })}</a><button class="btn btn-sm btn-cancel-order text-danger" data-id="${id}" data-toggle="modal" data-target="#modal-cancel-order" data-tooltip title="Batalkan Pesanan">${feather.icons['x-circle'].toSvg({
                     width: 18,
                     height: 18
                  })}</button>`;
                  } else if (data.order_status == 1) {
                     button += `<a href="<?= base_url('/transaction-detail') ?>/${data.trx_code}" class="btn btn-sm btn-edit-order text-primary" data-tooltip title="Detail Pembayaran">${feather.icons['file-text'].toSvg({
                        width: 18,
                        height: 18
                     })}</a>`;
                  }
                  return button;
               },
               className: "text-center",
               orderable: false
            },
         ]
      })

      $(document).on("change", "#status", function() {
         ordersDatatable.ajax.reload();
      })

      $(document).on("submit", "#form-add-order, #form-edit-order", function(e) {
         var order_price = $(this).find("input[name=order_price]");
         order_price.val(Number(order_price.val().replace(/\D/g, '')));
      })

      $(document).on('keyup', 'input[name=order_price]', function() {
         var value = $(this).val().replace(/[^\d]/g, '').toString();
         var price = formatRupiah(value);
         $(this).val(price)
      })

      $(document).on("click", ".btn-edit-order", function() {
         var data = $(this).data();
         var form_edit_order = $("#form-edit-order");
         form_edit_order.find("input[name=id]").val(data.id)
         form_edit_order.find("input[name=order_name]").val(data.order_name)
         form_edit_order.find("input[name=order_price]").val(formatRupiah(data.order_price.toString()))
         form_edit_order.find("input[name=unit_price]").val(data.unit_price)
      })

      $(document).on("click", ".btn-cancel-order", function() {
         var id = $(this).data("id");
         $('#form-cancel-order [name=id]').val(id);
      })

      setTimeout(() => {
         $("#alert-notification").alert("close");
      }, 5000);
   })
</script>
<?= $this->endSection() ?>