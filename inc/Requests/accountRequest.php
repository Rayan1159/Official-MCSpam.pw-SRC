<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$user = new userController();

/**
 * @return string
 * @return boolean
 *
 * @param $username
 * @param $password
 */

if ($_POST) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $captcha = $_POST['captcha'];

        if ($_POST['register'] === "1") {
            if ($captcha == $_SESSION['captcha']) {
                if ($username && $password) {
                    if ($user->register($username, $password, $email) === true) {
                        echo "account_created";
                        exit();
                    }
                } else {
                    echo "no_input";
                    exit();
                }
            } elseif ($captcha !== $_SESSION['captcha']) {
                echo "invalid_captcha";
            }
        }
        if ($_POST['login'] === "1") {
            if ($username && $password) {
                if($user->isBanned($username) === true) {
                    echo "account_disabled";
                    exit();
                }

                if ($user->createSession($username, $password) === true) {
                    echo "session_started";
                    exit();
                }
            } else {
                echo "no_input";
                exit();
            }
        }
        if ($_POST['logout'] === "1") {
            if ($_SESSION['username']){
                echo "logged_out";
                unset($_SESSION['username']);
            }
        }
        if($_POST['check_plan'] === "1") {
            if ($user->hasPlan() === "true") {
                echo "Plan expired";
                exit();
            } else {
                echo "No action";
                exit();
            }
        }
    } else {
        die("POST Request rejected.");
    }
} else {
    die("Cannot directly execute function through GET request");
}