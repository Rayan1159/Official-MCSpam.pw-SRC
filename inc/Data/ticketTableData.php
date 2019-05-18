<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Database.php');
$table = new userController();


if (isset($_POST)) {
    echo "            <thead>
                        <tr>
                           <td>Subject</td>
                           <td>Priority</td>
                           <td>Department</td>
                           <td>Status</td>      
                           <td>Read</td>
                        </tr>
                     </thead>";

    $table->loadUserTickets();
}