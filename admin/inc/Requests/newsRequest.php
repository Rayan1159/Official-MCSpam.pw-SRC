<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 24-4-19
 * Time: 22:15
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$news = new userController();
$database = new Database();
USE PDO;

if ($_POST) {
    if ($_POST['add'] === "1"){
        if ($news->hasPermission() === true) {
            $title = $_POST['title'];
            $message  = $_POST['message'];

            if ($title && $message) {
                if ($news->postNews($title, $message) === true) {
                    echo "news_posted";
                    $news->logAll("Posted new with title: $title");
                    exit();
                }

            }
            echo "not_posted";
            exit();
        }
    }
    if ($_POST['delete'] === "1") {
        if ($news->hasPermission() === true) {
            $id = $_POST['id'];
            if ($id){
                $mysql = $database->connect()->prepare('
                
                    DELETE FROM News
                        WHERE ID = ?
                
                ');
                $mysql->bindValue(1, $id, PDO::PARAM_STR);
                $mysql->execute();
                echo "news_deleted";
                $news->logAll("Deleted news with ID $id");
                exit();
            }
        }
        echo "not_deleted";
        exit();
    }
}