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
    $id = $_POST['id'];

    if ($_POST['delete'] === "1") {
        if (is_numeric($id)){
            if ($license->deletePlan($id) === true) {
                echo "plan_deleted";
                exit();
            } else {
                echo "failed_deleting";
                exit();
            }
        }
        return false;
    }
    if($_POST['add'] === "1") {
        $name = $_POST['name'];
        $time = $_POST['time'];
        $concurrents = $_POST['concurrents'];
        $price = $_POST['price'];
        $length = $_POST['length'];
        $vip = $_POST['vip'];

        if ($name && $time && $concurrents && $price && $length && $vip) {
            if ($license->createPlan($name, $time, $concurrents, $price, $length, $vip) === true) {
                echo "plan_added";
                exit();
            } else {
                echo "failed_add";
                exit();
            }
        }
        return false;
    }
}