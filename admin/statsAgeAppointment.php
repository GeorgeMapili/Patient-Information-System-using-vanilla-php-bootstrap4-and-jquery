<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

// Get the age
$elevToTwen = 0;
$twentyOneToFourty = 0;
$fourtyOneUp = 0;

$sql = "SELECT * FROM patientappointment";
$stmt = $con->prepare($sql);
$stmt->execute();

while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

    if(date_diff(date_create($result['pAge']), date_create('now'))->y > 10 && date_diff(date_create($result['pAge']), date_create('now'))->y < 21){
        $elevToTwen++;
    }elseif(date_diff(date_create($result['pAge']), date_create('now'))->y > 20 && date_diff(date_create($result['pAge']), date_create('now'))->y < 41){
        $twentyOneToFourty++;
    }elseif(date_diff(date_create($result['pAge']), date_create('now'))->y >= 41){
        $fourtyOneUp++;
    }

}

$data = (object) array($elevToTwen, $twentyOneToFourty, $fourtyOneUp);

$data = ["elevToTwen" => $elevToTwen, "twentyOneToFourty" => $twentyOneToFourty, "fourtyOneUp" => $fourtyOneUp];

echo json_encode($data);
