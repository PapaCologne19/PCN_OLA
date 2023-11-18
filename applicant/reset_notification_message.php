<?php
// Add appropriate database connection code here
session_start();
include '../database/connection.php';

if (isset($_SESSION['id'])) {
    $update_notif_message = "UPDATE applicant_notifications SET view = 1 WHERE applicant_id = '" . $_SESSION['id'] . "'";
    $update_notif_message_result = $con->query($update_notif_message);
}
