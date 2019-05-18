<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 21-4-19
 * Time: 21:53
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$user = new userController();

if ($_POST) {
    $license = $_POST['code'];
    if ($license) {
        if ($user->activateMembership($license) === true) {
            echo "code_redeemed";
            exit();
        } else {
            echo "invalid_license";
            exit();
        }
    }
    return false;
}