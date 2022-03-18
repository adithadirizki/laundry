<?= $this->extend("layout/wrapper") ?>

<?= $this->section("header") ?>
<link rel="stylesheet" href="<?= base_url('/dist/css/select2.min.css') ?>">
<style>
   .select2-container--default .select2-selection--single {
      height: calc(1.5em + .75rem + 2px);
      padding: .375rem .75rem;
      border: 1px solid #e9ecef;
   }

   .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 6px;
   }

   .select2-container .select2-selection--single .select2-selection__rendered {
      padding: 0;
   }
</style>
<?= $this->endSection() ?>

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
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <form id="form-add-order" action="<?= base_url("/order-add") ?>" method="POST">
               <h3 class="mb-4">Buat Pesanan</h3>
               <div class="form-group">
                  <label for="ordered_by">Pelanggan <span class="text-danger">*</span></label>
                  <select name="ordered_by" id="ordered_by" required></select>
                  <small class="text-danger"><?= $errors['ordered_by'] ?? null ?></small>
               </div>
               <div class="row align-items-end" id="template-add-item">
                  <div class="w-100"></div>
                  <div class="col-sm-5 col-md-12 col-lg-3">
                     <div class="form-group">
                        <label>Layanan <span class="text-danger">*</span></label>
                        <select name="services[id][]" class="service_id" required></select>
                     </div>
                  </div>
                  <div class="col-sm-4 col-md-12 col-lg-3">
                     <div class="form-group">
                        <label>Harga Satuan <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                           </div>
                           <input type="text" name="services[price][]" class="form-control" min="0" placeholder="Harga Satuan" readonly required>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-3 col-md-12 col-lg-2">
                     <div class="form-group">
                        <label>Kuantitas <span class="text-danger">*</span></label>
                        <input type="number" name="services[quantity][]" class="form-control" min="1" placeholder="Kuantitas" required>
                     </div>
                  </div>
                  <div class="col-sm-5 col-md-12 col-lg-3">
                     <div class="form-group">
                        <label>Total Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                           </div>
                           <input type="text" name="total_price[]" class="form-control" placeholder="Total Harga" disabled>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1 col-md-12 col-lg-1">
                     <button type="button" class="btn btn-danger delete-form-item mb-3"><i data-feather="trash" class="svg-icon"></i></button>
                  </div>
               </div>
               <button type="button" class="btn btn-primary" id="add-form-item"><i data-feather="plus" class="svg-icon"></i></button>
               <hr>
               <div class="text-right">
                  <button type="submit" class="btn btn-primary">Simpan pesanan</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer") ?>
<script src="<?= base_url('/dist/js/select2.full.min.js') ?>"></script>
<script>
   $(document).ready(function() {
      var clone_template = $("#template-add-item").html();

      $("#ordered_by").select2({
         placeholder: "Pelanggan",
         width: '100%',
         allowClear: true,
         ajax: {
            url: "<?= base_url("/costumer-list") ?>",
            type: "post",
            dataType: "json",
            delay: 250,
            cache: true,
            data: function(params) {
               var query = {
                  start: params.page || 0,
                  length: 10,
                  search: {
                     value: params.term || ""
                  },
                  columns: [{
                     data: "id"
                  }],
                  order: [{
                     column: 0,
                     dir: "desc"
                  }]
               }

               // Query parameters will be ?search=[term]&page=[page]
               return query;
            },
            processResults: function(data, params) {
               params.page = params.page || 0;

               return {
                  results: data.data.map((value) => {
                     return {
                        id: value.id,
                        text: value.costumer_name
                     }
                  }),
                  pagination: {
                     more: ((params.page + 1) * 10) < data.recordsTotal
                  }
               };
            },
         }
      })

      var select2_service = {
         placeholder: "Layanan",
         width: '100%',
         ajax: {
            url: "<?= base_url("/service-list") ?>",
            type: "post",
            dataType: "json",
            delay: 250,
            cache: true,
            data: function(params) {
               var query = {
                  start: params.page || 0,
                  length: 10,
                  search: {
                     value: params.term || ""
                  },
                  columns: [{
                     data: "id"
                  }],
                  order: [{
                     column: 0,
                     dir: "desc"
                  }]
               }

               // Query parameters will be ?search=[term]&page=[page]
               return query;
            },
            processResults: function(data, params) {
               params.page = params.page || 0;

               return {
                  results: data.data.map((value) => {
                     value.service_name = $('<p></p>').html(value.service_name).text();

                     return {
                        id: value.id,
                        text: value.service_name,
                        service_price: value.service_price,
                        unit_price: value.unit_price
                     }
                  }),
                  pagination: {
                     more: ((params.page + 1) * 10) < data.recordsTotal
                  }
               };
            },
         },
         templateSelection: (data, container) => {
            $(data.element).attr('data-service_price', data.service_price);
            $(data.element).attr('data-unit_price', data.unit_price);
            return data.text;
         }
      }

      $(".service_id").select2(select2_service);

      function sum_total_pay() {
         var total_pay = 0;
         $('#template-add-item input[name="total_price[]"]').each((index, el) => {
            var total_price = $(el).val().replace(/\D/g, '');
            total_pay += Number(total_price);
         });
         $('#total-pay').text(formatRupiah(total_pay));
      }

      // price format to only numeric
      $(document).on("submit", "#form-add-order", function(e) {
         var service_price = $(this).find("input[name='services[price][]']");
         service_price.each((index, el) => {
            $(el).val(Number($(el).val().replace(/\D/g, '')));
         })
      })

      // auto fill form
      $(document).on("change", ".service_id", function() {
         var index = $(this).parent().index();
         var data = $(this).find(":selected").data();
         var price = $(this).parents().parents().next();
         var quantity = price.next();
         var total_price = quantity.next();

         price.find('input').val(formatRupiah(data.service_price));
         total_price.find('input').val(formatRupiah(data.service_price));
         quantity.find('input').val(1);

         sum_total_pay();
      })

      // handle quantity
      $(document).on("keyup change", "input[name='services[quantity][]']", function() {
         var price = $(this).parents().parents().prev();
         var quantity = price.next();
         var total_price = quantity.next();

         price = price.find('input').val().replace(/\D/g, '');
         quantity = $(this).val();

         console.log(quantity);

         total_price.find('input').val(formatRupiah(price * quantity));

         sum_total_pay();
      })

      // add item / service
      $(document).on("click", "#add-form-item", function() {
         $(".service_id").select2("destroy");
         $("#template-add-item").append(clone_template);
         $(".service_id").select2(select2_service);
      })

      // delete item / service
      $(document).on("click", ".delete-form-item", function(e) {
         var end = $(this).parent().index();
         var start = end - 4;

         $("#template-add-item").children().each((index, el) => {
            if (index >= start && index <= end) el.remove()
         })

         sum_total_pay();
      })
   })
</script>
<?= $this->endSection() ?>