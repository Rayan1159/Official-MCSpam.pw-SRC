<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 20-4-19
 * Time: 18:43
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$database = new Database();
USE PDO;

$mysql = $database->connect()->prepare('

    SELECT * FROM Logs
      WHERE type = ?
        AND username = ?

');
$mysql->bindValue(1, "Login", PDO::PARAM_STR);
$mysql->bindValue(2, $_SESSION['username'], PDO::PARAM_STR);
$mysql->execute();

while ($row = $mysql->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

$results = [
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
];


echo json_encode($results);