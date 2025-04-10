<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ADMINPANEL | BASE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons 
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->

  <!-- Tempusdominus Bootstrap 4 -->
  <!--link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"-->
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/bootstrap/js/bootstrap.min.js">

  <!-- iCheck
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">-->
  <!-- JQVMap
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/jqvmap/jqvmap.min.css">-->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>dist/css/ADMINLTE.min.css">
  <!-- overlayScrollbars 
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">-->
  <!-- Daterange picker
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/daterangepicker/daterangepicker.css">-->
  <!-- summernote 
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.css">-->


  <link rel="stylesheet" href="views/components/sweet_alert/sweetalert2.min.css">
  <link rel="stylesheet" href="views/css/template.css">



  <!-- jQuery -->
  <script src="<?php echo ADMINLTE; ?>plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo ADMINLTE; ?>plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->

  <script src="<?php echo ADMINLTE; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS 
  <script src="<?php echo ADMINLTE; ?>plugins/chart.js/Chart.min.js"></script>-->
  <!-- Sparkline 
  <script src="<?php echo ADMINLTE; ?>plugins/sparklines/sparkline.js"></script>-->
  <!-- JQVMap
  <script src="<?php echo ADMINLTE; ?>plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="<?php echo ADMINLTE; ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>-->
  <!-- jQuery Knob Chart 
  <script src="<?php echo ADMINLTE; ?>plugins/jquery-knob/jquery.knob.min.js"></script>-->
  <!-- daterangepicker 
  <script src="<?php echo ADMINLTE; ?>plugins/moment/moment.min.js"></script>
  <script src="<?php echo ADMINLTE; ?>plugins/daterangepicker/daterangepicker.js"></script>-->
  <!-- Tempusdominus Bootstrap 4 
  <script src="<?php echo ADMINLTE; ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>-->
  <!-- Summernote
  <script src="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.js"></script> -->
  <!-- overlayScrollbars 
  <script src="<?php echo ADMINLTE; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>-->
  <!-- ADMINLTE App -->
  <script src="<?php echo ADMINLTE; ?>dist/js/ADMINLTE.js"></script>
  <!-- ADMINLTE for demo purposes
  <script src="<?php echo ADMINLTE; ?>dist/js/demo.js"></script>-->
  <!-- ADMINLTE dashboard demo (This is only for demo purposes)
  <script src="<?php echo ADMINLTE; ?>dist/js/pages/dashboard.js"></script>-->

  <link href="views/components/dataTables/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        <link href="views/components/dataTables/css/buttons.bootstrap.css" rel="stylesheet" />
        <link href="views/components/dataTables/css/responsive.bootstrap.min.css" rel="stylesheet" />


        <script src="views/components/dataTables/jquery.dataTables.min.js"></script>
        <script src="views/components/dataTables/dataTables.bootstrap.min.js"></script>
        <script src="views/components/dataTables/dataTables.buttons.min.js"></script>
        <script src="views/components/dataTables/buttons.bootstrap.min.js"></script>
        <script src="views/components/dataTables/buttons.colVis.js"></script>
        <script src="views/components/dataTables/buttons.html5.js"></script>
        <script src="views/components/dataTables/buttons.print.js"></script>
        <script src="views/components/dataTables/dataTables.responsive.min.js"></script>
        <script src="views/components/dataTables/responsive.bootstrap.js"></script>

        <script src="views/js/datatable_templates.js"></script>


  <script src="views/components/sweet_alert/sweetalert2.all.min.js"></script>
  <script src="views/js/repository.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?php echo ADMINLTE; ?>dist/img/ADMINLTELogo.png" alt="ADMINLTELogo" height="60" width="60">
    </div>
