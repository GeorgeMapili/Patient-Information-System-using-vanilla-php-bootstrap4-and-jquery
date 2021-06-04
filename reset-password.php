<?php
ob_start();
session_start();

require_once("connect.php");

if(!isset($_SESSION['email-reset-password'])){
    header("location:index.php");
    exit;
}

if (isset($_SESSION['id'])) {
    header("location:home.php");
    exit(0);
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/sumc.png">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Patient | Forgot-password</title>

    <style>
        @media screen and (max-width:480px){
            .welcome_title{
                font-size: 40px;
            }
        }
    </style>
</head>

<body>
    <h1 class="mb-3 text-center welcome_title" style="color: rgb(15, 208, 214);">Change password</h1>
    <?= (isset($_GET['RegSuccess'])) ? '<span class="text-success my-4">Register Successfully</span>' : ""; ?>
    <form class="w-25" action="reset-password.php" method="post">

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Minimum of 6 characters" minlength="6" required>
        </div>

        <div class="form-group">
            <label for="password">Confirm Password</label>
            <input type="password" name="confirm-password" class="form-control" placeholder="Minimum of 6 characters" minlength="6" required>
        </div>


        <input type="submit" class="btn-block btn-info mt-4" value="Reset password" name="reset-password">

    </form>

    <?php

    if(isset($_POST['reset-password'])){
        $password = htmlspecialchars($_POST['password']);
        $confirm_password = htmlspecialchars($_POST['confirm-password']);

        if(empty($password) || empty($confirm_password)){
            header("location:reset-password.php?error=apply-all-fields");
            exit;
        }elseif($password != $confirm_password){
            header("location:reset-password.php?error=password-do_not-match");
            exit;
        }

        $hashPass = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE patientappointment SET pPassword = :password WHERE pEmail = :email";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
        $stmt->bindParam(":email", $_SESSION['email-reset-password'], PDO::PARAM_STR);
        $stmt->execute();

        header("location:index.php");
        unset($_SESSION['email-reset-password']);
        exit;

    }

    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>