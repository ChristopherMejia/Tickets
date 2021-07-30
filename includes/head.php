<?php
session_start();
include "config/config.php";

if (!isset($_SESSION['user_id']) && $_SESSION['user_id'] == null) {
    header("location: login");
}

$role = $global->getRol($_SESSION['user_id']);
$maintenance = $configuration->maintenance($role['id']);
$dataConfiguration = $configuration->getConfiguration();
$dataUserCustom = $custom->getUserCustom($_SESSION['user_id']);


if ($maintenance) {
    header("location: maintenance");
}

?>
<?php
$id = $_SESSION['user_id'];
$data_user = $global->dataUser($id);

foreach ($data_user as $u) {
    $user_id = $u['id'];
    $username = $u['username'];
    $name = $u['name'];
    $email = $u['email'];
    $profile_pic = $u['profile_pic'];
    $role = $u['role'];
    $company = $u['company'];
    $company_id = $u['company_id'];
    $created_at = $u['created_at'];
}

$subCompanies = $global->subCompanies($company_id);
$allSubCompanies = $global->allSubCompanies($company_id);

?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $dataUserCustom['theme'] ?>-theme">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href=favicon.ico rel="shortcut icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title . " " . $name; ?> </title>

    <!-- Bootstrap -->
    <link href="css/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Reenie+Beanie:regular" rel="stylesheet" type="text/css">

    <!-- NProgress -->
    <link href="css/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="css/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="css/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="css/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>


    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"/>


    <!-- bootstrap-daterangepicker -->
    <link href="css/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>

    <!-- Custom Theme Style -->
    <link href="css/admin.css" rel="stylesheet">
    <link href="css/custom.min.css" rel="stylesheet">

    <!-- MICSS button[type="file"] -->
    <link rel="stylesheet" href="css/micss.css">

    <!-- Dropzone -->
    <link rel="stylesheet" href="css/dropzone/basic.css">

    <!-- sweet alert -->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <!-- Jquery file Upload -->
    <link rel="stylesheet" href="css/fancybox/jquery.fancybox.min.css"/>

    <!-- Animate.css -->
    <link href="css/animate.css/animate.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<body class="nav-sm">
<?php include "includes/top.php" ?>
<div class="loader">
    <span class="loader1 block-loader"></span>
    <span class="loader2 block-loader"></span>
    <span class="loader3 block-loader"></span>
</div>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="dashboard" class="site_title">
                        <img src="images/t-icon.png" class="img-responsive" alt="">
                        <span>Ticketsystem</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_info">
                        <span>Bienvenido,</span>
                        <h2><?php echo $name; ?></h2>
                        <input type="hidden" id="user_id" value="<?php echo $user_id ?>">

                    </div>
                </div>
                <!-- /menu profile quick info -->

