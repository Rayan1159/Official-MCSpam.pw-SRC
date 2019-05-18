<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');

$user = new userController();
$session = new Database();
if(ISSET($_SESSION['username'])) {
    if ($session->getSession() === true) {
        header('location: index');
        exit();
    }
    header('location: login');
    exit();
}

$url = $_SERVER["REQUEST_URI"];
if(preg_match("/\.php/", $url)) {
    header("Location: " . preg_replace("/\.php/", " ", $url));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="utf-8" />
    <title>MCSpam | Minecraft botting service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MCSpam is a service that allows you to bot target mc servers, Doesn't matter of they're premium. You can bot them with ease." name="description" />
    <meta content="Rayan, Navix" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/toaster.min.css" rel="stylesheet" type="text/css" />

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/userController.js"></script>

</head>

<body class="account-body">

<!-- Log In page -->
<div class="row vh-100">
    <div class="col-lg-3  pr-0">
        <div class="card mb-0 shadow-none">
            <div class="card-body">

                <div class="px-3">
                    <div class="media">
                        <a href="login.php" class="logo logo-admin"></a>
                        <div class="media-body ml-3 align-self-center">
                            <h4 class="mt-0 mb-1">Sign up to MCSpam</h4>
                            <p class="text-muted mb-0">Create your account here</p>
                        </div>
                    </div>

                    <form class="form-horizontal my-4">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-outline font-16"></i></span>
                                </div>
                                <input type="text" class="form-control" id="username" placeholder="Enter username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="userpassword">Password</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="mdi mdi-key font-16"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" placeholder="Enter password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="userpassword">Repeat Password</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="mdi mdi-key font-16"></i></span>
                                </div>
                                <input type="password" class="form-control" id="repeat-password" placeholder="Enter password again">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Enter your email</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="mdi mdi-email font-16"></i></span>
                                </div>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="captcha">Enter this code <i id="captcha"></i></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="mdi mdi-codepen font-16"></i></span>
                                </div>
                                <input type="text" class="form-control" id="captcha_answer" placeholder="Enter captcha code">
                            </div>
                        </div>

                        <div class="form-group mb-0 row">
                            <div class="col-12 mt-2">
                                <button class="btn btn-primary btn-block waves-effect waves-light" id="register" type="button">Register <i class="fas fa-sign-in-alt ml-1"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="m-3 text-center bg-banner p-3 text-primary">
                    <h5 class="">Already have an account? </h5>
                    <p class="font-13">Login <span>Here</span></p>
                    <a href="login" class="btn btn-primary btn-round waves-effect waves-light">Sign in</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 p-0 d-flex justify-content-center">
        <div class="accountbg d-flex align-items-center">
            <div class="account-title text-white text-center">
                <h4 class="mt-3">Welcome to MCSpam V2</h4>
                <div class="border w-25 mx-auto border-primary"></div>
                <h1 class="">Let's Get Started</h1>
                <p class="font-14 mt-3">Don't have an account ? <a href="register" class="text-primary">Sign up</a></p>
            </div>
        </div>
    </div>
</div>
<!-- End Log In page -->


<script src="assets/js/toaster.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/metisMenu.min.js"></script>
<script src="assets/js/waves.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

</body>
</html>
