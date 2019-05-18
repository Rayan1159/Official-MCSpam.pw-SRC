<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 24-4-19
 * Time: 23:48
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$news = new userController();
$database = new Database();
USE PDO;

if ($_POST) {
    if ($news->hasPermission() === true){
        if ($_POST['add'] === "1") {
            $api = $_POST['api'];
            if ($api) {
                $mysql = $database->connect()->prepare('
                
                    INSERT INTO Servers(api)
                      VALUES(?)
                
                ');
                $mysql->bindValue(1, $api, PDO::PARAM_STR);
                $mysql->execute();
                echo "server_added";
                exit();
            }
            echo "failed_add";
            exit();
        }

        if ($_POST['delete'] === "1") {
            $id = $_POST['id'];
            $mysql = $database->connect()->prepare('
            
                DELETE FROM Servers
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $id, PDO::PARAM_INT);
            $mysql->execute();
            echo "server_deleted";
            exit();
        }
    }
}