<?= $this->extend("layout/wrapper") ?>

<?= $this->section("content") ?>
<div class="row">
   <div class="col-12">
      <?php
      $session = session();

      $errors = $session->getFlashdata("errors");
      $message = $session->getFlashdata("message");
      $message_type = $session->getFlashdata("message_type");

      if ($message)
         echo '<div class="alert alert-' . $message_type . ' alert-dismissible border-0 fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . '</div>';
      ?>
   </div>
   <div class="col-lg-8">
      <div class="card">
         <div class="card-body">
            <a href="<?= base_url("/transaction-print/$data->trx_code") ?>" class="btn btn-outline-primary mb-4"><i data-feather="printer" class="mr-2"></i>
               Print</a>
            <div class="table-responsive">
               <table class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Layanan</th>
                        <th>Harga Satuan</th>
                        <th class="text-center">Kuantitas</th>
                        <th>Total Harga</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     helper('number');
                     $total_pay = 0;
                     foreach ($order_items as $key => $value) {
                        $total_pay += $value->price * $value->quantity;
                     ?>
                        <tr>
                           <td><?= $key + 1 ?></td>
                           <td><?= $value->service_name ?></td>
                           <td><?= number_to_currency($value->price, 'IDR', 'id-ID') ?></td>
                           <td class="text-center"><?= $value->quantity ?></td>
                           <td class="total-price"><?= number_to_currency($value->price * $value->quantity, 'IDR', 'id-ID') ?></td>
                        </tr>
                     <?php
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Detail Pembayaran</h3>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Pelanggan :</span>
               <span class="h5 font-weight-bold"><?= $data->costumer_name ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Operator :</span>
               <span class="h5 font-weight-bold"><?= $data->name ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Diskon :</span>
               <span class="h5 font-weight-bold"><?= number_to_currency($data->discount, 'IDR', 'id-ID') ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Biaya Tambahan :</span>
               <span class="h5 font-weight-bold"><?= number_to_currency($data->additional_cost, 'IDR', 'id-ID') ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Total Bayar :</span>
               <?php $total_pay = $total_pay + $data->additional_cost - $data->discount; ?>
               <span class="h5 font-weight-bold"><?= number_to_currency($total_pay, 'IDR', 'id-ID') ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Uang Tunai :</span>
               <span class="h5 font-weight-bold"><?= number_to_currency($data->cash, 'IDR', 'id-ID') ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-1">
               <span>Kembalian :</span>
               <span class="h5 font-weight-bold"><?= number_to_currency($data->cash - $total_pay, 'IDR', 'id-ID') ?></span>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer") ?>
<script>
   $(document).ready(function() {
      function sum_total_pay() {
         var total_pay = 0;
         $('table tbody .total-price').each((index, el) => {
            var total_price = $(el).text().replace(/\D/g, '');
            total_pay += Number(total_price);
         });
         var discount = Number($('#discount').val().replace(/\D/g, ''));
         var additional_cost = Number($('#additional_cost').val().replace(/\D/g, ''));
         $('#total_pay').text(formatRupiah(total_pay + additional_cost - discount, 'Rp'));
         $('input[name=total_pay]').val(total_pay + additional_cost - discount);
      }

      function sum_change_money() {
         var cash = $('#cash').val().replace(/\D/g, '');
         var total_pay = $('#total_pay').text().replace(/\D/g, '');

         var change_money = Number(cash) - Number(total_pay);

         $('input[name=change]').val(change_money);
         $('#change_money').text(formatRupiah(change_money, 'Rp'));
         if (change_money < 0) {
            $('#change_money').removeClass("text-success");
            $('#change_money').addClass("text-danger");
            $('#change_money').text('-' + $('#change_money').text());
         } else if (change_money > 0) {
            $('#change_money').removeClass("text-danger");
            $('#change_money').addClass("text-success");
         } else {
            $('#change_money').removeClass("text-danger");
            $('#change_money').removeClass("text-success");
         }
      }

      $('#form-pay-order').trigger('reset');

      // price format to only numeric
      $(document).on("submit", "#form-pay-order", function(e) {
         var discount = $(this).find("input[name=discount]");
         var additional_cost = $(this).find("input[name=additional_cost]");
         var cash = $(this).find("input[name=cash]");

         discount.val(discount.val().replace(/\D/g, ''));
         additional_cost.val(additional_cost.val().replace(/\D/g, ''));
         cash.val(cash.val().replace(/\D/g, ''));
      })

      // load sum total pay
      $(document).on("keyup", "#discount, #additional_cost", function() {
         $(this).val(formatRupiah($(this).val()));

         sum_total_pay();
      })

      // load change money
      $(document).on("keyup", "#cash", function() {
         $(this).val(formatRupiah($(this).val()));

         sum_change_money();
      })
   })
</script>
<?= $this->endSection() ?>