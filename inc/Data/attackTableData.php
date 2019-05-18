<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$table = new userController();


if (isset($_POST)) {
    echo "                    <thead>
                                        <tr>
                                            <td>Target</td>
                                            <td>Port</td>
                                            <td>Time</td>
                                            <td>Version</td>
                                            <td>Status</td>
                                        </tr>
                                    </thead>";

    $table->loadAttackTable();
}