<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard </title>
<!-- Set the base URL relative to the admin folder -->
<!-- head.php -->
<?php
$baseUrl = '/admin/';  // Set this to reference the 'admin' directory
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

// Check or initialize $scriptDir
$scriptDir = $scriptDir ?? __DIR__;
$scriptDir = rtrim($scriptDir, '/\\');
$rootPath = $scriptDir;
$rooturl = $protocol . $host;
?>

<!-- Include CSS Files -->
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/feather/feather.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/mdi/css/materialdesignicons.min.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/ti-icons/css/themify-icons.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/font-awesome/css/font-awesome.min.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/typicons/typicons.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/simple-line-icons/css/simple-line-icons.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/css/vendor.bundle.base.css"/>
<link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css"/>


    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"> -->
    <link rel="stylesheet" type="text/css"  href="<?php echo $baseUrl; ?>assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet"  href="<?php echo $baseUrl; ?>assets/css/style.css?v=<?php echo time(); ?>">
    <!-- endinject -->
    <link rel="shortcut icon"  href="<?php echo $baseUrl; ?>assets/images/short-url-fav-icon.png" />

  </head>