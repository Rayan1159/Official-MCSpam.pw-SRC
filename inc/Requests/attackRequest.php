<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 21-4-19
 * Time: 13:21
 */
namespace mcspam;

require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/attackController.php');
$user = new attackController();

if ($_POST) {
    if ($_POST['joinbot'] === "1"){
        $target = $_POST['ip'];
        $port = $_POST['port'];
        $time = $_POST['time'];
        $version = $_POST['version'];
        $vip = $_POST['vip'];
        $mode = $_POST['mode'];

        if ($target && $port && $time && $version) {
            if ($vip === "1") {
                if ($user->getVip() === false){
                    echo "no_vip";
                    exit();
                }
            }

            if (!is_numeric($mode)){
                return false;
            }

            if ($user->maxAttacks() === true) {
                echo "max_running";
                exit();
            }

            if ($user->isFree() === true) {
                if ($time > 120) {
                    echo "time_limit";
                    die();
                }
                elseif ($user->isFree() === false)
                {
                    if ($time > $user->getAttack()) {
                        echo "time_limit";
                        exit();
                    }
                }
            }

            if ($user->isBlacklist($target) === true) {
                echo "server_blacklist";
                exit();
            }

            if ($user->getConc() === false){
                echo "concurrent_limit";
                exit();
            }

            if ($user->sendAttack($target, $port, $time, $version, $vip, $mode) === true) {
                echo "attack_sent";
                exit();
            }
            echo "attack_failed";
        } else {
            echo "input_empty";
        }
    }
    if ($_POST["layer7"] === "1"){
        $host = $_POST['ip'];
        $time = $_POST['time'];

        if ($host && $time) {
            if ($time > 600){
                echo "time_limit";
                exit();
            }
        }

        if ($user->getConc() === false) {
            echo "concurrent_limit";
            exit();
        }

        if ($user->maxAttacks() === true) {
            echo "max_running";
            exit();
        }

        if ($user->sendHTTP($host, $time) === false) {
            echo "attack_failed";
            exit();
        } else {
            echo "attack_sent";
            exit();
        }
    }
}