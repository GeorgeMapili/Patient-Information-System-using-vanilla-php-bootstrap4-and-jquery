<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$status = "done";
$sql = "SELECT * FROM appointment WHERE aStatus = :status";
$stmt = $con->prepare($sql);
$stmt->bindParam(":status", $status, PDO::PARAM_STR);
$stmt->execute();

$patientAppointment = $stmt->rowCount();

if ($patientAppointment > 0) {
    echo $patientAppointment;
} else {
    echo "0";
}
