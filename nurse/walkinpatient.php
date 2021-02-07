<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$sql = "SELECT * FROM walkinpatient";
$stmt = $con->prepare($sql);
$stmt->execute();

$walkinpatient = $stmt->rowCount();

if ($walkinpatient > 0) {
    echo $walkinpatient;
} else {
    echo "0";
}
