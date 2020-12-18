<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

$male = "male";
$sql = "SELECT * FROM patientappointment WHERE pGender = :male";
$stmt = $con->prepare($sql);
$stmt->bindParam(":male", $male, PDO::PARAM_STR);
$stmt->execute();

$maleGender = $stmt->rowCount();


$female = "female";
$sql = "SELECT * FROM patientappointment WHERE pGender = :female";
$stmt = $con->prepare($sql);
$stmt->bindParam(":female", $female, PDO::PARAM_STR);
$stmt->execute();

$femaleGender = $stmt->rowCount();

$data = (object) array($maleGender, $femaleGender);

print_r($data);
