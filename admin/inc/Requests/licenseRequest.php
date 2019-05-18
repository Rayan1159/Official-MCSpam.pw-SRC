<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 23-4-19
 * Time: 10:13
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$license = new userController();

if ($_POST){
    if ($_POST['add'] === "1") {
        $amount = $_POST['amount'];
        $plan = $_POST['plan'];

        if ($license->createLicense($plan, $amount) === true) {
            echo "license_created";
            exit();
        }
        echo "failed_create";
        exit();
    }
    if ($_POST['delete'] === "1") {
        $id = $_POST['id'];
        if (is_numeric($id)){
            if ($license->deleteLicense($id) === true) {
                echo "license_deleted";
                $license->logAll("Deleted license with id $id");
                exit();
            }
            echo "failed_delete";
            exit();
        }
    }
}