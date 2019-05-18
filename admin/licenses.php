<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
USE PDO;

$user = new userController();
$session = new Database();
$url = $_SERVER["REQUEST_URI"];
if(preg_match("/\.php/", $url)) {
    header("Location: " . preg_replace("/\.php/", " ", $url));
}

if ($session->getSession() === false) {
    header('location: ../login');
}

if ($user->hasPermission() === false){
    header('location: ../index');
    exit();
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
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/jquery.datatables.min.css" rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="../assets/css/toaster.min.css" rel="stylesheet" type="text/css"/>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/userController.js"></script>

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
            <img src="../assets/images/D6KXMpyS_400x400.png" alt="user" class="rounded-circle img-thumbnail mb-1">
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
                    <h4 class="page-title mb-2"><i class="mdi mdi-monitor-dashboard mr-2"></i>Administrator Dashboard</h4>
                    <div class="">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">MCSpam</a></li>
                            <li class="breadcrumb-item active">Admin dashboard</li>
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
        if (file_exists('template/sidenav.php')) {
            require($_SERVER['DOCUMENT_ROOT'] . '/admin/' . '/template/sidenav.php');
        }
        ?>

        <!-- end left-sidenav-->

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">
                                    Create new license(s)
                                </h4>
                                <div class="form-group">
                                    <select class="custom-select" id="plan">
                                        <option value="">Select plan to assign license to</option>
                                        <?php

                                        if ($user->hasPermission() === true) {

                                            $mysql = $session->connect()->prepare('
                                            
                                                SELECT * FROM Plans
                                            
                                            ');
                                            $mysql->execute();

                                            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                                                $plan = $data['name'];

                                                echo "<option value='{$plan}'>{$plan}</option>";
                                            }

                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="custom-select" id="amount">
                                        <option value="">Amount of licenses to make</option>
                                        <option value="1">1 License</option>
                                        <option value="10">10 Licenses</option>
                                        <option value="20">20 Licenses</option>
                                        <option value="30">30 Licenses</option>
                                        <option value="2000">2000 Licenses</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="add_license" class="btn btn-danger col-lg-12">Add license(s) to database</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">
                                    Current plans
                                </h4>
                                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%;" id="licenses">
                                    <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Plan </td>
                                        <td>Code</td>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    if ($user->hasPermission() === true) {
                                        $mysql = $session->connect()->prepare('
                                        
                                            SELECT * FROM Licenses
                                        
                                        ');
                                        $mysql->execute();

                                        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                                            $name = $data['plan'];
                                            $code = $data['code'];
                                            $ID = $data['ID'];
                                            $action = '<button type="button" class="btn btn-danger col-lg-12" id="delete" data-id="' . $data['ID'] . '">Delete</button>';

                                            echo "<tr><td>{$ID}</td><td>{$name}</td><td>{$code}</td><td>{$action}</td></tr>";
                                        }
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
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

<script>
    $(document).ready(function () {
        $(document).on('click', '#add_license', function () {
            var amount = $('#amount').val();
            var plan = $('#plan').val();

            if (amount === "" || plan === "" ){
                return toastr['error']("Input fields left empty");
            }

            $.ajax('inc/Requests/licenseRequest', {
                data: {
                    add: 1,
                    plan: plan,
                    amount:amount
                },
                method: "POST",
                success:function (getResp) {
                    if (getResp === "license_created") {
                        setTimeout(function () {
                            window.location = "servers";
                        }, 2500);
                        return toastr['success']("The license(s) were added to the database", "MCSpam")
                    }
                    if (getResp === "failed_create") {
                        setTimeout(function () {
                            window.location = "servers";
                        }, 2500);
                        return toastr['error']("Failed to create licenses", "MCSpam");
                    }
                }
            });
        });
        $(document).on('click', '#delete', function () {
            var id = $(this).data('id');

            if (id) {
                $.ajax('inc/Requests/licenseRequest', {
                    data: {
                        delete: 1,
                        id: id
                    },
                    method: "POST",
                    success:function (getResp) {
                        if (getResp === "license_deleted") {
                            setTimeout(function () {
                               window.location = "licenses"
                            }, 2500);
                            return toastr['success']("The license was deleted", "MCSpam");
                        }
                        if (getResp === "failed_delete") {
                            return toastr['error']("Something went wrong while deleting license", "MCSpam");
                        }
                    }
                })
            }
        })
    })
</script>

<script src="inc/js/adminTables.js"></script>

<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="../assets/plugins/datatables/jszip.min.js"></script>
<script src="../assets/plugins/datatables/pdfmake.min.js"></script>
<script src=../assets/plugins/datatables/vfs_fonts.js"></script>
<!-- Responsive examples -->
<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="../assets/js/toaster.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/metisMenu.min.js"></script>
<script src="../assets/js/waves.min.js"></script>
<script src="../assets/js/jquery.slimscroll.min.js"></script>

<script src="../assets/plugins/moment/moment.js"></script>
<script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>

<script src="../assets/pages/jquery.dashboard-3.init.js"></script>

<!-- App js -->
<script src="../assets/js/app.js"></script>

</body>
</html>



