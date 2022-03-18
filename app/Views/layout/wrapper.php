<!DOCTYPE html>
<html dir="ltr" lang="en">

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
   <!-- This page plugin CSS -->
   <?= $this->renderSection("header") ?>
   <!-- Custom CSS -->
   <link href="<?= base_url("dist/css/style.min.css") ?>" rel="stylesheet">
</head>

<body>
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
   <!-- Main wrapper - style you can find in pages.scss -->
   <!-- ============================================================== -->
   <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <header class="topbar" data-navbarbg="skin6">
         <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header" data-logobg="skin6">
               <!-- This is for the sidebar toggle which is visible on mobile only -->
               <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
               <!-- ============================================================== -->
               <!-- Logo -->
               <!-- ============================================================== -->
               <div class="navbar-brand">
                  <!-- Logo icon -->
                  <a href="<?= base_url() ?>">
                     <b class="logo-icon">
                        <!-- Dark Logo icon -->
                        <img src="<?= base_url("assets/images/logo-icon.png") ?>" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="<?= base_url("assets/images/logo-icon.png") ?>" alt="homepage" class="light-logo" />
                     </b>
                     <!--End Logo icon -->
                     <!-- Logo text -->
                     <span class="logo-text h3 font-weight-bold mb-0" style="font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">App Laundry
                     </span>
                  </a>
               </div>
               <!-- ============================================================== -->
               <!-- End Logo -->
               <!-- ============================================================== -->
               <!-- ============================================================== -->
               <!-- Toggle which is visible on mobile only -->
               <!-- ============================================================== -->
               <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse justify-content-end" id="navbarSupportedContent">
               <!-- ============================================================== -->
               <!-- Right side toggle and nav items -->
               <!-- ============================================================== -->
               <ul class="navbar-nav float-right">
                  <!-- ============================================================== -->
                  <!-- User profile and search -->
                  <!-- ============================================================== -->
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="rounded-circle">
                           <i data-feather="user"></i>
                        </span>
                        <span class="ml-2 d-none d-lg-inline-block"><span>Hai,</span> <span class="text-dark"><?= session()->get("name") ?></span> <i data-feather="chevron-down" class="svg-icon"></i></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <a class="dropdown-item" href="<?= base_url("/profile") ?>"><i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                           Profile</a>
                        <a class="dropdown-item" href="<?= base_url('/logout') ?>"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                           Logout</a>
                     </div>
                  </li>
                  <!-- ============================================================== -->
                  <!-- User profile and search -->
                  <!-- ============================================================== -->
               </ul>
            </div>
         </nav>
      </header>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <aside class="left-sidebar" data-sidebarbg="skin6">
         <!-- Sidebar scroll-->
         <div class="scroll-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
               <ul id="sidebarnav">
                  <li class="sidebar-item <?= $nav_active === "dashboard" ? "selected" : null ?>"> <a class="sidebar-link sidebar-link <?= $nav_active === "dashboard" ? "active" : null ?>" href="<?= base_url() ?>" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
                  <?php if (session()->get('role') === "admin") : ?>
                     <li class="sidebar-item <?= $nav_active === "users" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "users" ? "active" : null ?>" href="<?= base_url("/users") ?>" aria-expanded="false"><i data-feather="user-check" class="feather-icon"></i><span class="hide-menu">Pengguna</span></a>
                     </li>
                  <?php endif; ?>
                  <li class="sidebar-item <?= $nav_active === "costumers" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "costumers" ? "active" : null ?>" href="<?= base_url("/costumers") ?>" aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span class="hide-menu">Pelanggan</span></a>
                  </li>
                  <li class="sidebar-item <?= $nav_active === "services" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "services" ? "active" : null ?>" href="<?= base_url("/services") ?>" aria-expanded="false"><i data-feather="shopping-bag" class="feather-icon"></i><span class="hide-menu">Layanan</span></a>
                  </li>
                  <li class="sidebar-item <?= $nav_active === "orders" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "orders" ? "active" : null ?>" href="<?= base_url("/orders") ?>" aria-expanded="false"><i data-feather="shopping-cart" class="feather-icon"></i><span class="hide-menu">Pesanan</span></a>
                  </li>
                  <li class="sidebar-item <?= $nav_active === "transactions" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "transactions" ? "active" : null ?>" href="<?= base_url("/transactions") ?>" aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i><span class="hide-menu">Transaksi</span></a>
                  </li>
                  <?php if (session()->get('role') === "admin") : ?>
                     <li class="sidebar-item <?= $nav_active === "report" ? "selected" : null ?>"> <a class="sidebar-link <?= $nav_active === "report" ? "active" : null ?>" href="<?= base_url("/report") ?>" aria-expanded="false"><i data-feather="file" class="feather-icon"></i><span class="hide-menu">Laporan</span></a>
                     </li>
                  <?php endif; ?>
                  <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="<?= base_url('/logout') ?>" aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span class="hide-menu">Logout</span></a></li>
               </ul>
            </nav>
            <!-- End Sidebar navigation -->
         </div>
         <!-- End Sidebar scroll-->
      </aside>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
         <!-- ============================================================== -->
         <!-- Bread crumb and right sidebar toggle -->
         <!-- ============================================================== -->
         <div class="page-breadcrumb p-3">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?= $title ?></h4>
            <div class="d-flex align-items-center">
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                     <?php if (isset($breadcrum)) : ?>
                        <li class="breadcrumb-item text-muted active" aria-current="page"><?= $breadcrum ?></li>
                     <?php else : ?>
                        <li class="breadcrumb-item"><a href="index.html" class="text-muted">Home</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page"><?= $title ?></li>
                     <?php endif; ?>
                  </ol>
               </nav>
            </div>
         </div>
         <!-- ============================================================== -->
         <!-- End Bread crumb and right sidebar toggle -->
         <!-- ============================================================== -->
         <div class="container-fluid p-3">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <?= $this->renderSection("content") ?>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
         </div>
         <!-- ============================================================== -->
         <!-- footer -->
         <!-- ============================================================== -->
         <footer class="footer text-center text-muted">
            All Rights Reserved by Adminmart. Designed and Developed by <a href="https://wrappixel.com">WrapPixel</a>.
         </footer>
         <!-- ============================================================== -->
         <!-- End footer -->
         <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
   </div>
   <!-- ============================================================== -->
   <!-- End Wrapper -->
   <!-- ============================================================== -->
   <!-- End Wrapper -->
   <!-- ============================================================== -->
   <!-- All Jquery -->
   <!-- ============================================================== -->
   <script src="<?= base_url("assets/libs/jquery/dist/jquery.min.js") ?>"></script>
   <!-- PopperJS -->
   <script src="<?= base_url("assets/libs/popper.js/dist/umd/popper.min.js") ?>"></script>
   <!-- Bootstrap tether Core JavaScript -->
   <script src="<?= base_url("assets/libs/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
   <!-- apps -->
   <script src="<?= base_url("dist/js/app-style-switcher.min.js") ?>"></script>
   <script src="<?= base_url("dist/js/feather.min.js") ?>"></script>
   <!-- slimscrollbar scrollbar JavaScript -->
   <script src="<?= base_url("assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js") ?>"></script>
   <!--Custom JavaScript -->
   <script src="<?= base_url("dist/js/custom.min.js") ?>"></script>
   <script>
      function formatRupiah(angka, prefix) {
         var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

         // tambahkan titik jika yang di input sudah menjadi angka ribuan
         if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
         }

         rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
         return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
      }
   </script>
   <!--This page plugins -->
   <?= $this->renderSection("footer") ?>
</body>

</html>