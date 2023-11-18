<?php
// Add appropriate database connection code here
session_start();
include '../database/connection.php';

if (isset($_SESSION['id'])) {
    $update_notif = "UPDATE applicant_notifications SET clicked = 1 WHERE applicant_id = '" . $_SESSION['id'] . "'";
    $update_notif_result = $con->query($update_notif);
}
