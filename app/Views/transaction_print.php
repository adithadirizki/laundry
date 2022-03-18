<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Transaksi #<?= $data->trx_code ?></title>
   <!-- Custom CSS -->
   <link href="<?= base_url("dist/css/style.min.css") ?>" rel="stylesheet">
   <style>
      table td,
      table th {
         padding: .375rem .5rem;
      }
   </style>
</head>

<body class="h6" onload="window.print()">
   <div class="row">
      <div class="col-lg-4 m-auto">
         <div class="card">
            <div class="p-3">
               <div class="h3 text-center font-weight-bold">App Laundry</div>
               <!-- <div class="text-center">Jl. Soekarno-Hatta No. 911</div> -->
               <hr>
               <div class="d-flex justify-content-between mb-2">Kode Pesanan:
                  <span class="text-right"><?= $data->order_code ?></span>
               </div>
               <div class="d-flex justify-content-between mb-2">Kode Trx:
                  <span class="text-right">#<?= $data->trx_code ?></span>
               </div>
               <div class="d-flex justify-content-between mb-2">Tgl. Transaksi:
                  <span class="text-right"><?= $data->created_at ?></span>
               </div>
            </div>
            <div class="px-2">
               <table class="h6 w-100">
                  <tbody>
                     <?php helper('number');
                     $total_pay = $data->additional_cost - $data->discount;
                     foreach ($order_items as $value) :
                        $total_pay += $value->price * $value->quantity;
                     ?>
                        <tr>
                           <td><?= $value->service_name ?></td>
                           <td class="text-left"><?= number_to_currency($value->price, 'IDR', 'id-ID') ?></td>
                           <td class="text-center"><?= $value->quantity ?></td>
                           <td class="text-right"><?= number_to_currency($value->price * $value->quantity, 'IDR', 'id-ID') ?></td>
                        </tr>
                     <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                     <tr>
                        <td colspan="3" class="text-right">Diskon</td>
                        <td class="text-right"><?= number_to_currency($data->discount, 'IDR', 'id-ID') ?></td>
                     </tr>
                     <tr>
                        <td colspan="3" class="text-right">Biaya Tambahan</td>
                        <td class="text-right"><?= number_to_currency($data->additional_cost, 'IDR', 'id-ID') ?></td>
                     </tr>
                     <tr>
                        <td colspan="3" class="text-right">Total Bayar</td>
                        <td class="text-right"><?= number_to_currency($total_pay, 'IDR', 'id-ID') ?></td>
                     </tr>
                     <tr>
                        <td colspan="3" class="text-right">Uang Tunai</td>
                        <td class="text-right"><?= number_to_currency($data->cash, 'IDR', 'id-ID') ?></td>
                     </tr>
                     <tr>
                        <td colspan="3" class="text-right">Kembalian</td>
                        <td class="text-right"><?= number_to_currency($data->cash - $total_pay, 'IDR', 'id-ID') ?></td>
                     </tr>
                  </tfoot>
               </table>
            </div>
            <small class="font-italic text-center p-3">*Terima kasih sudah mencuci di App Laundry*</small>
         </div>
      </div>
   </div>
</body>

</html>