<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$captcha = new userController();
if (ISSET($_POST)) {
    echo $captcha->createCaptcha();
}