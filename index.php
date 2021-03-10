<?php
session_start();
require_once 'connect.php';

if (isset($_SESSION['id'])) {
    header("location:main.php");
    exit(0);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Login</title>
</head>

<?php

if (isset($_POST['login'])) {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));

    $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();


    $tryCount = 0;

    while ($patientUser = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $patientUser['pPassword'])) {
            $_SESSION['id'] = $patientUser['pId'];
            $_SESSION['name'] = $patientUser['pName'];
            $_SESSION['email'] = $patientUser['pEmail'];
            $_SESSION['address'] = $patientUser['pAddress'];
            $_SESSION['age'] = $patientUser['pAge'];
            $_SESSION['gender'] = $patientUser['pGender'];
            $_SESSION['mobile'] = $patientUser['pMobile'];
            $_SESSION['profile'] = $patientUser['pProfile'];
            header("location:main.php");
            exit(0);
        } else {

            $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $patient = $stmt->fetch(PDO::FETCH_ASSOC);
            $patientId = $patient['pId'];
            $time = time();
            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];


            $sql = "INSERT INTO loginlog(ip_address,try_time,patient_id)VALUES(:ipaddress,:time,:id)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":ipaddress", $ip, PDO::PARAM_STR);
            $stmt->bindParam(":time", $time, PDO::PARAM_INT);
            $stmt->bindParam(":id", $patientId, PDO::PARAM_INT);
            $stmt->execute();


            header("location:index.php?errPass=Incorrect_password&pid=$patientId");
            exit(0);
        }
    }

    $patientCount = $stmt->rowCount();

    if ($patientCount == 0) {
        header("location:index.php?errEmail=Incorrect_email");
        exit(0);
    }
}

?>

<body>
    <h1>Hello</h1>
</body>

</html>