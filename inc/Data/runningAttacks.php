<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 29-4-19
 * Time: 20:44
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$table = new userController();

if ($table->deadAttacks() === false) {
    echo "not_cleared";
    exit();
} else if($table->deadAttacks() === true) {
    echo "cleared";
    exit();
}