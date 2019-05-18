<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Controllers/userController.php');
$user = New userController();

if ($_POST) {
    if ($_POST['add'] === "1") {
        $subject = $_POST['subject'];
        $priority = $_POST['priority'];
        $department = $_POST['department'];
        $message = $_POST['message'];

        if ($subject && $priority && $department && $message) {
            if ($user->createTicket($subject, $department, $priority, $message) === true) {
                echo "ticket_submitted";
                exit();
            } else {
                echo "ticket_failed";
                exit();
            }
        } else {
            echo "input_empty";
            exit();
        }
    }

    if ($_POST['add_message'] === "1") {
        $id = $_POST['id'];
        $message = stripslashes(htmlspecialchars($_POST['message']));

        if ($id && $message) {
            if (is_numeric($id)) {
                if ($user->isClosed($id) === true) {
                    echo "closed_ticket";
                    exit();
                }

                if ($user->insertTicketMessage($message, $id) === true) {
                    echo "message_sent";
                    exit();
                } else {
                    echo "not_sent";
                    exit();
                }
            }
        }
    }

    if ($_POST['close'] === "1") {
        $id = $_POST['id'];
        if ($id){
            if ($user->closeTicket($id) === true) {
                echo "ticket_closed";
                exit();
            } else {
                echo "not_closed";
                exit();
            }
        }
    }
}