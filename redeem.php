<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');

$user = new userController();
$session = new Database();
$url = $_SERVER["REQUEST_URI"];
if (preg_match("/\.php/", $url)) {
    header("Location: " . preg_replace("/\.php/", " ", $url));
}

if ($session->getSession() === false) {
    header('location: login');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>MCSpam | Minecraft botting service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MCSpam is a service that allows you to bot target mc servers, Doesn't matter of they're premium. You can bot them with ease."
          name="description"/>
    <meta content="Rayan, Navix" name="author"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/jquery.datatables.min.css" rel="stylesheet" type="text/css">
    <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>


    <link href="assets/css/toaster.min.css" rel="stylesheet" type="text/css"/>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/userController.js"></script>

</head>

<body>

<!-- Top Bar Start -->
<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">

        <!-- LOGO -->
        <div class="topbar-left">
            <a href="index" class="logo">
                <span>
                    <h2 style="margin-top: 6%;">MCSpam</h2>
                </span>
            </a>
        </div>

        <ul class="list-unstyled topbar-nav float-right mb-0">

            <li class="dropdown">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#"
                   role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="mdi mdi-bell-outline nav-icon"></i>
                    <span class="badge badge-danger badge-pill noti-icon-badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                    <!-- item-->
                    <h6 class="dropdown-item-text">
                        Notifications (0)
                    </h6>
                    <div class="slimscroll notification-list">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-danger"><i class="mdi mdi-message"></i></div>
                            <p class="notify-details">New Message received
                                <small class="text-muted">You have 87 unread messages</small>
                            </p>
                        </a>
                    </div>
                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#"
                   role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/users/user-1.jpg" alt="profile-user" class="rounded-circle"/>
                    <span class="ml-1 nav-user-name hidden-sm"> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="dripicons-user text-muted mr-2"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="dripicons-gear text-muted mr-2"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" type="button" id="logout"><i
                            class="dripicons-exit text-muted mr-2"></i> Logout
                    </button>
                </div>
            </li>
        </ul>

        <ul class="list-unstyled topbar-nav mb-0">

            <li>
                <button class="button-menu-mobile nav-link waves-effect waves-light">
                    <i class="mdi mdi-menu nav-icon"></i>
                </button>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->
<div class="page-wrapper-img">
    <div class="page-wrapper-img-inner">
        <div class="sidebar-user media">
            <img src="assets/images/D6KXMpyS_400x400.png" alt="user" class="rounded-circle img-thumbnail mb-1">
            <span class="online-icon"><i class="mdi mdi-record text-success"></i></span>
            <div class="media-body align-item-center">
                <h5>Hello there <?php echo $_SESSION['username']; ?> </h5>
                <ul class="list-unstyled list-inline mb-0 mt-2">
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class=""><i class="mdi mdi-account"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class=""><i class="mdi mdi-settings"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a type="button" href="javascript: void(0);" id="logout"><i class="mdi mdi-power"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right align-item-center mt-2">
                        <button class="btn btn-info px-4 align-self-center report-btn" type="button"
                                onclick="window.location = 'pricing' ">See plans
                        </button>
                    </div>
                    <h4 class="page-title mb-2"><i class="mdi mdi-monitor-dashboard mr-2"></i>Redeem license code</h4>
                    <div class="">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">MCSpam</a></li>
                            <li class="breadcrumb-item active">Redeem</li>
                        </ol>
                    </div>
                </div><!--end page title box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
    </div><!--end page-wrapper-img-inner-->
</div><!--end page-wrapper-img-->

<div class="page-wrapper">
    <div class="page-wrapper-inner">

        <!-- Left Sidenav -->
        <?php
        if (file_exists('inc/template/sidenav.php')) {
            require($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/template/sidenav.php');
        }
        ?>

        <!-- end left-sidenav-->

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row justify-content-center h-25">
                    <div class="col-lg-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">
                                    Redeem license
                                </h4>
                                <div class="">
                                    <input type="text" class="form-control" id="code" placeholder="License code">
                                </div>
                                <button type="button" class="btn btn-info col-lg-12" id="redeem" style="margin-top: 10px;">Redeem</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">
                                    Information
                                </h4>
                                When you redeemed a license it deletes itself.
                                <br>Your plan will be automatically asigned to your account.
                                <br>If the plan did not add you your account please contact support.
                                <br>Make sure you send your transaction code and email used to receive it.
                                <br>We'll help you resolve it asap.

                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">
                                    Your redeemed codes
                                </h4>
                                <table class="table table-striped table-bordered dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <td>Code</td>
                                            <td>Plan</td>
                                            <td>Date</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>3456345645.gravel</td>
                                            <td>Gravel</td>
                                            <td>2019-04-21</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- container -->


            <footer class="footer text-center text-sm-left">
                &copy; 2019 MCSpam v2.0 <span class="text-muted d-none d-sm-inline-block float-right">Developed with <i
                        class="mdi mdi-heart text-danger"></i> by Rayan#6666</span>
            </footer>
        </div>
        <!-- end page content -->
    </div>
    <!--end page-wrapper-inner -->
</div>
<!-- end page-wrapper -->
<!-- jQuery  -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="assets/plugins/datatables/jszip.min.js"></script>
<script src="assets/plugins/datatables/pdfmake.min.js"></script>
<script src="assets/plugins/datatables/vfs_fonts.js"></script>
<!-- Responsive examples -->
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="assets/js/toaster.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/metisMenu.min.js"></script>
<script src="assets/js/waves.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>

<script src="assets/plugins/moment/moment.js"></script>
<script src="assets/plugins/apexcharts/apexcharts.min.js"></script>

<script src="assets/pages/jquery.dashboard-3.init.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

</body>
</html>


