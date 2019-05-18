<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 24-4-19
 * Time: 14:55
 */

namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$user = new userController();
$database = new Database();

USE PDO;

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if ($user->hasPermission() === true) {
        if (!$password) {
            $mysql = $database->connect()->prepare('
        
                    UPDATE Users
                        SET username = ?, email = ?
                          WHERE username = ?
            
                ');
            $mysql->bindValue(1, stripslashes(htmlspecialchars($username)), PDO::PARAM_STR);
            $mysql->bindValue(2, stripslashes(htmlspecialchars($email)), PDO::PARAM_STR);
            $mysql->bindValue(3, stripslashes(htmlspecialchars($username)), PDO::PARAM_STR);
            $mysql->execute();
            echo "user_updated";
            exit();
        } elseif ($password) {
            $mysql = $database->connect()->prepare('
        
                    UPDATE Users
                        SET username = ?, email = ?, password = ?
                          WHERE username = ?
            
                ');
            $mysql->bindValue(1, stripslashes(htmlspecialchars($username)), PDO::PARAM_STR);
            $mysql->bindValue(2, stripslashes(htmlspecialchars($email)), PDO::PARAM_STR);
            $mysql->bindValue(3, password_hash($password, PASSWORD_ARGON2I), PDO::PARAM_STR);
            $mysql->bindValue(4, stripslashes(htmlspecialchars($username)), PDO::PARAM_STR);
            $mysql->execute();
            echo "user_updated";
            exit();
        }
    }
    echo "update_failed";
    exit();
}