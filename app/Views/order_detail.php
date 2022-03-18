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
   <div class="col-md-8">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-4">Layanan yang dipesan</h3>
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
   <div class="col-md-4">
      <div class="card">
         <div class="card-body">
            <form action="<?= base_url('/order-pay') ?>" id="form-pay-order" method="POST">
               <h3 class="mb-4">Pembayaran</h3>
               <div class="form-group">
                  <label for="order_code">Kode Pesanan <span class="text-danger">*</span></label>
                  <input type="hidden" name="order_id" value="<?= $data->id ?>" required>
                  <input type="text" name="order_code" id="order_code" class="form-control" value="<?= $data->order_code ?>" disabled>
               </div>
               <div class="form-group">
                  <label for="costumer_name">Pelanggan <span class="text-danger">*</span></label>
                  <input type="text" name="costumer_name" id="costumer_name" class="form-control" value="<?= $data->costumer_name ?>" required disabled>
               </div>
               <div class="form-group">
                  <label for="discount">Diskon <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="discount" id="discount" class="form-control" value="<?= old('discount') ?? 0 ?>" placeholder="Diskon" required>
                  </div>
                  <small class="text-danger"><?= $errors['discount'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <label for="additional_cost">Biaya Tambahan <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="additional_cost" id="additional_cost" class="form-control" value="<?= old('additional_cost') ?? 0 ?>" placeholder="Biaya Tambahan" required>
                  </div>
                  <small class="text-danger"><?= $errors['additional_cost'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <div>Total Bayar:
                     <span class="font-weight-bold" id="total_pay">
                        <?= number_to_currency(old('total_pay') ?? $total_pay, 'IDR', 'id-ID') ?>
                     </span>
                  </div>
               </div>
               <div class="form-group">
                  <label for="cash">Uang Tunai <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                     </div>
                     <input type="text" name="cash" id="cash" class="form-control" value="<?= old('cash') ?? 0 ?>" placeholder="Uang Tunai" required>
                  </div>
                  <small class="text-danger"><?= $errors['cash'] ?? null ?></small>
               </div>
               <div class="form-group">
                  <div>Kembalian:
                     <span class="font-weight-bold text-danger" id="change_money">
                        <?= number_to_currency(old('change') ?? -$total_pay, 'IDR', 'id-ID') ?>
                     </span>
                  </div>
               </div>
               <button type="submit" class="btn btn-block btn-primary">Konfirmasi Pembayaran</button>
            </form>
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