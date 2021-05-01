<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$status = "pending";
$sql = "SELECT * FROM appointment WHERE aStatus = :status";
$stmt = $con->prepare($sql);
$stmt->bindParam(":status", $status, PDO::PARAM_STR);
$stmt->execute();

$resultCount = $stmt->rowCount();

if ($resultCount > 0) {
    echo $resultCount;
} else {
    echo "0";
}
