<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 24-4-19
 * Time: 20:53
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
USE PDO;

$user = new userController();
$session = new Database();
$url = $_SERVER["REQUEST_URI"];
if(preg_match("/\.php/", $url)) {
    header("Location: " . preg_replace("/\.php/", " ", $url));
}

if ($session->getSession() === false) {
    header('location: ../login');
    exit();
}

if ($user->hasPermission() === false){
    header('location: ../index');
    exit();
}
$plan = $_GET['plan'];

if ($_GET) {
    $password = ['oworayan', 'uwurehaab'];
    if (in_array($_GET['password'], $password)){
        $mysql = $session->connect()->prepare('
        
            SELECT * FROM Licenses
              WHERE plan = ?
        
        ');
        $mysql->bindValue(1, stripslashes(htmlspecialchars($plan)), PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0) {
            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                $code = $data['code'];
                if ($code) {
                    echo $code."<br>";
                }
            }
        }
    } else {
        $user->logAll("Tried to get plan data");
        die("Attempt logged");
    }
} else {
    return false;
}