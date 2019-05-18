<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 28-4-19
 * Time: 13:34
 */
if ($_POST) {
    if ($_POST['enabled'] === "1") {
        $price = "disabled";

        if ($price === "enabled"){
            echo "disabled";
            exit();
        } else {
            echo "enabled";
            exit();
        }
    }
}