<?php
session_start();
include '../database/connection.php';
$select_notif = "SELECT * FROM applicant_notifications WHERE applicant_id = '" . $_SESSION['id'] . "' AND clicked = '0'";
$select_notif_result = $con->query($select_notif);
$total = $select_notif_result->num_rows;
if($total === 0){
    echo "";
}
else{
    echo $total;
}
