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

    <!-- Wizard -->
    <link href="assets/plugins/form-wizard/css/smart_wizard.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/form-wizard/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/form-wizard/css/smart_wizard_theme_circles.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>>

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
                    <h4 class="page-title mb-2"><i class="mdi mdi-monitor-dashboard mr-2"></i>Attack HUB</h4>
                    <div class="">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">MCSpam</a></li>
                            <li class="breadcrumb-item active">HUB</li>
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
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Attack HUB V2.5</h4>
                                <p class="text-muted mb-4">Here you can send bot attacks to servers.</p>
                                <?php if ($user->isFree() === true) { ?>
                                <div class="alert alert-danger">You're using the free plan. You can get much more power by purchasing.</div>
                                <?php } ?>
                                <div id="smart_wizard_arrows">
                                    <ul>
                                        <li><a href="#step-1">Specify your attack settings<br /><small>Put your desired attack settings below</small></a></li>
                                        <li><a href="#step-2">Attack overview<br /><small>The attack settings you specified.</small></a></li>
                                    </ul>

                                    <div class="p-3 sw-arrows-content mb-3">
                                        <div id="step-1" style="margin-top: 2%;">
                                            <div class="form-group">
                                                <input class="form-control" id="target" placeholder="Enter target ip" type="text">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="port" placeholder="Enter target port" type="text">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="time" placeholder="Enter attack time   <?php if ($user->isFree() === true) { echo "(Max 120 seconds)";} ?>" type="text">
                                            </div>
                                            <div class="form-group">
                                                <select class="custom-select" id="mode">
                                                    <option value="">Attack style</option>
                                                    <option value="1">High server load (Massive ram load on target server)</option>
                                                    <option value="2">Fast joins (Bots join very fast)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="custom-select" id="vip">
                                                    <option value="">Enable VIP Mode?</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="padding-bottom: 2%;">
                                                <select class="custom-select" id="version">
                                                    <option value="">Select a version</option>
                                                    <option value="477">1.14</option>
                                                    <option value="404">1.13.2</option>
                                                    <option value="401">1.13.1</option>
                                                    <option value="393">1.13</option>
                                                    <option value="404">1.13</option>
                                                    <option value="340">1.12.2</option>
                                                    <option value="338">1.12.1</option>
                                                    <option value="335">1.12</option>
                                                    <option value="316">1.11.2</option>
                                                    <option value="315">1.11</option>
                                                    <option value="210">1.10.*</option>
                                                    <option value="110">1.9.4</option>
                                                    <option value="109">1.9.2</option>
                                                    <option value="108">1.9.1</option>
                                                    <option value="107">1.9</option>
                                                    <option value="47">1.8</option>
                                                    <option value="5">1.7.10</option>
                                                    <option value="4">1.7.2 / 1.7.5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="step-2" class="">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered dt-responsive nowrap">
                                                        <thead>
                                                            <tr>
                                                                <td>Target IP</td>
                                                                <td>Port</td>
                                                                <td>Time</td>
                                                                <td>Version</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td id="get_ip"></td>
                                                                <td id="get_port"></td>
                                                                <td id="get_time"></td>
                                                                <td id="get_version"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end /div-->
                                </div> <!--end smartwizard-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Your attack overview</h4>
                                <table class="table table-striped table-bordered dt-responsive nowrap" id="attack_table">

                                </table>
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->
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

<script>
    $(document).ready(function () {
        setInterval(function () {
            $.ajax({
                url: 'inc/Data/attackTableData',
                method: 'POST',
                success: function (data) {
                    if (data) {
                        $('#attack_table').html(data);
                    }
                }
            });
        }, 1000)
    });
</script>

<!-- end page-wrapper -->
<!-- jQuery  -->
<script src="assets/plugins/form-wizard/js/jquery.smartWizard.min.js"></script>
<script src="assets/pages/jquery.form-wizard.init.js"></script>
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


