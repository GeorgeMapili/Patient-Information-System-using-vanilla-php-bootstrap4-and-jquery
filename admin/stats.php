<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

$sql = "SELECT * FROM walkinpatient WHERE walkInAge <= 10";
$stmt = $con->prepare($sql);
$stmt->execute();

$tenBelow = $stmt->rowCount();

$val1 = 11;
$val2 = 20;
$sql = "SELECT * FROM walkinpatient WHERE walkInAge BETWEEN :val1 AND :val2";
$stmt = $con->prepare($sql);
$stmt->bindParam(":val1", $val1, PDO::PARAM_INT);
$stmt->bindParam(":val2", $val2, PDO::PARAM_INT);
$stmt->execute();

$elevToTwen = $stmt->rowCount();

$val1 = 21;
$val2 = 40;
$sql = "SELECT * FROM walkinpatient WHERE walkInAge BETWEEN :val1 AND :val2";
$stmt = $con->prepare($sql);
$stmt->bindParam(":val1", $val1, PDO::PARAM_INT);
$stmt->bindParam(":val2", $val2, PDO::PARAM_INT);
$stmt->execute();

$twentyOneToFourty = $stmt->rowCount();

$sql = "SELECT * FROM walkinpatient WHERE walkInAge >= 41";
$stmt = $con->prepare($sql);
$stmt->execute();

$fourtyOneUp = $stmt->rowCount();

$data = (object) array($tenBelow, $elevToTwen, $twentyOneToFourty, $fourtyOneUp);

print_r($data);
