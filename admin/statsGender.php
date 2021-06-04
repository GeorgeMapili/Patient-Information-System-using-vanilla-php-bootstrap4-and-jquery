<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

$male = "male";
$sql = "SELECT walkInGender FROM walkinpatient WHERE walkInGender = :male";
$stmt = $con->prepare($sql);
$stmt->bindParam(":male", $male, PDO::PARAM_STR);
$stmt->execute();

$maleGender = $stmt->rowCount();


$male = "female";
$sql = "SELECT walkInGender FROM walkinpatient WHERE walkInGender = :female";
$stmt = $con->prepare($sql);
$stmt->bindParam(":female", $male, PDO::PARAM_STR);
$stmt->execute();

$femaleGender = $stmt->rowCount();

$data = ["male" => $maleGender, "female" => $femaleGender];

echo json_encode($data);
