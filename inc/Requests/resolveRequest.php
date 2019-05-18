<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/resolveController.php');
$resolver = new resolveController();

if ($_POST) {
    if ($_POST['SRV'] === "1") {
        $host = $_POST['host'];
        if ($host) {
            if ($resolver->checkAddress($host) !== false){
                echo $resolver->checkAddress($host);
                exit();
            } else {
                echo "not_resolved";
                exit();
            }
        }
    }
}
