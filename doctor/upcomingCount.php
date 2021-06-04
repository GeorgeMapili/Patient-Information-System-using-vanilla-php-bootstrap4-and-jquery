<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['ddId'])) {
    header("location:index.php");
    exit(0);
}

$status1 = "accepted";
$sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1";
$stmt = $con->prepare($sql);
$stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
$stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
$stmt->execute();
$upcomingAppointmentCount = $stmt->rowCount();

if ($upcomingAppointmentCount > 0) {
    echo $upcomingAppointmentCount;
} else {
    echo "0";
}
