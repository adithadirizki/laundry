<?= $this->extend("layout/wrapper") ?>

<?= $this->section("header") ?>
<link href="<?= base_url("assets/libs/chartist/dist/chartist.min.css") ?>" rel="stylesheet">
<style>
   /* This selector overrides the points style on line charts. Points on line charts are actually just very short strokes. This allows you to customize even the point size in CSS */
   body .stats .ct-series-a .ct-point {
      /* Size of your points */
      stroke-width: 10px;
   }
</style>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="row">
   <?php if (session()->get('role') === "admin") : ?>
      <div class="col-6 col-md-3">
         <div class="card border-right">
            <div class="card-body">
               <div class="d-flex d-lg-flex d-md-block align-items-center">
                  <div>
                     <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium"><?= $data->total_users ?? 0 ?></h2>
                     </div>
                     <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengguna</h6>
                  </div>
                  <div class="ml-auto mt-md-3 mt-lg-0">
                     <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endif; ?>
   <div class="col-6 <?= session()->get('role') === "admin" ? "col-md-3" : "col-md-4" ?>">
      <div class="card border-right">
         <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
               <div>
                  <div class="d-inline-flex align-items-center">
                     <h2 class="text-dark mb-1 font-weight-medium"><?= $data->total_costumers ?? 0 ?></h2>
                  </div>
                  <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pelanggan</h6>
               </div>
               <div class="ml-auto mt-md-3 mt-lg-0">
                  <span class="opacity-7 text-muted"><i data-feather="user"></i></span>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-6 <?= session()->get('role') === "admin" ? "col-md-3" : "col-md-4" ?>">
      <div class="card border-right">
         <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
               <div>
                  <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><?= $data->total_orders ?? 0 ?></h2>
                  <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pesanan
                  </h6>
               </div>
               <div class="ml-auto mt-md-3 mt-lg-0">
                  <span class="opacity-7 text-muted"><i data-feather="shopping-cart"></i></span>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-6 <?= session()->get('role') === "admin" ? "col-md-3" : "col-md-4" ?>">
      <div class="card border-right">
         <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
               <div>
                  <div class="d-inline-flex align-items-center">
                     <h2 class="text-dark mb-1 font-weight-medium"><?= $data->total_transactions ?? 0 ?></h2>
                  </div>
                  <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Transaksi</h6>
               </div>
               <div class="ml-auto mt-md-3 mt-lg-0">
                  <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php if (session()->get('role') === "admin") : ?>
   <div class="row">
      <div class="col-md-6 col-lg-4">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                           <div class="d-inline-flex align-items-center">
                              <h2 class="text-dark mb-1 font-weight-medium"><?= $data->total_income ?? 0 ?></h2>
                           </div>
                           <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                           <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <h4 class="card-title">Pendapatan</h4>
                     <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-lg-8">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-start">
                  <h4 class="card-title mb-0">Statistik Pesanan </h4>
               </div>
               <div class="stats ct-charts position-relative" style="height: 315px;"></div>
            </div>
         </div>
      </div>
   </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script src="<?= base_url("assets/libs/chartist/dist/chartist.min.js") ?>"></script>
<script src="<?= base_url("assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js") ?>"></script>
<script>
   $(document).ready(function() {

      var data = {
         labels: <?= json_encode($data->statistic_transactions['months']) ?>,
         series: [
            <?= json_encode($data->statistic_transactions['data']) ?>
         ],
      };

      var options = {
         axisX: {
            showGrid: false
         },
         axisY: {
            labelInterpolationFnc: function(value) {
               return (value / 1000) + 'K';
            }
         },
         low: 0,
         fullWidth: true,
         chartPadding: {
            top: 15,
            bottom: 25,
         },
         plugins: [
            Chartist.plugins.tooltip({
               transformTooltipTextFnc: (value) => {
                  return (value / 1000) + "K";
               }
            })
         ],
      };

      new Chartist.Bar('.net-income', data, options);

      var chart = new Chartist.Line('.stats', {
         labels: <?= json_encode($data->statistic_orders['days']) ?>,
         series: [
            <?= json_encode($data->statistic_orders['data']) ?>
         ]
      }, {
         low: 0,
         fullWidth: true,
         axisY: {
            labelInterpolationFnc: function(value) {
               return Number(value).toFixed();
            }
         },
         chartPadding: {
            top: 15,
            bottom: 25,
         },
         plugins: [
            Chartist.plugins.tooltip()
         ],
      });
   })
</script>
<?= $this->endSection() ?>