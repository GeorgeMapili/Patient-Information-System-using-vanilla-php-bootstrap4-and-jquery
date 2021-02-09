<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['dId'])) {
    header("location:index.php");
    exit(0);
}

$discharge = 0;
$sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInDischarged = :discharge";
$stmt = $con->prepare($sql);
$stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
$stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
$stmt->execute();

$walkinCount = $stmt->rowCount();

if ($walkinCount > 0) {
    echo $walkinCount;
} else {
    echo "0";
}
