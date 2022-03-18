<!DOCTYPE html>
<html dir="ltr">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="App Laundry">
   <meta name="author" content="Adit Hadi Rizki">
   <!-- Favicon icon -->
   <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url("assets/images/favicon.png") ?>">
   <title><?= $title ?> - <?= env("app.name") ?></title>
   <!-- Custom CSS -->
   <link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body>
   <div class="main-wrapper">
      <!-- ============================================================== -->
      <!-- Preloader - style you can find in spinners.css -->
      <!-- ============================================================== -->
      <div class="preloader">
         <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
         </div>
      </div>
      <!-- ============================================================== -->
      <!-- Preloader - style you can find in spinners.css -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Login box.scss -->
      <!-- ============================================================== -->
      <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
         <div class="auth-box row">
            <div class="col-sm-5 d-none d-sm-block modal-bg-img" style="background-image: url(assets/images/bg-login.jpg);">
            </div>
            <div class="col-sm-7 bg-white">
               <div class="p-3">
                  <h2 class="mt-3 text-center">Sign In</h2>
                  <p class="text-center">Enter your username and password to access webpanel.</p>
                  <form class="mt-4" method="POST" action="<?= base_url('/login') ?>">
                     <div class="row">
                        <?php
                        $session = session();

                        $errors = $session->getFlashdata("errors");
                        $message = $session->getFlashdata("message");
                        $message_type = $session->getFlashdata("message_type");

                        echo '<div class="col-12"><div class="alert alert-' . $message_type . ' alert-dismissible bg-' . $message_type . ' text-white border-0 fade show" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">×</span>
                           </button>
                           ' . $message . '</div></div>';
                        ?>

                        <div class="col-lg-12">
                           <div class="form-group">
                              <label class="text-dark" for="username">Username</label>
                              <input class="form-control" id="username" type="text" name="username" maxlength="25" placeholder="Enter your username" autofocus value="<?= old("username") ?>">
                              <small class="text-danger"><?= $errors["username"] ?? null ?></small>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <label class="text-dark" for="password">Password</label>
                              <input class="form-control" id="password" type="password" name="password" minlength="6" placeholder="Enter your password" value="<?= old("password") ?>">
                              <small class="text-danger"><?= $errors["password"] ?? null ?></small>
                           </div>
                        </div>
                        <div class="col-lg-12 text-center">
                           <button type="submit" class="btn btn-block btn-primary">Sign In</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- ============================================================== -->
      <!-- Login box.scss -->
      <!-- ============================================================== -->
   </div>
   <!-- ============================================================== -->
   <!-- All Required js -->
   <!-- ============================================================== -->
   <script src="assets/libs/jquery/dist/jquery.min.js "></script>
   <!-- ============================================================== -->
   <!-- This page plugin js -->
   <!-- ============================================================== -->
   <script>
      $(".preloader ").fadeOut();
   </script>
</body>

</html>