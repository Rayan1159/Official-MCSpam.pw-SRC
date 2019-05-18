<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$user = new userController();

if ($_POST) {
    if ($user->hasPermission() === true) {
        if ($_POST['add'] === "1") {
            $host = $_POST['host'];
            if ($host) {
                if ($user->addBlacklist($host) === true) {
                    echo "added_blacklist";
                    exit();
                } else {
                    echo "not_added";
                    exit();
                }
            } else {
                return false;
            }
        }

        if ($_POST['delete'] === "1") {
            $host = $_POST['host'];
            if ($host) {
                if ($user->deleteBlacklist($host) === true) {
                    echo "blacklist_deleted";
                    exit();
                } else {
                    echo "not_deleted";
                    exit();
                }
            } else {
                return false;
            }
        }
    }
}