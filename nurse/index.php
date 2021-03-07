<?php
session_start();
require_once '../connect.php';

if (isset($_SESSION['nId'])) {
    header("location:dashboard.php");
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
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" href="../img/sumc.png">
    <title>Secretary | Login</title>
</head>

<body>
    <?php

    if (isset($_POST['loginNurse'])) {

        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        if (empty($email) || empty($password)) {
            header("location:index.php?errField=Please_input_all_field");
            exit(0);
        }

        $sql = "SELECT * FROM nurse_receptionist WHERE nEmail = :email";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        // PARTIAL VALIDATION WAIT FOR ADMIN TO FINISHED
        while ($nurseAcc = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($password, $nurseAcc['nPassword'])) {
                $_SESSION['nId'] = $nurseAcc['nId'];
                $_SESSION['nName'] = $nurseAcc['nName'];
                $_SESSION['nEmail'] = $nurseAcc['nEmail'];
                $_SESSION['nAddress'] = $nurseAcc['nAddress'];
                $_SESSION['nMobile'] = $nurseAcc['nMobile'];
                $_SESSION['nProfileImg'] = $nurseAcc['nProfileImg'];

                header("location:dashboard.php");
                exit(0);
            } else {
                header("location:index.php?errPass=Incorrect_password");
                exit(0);
            }
        }

        $nurseCount = $stmt->rowCount();
        if ($nurseCount === 0) {
            header("location:index.php?errEmail=Incorrect_Email");
            exit(0);
        }
    }

    ?>

    <h2 class="display-4 mb-3" style="color: rgb(15, 208, 214);">Secretary Login</h2>
    <form action="index.php" method="post">

        <?= (isset($_GET['errField']) && $_GET['errField'] == "Please_input_all_field") ? '<div class="text-center my-3"><span class="text-danger text">Please input all fields</span></div>' : ''; ?>

        <div class="form-group">
            <label>Email address</label>
            <?= (isset($_GET['errEmail']) && $_GET['errEmail'] === "Incorrect_Email") ? '<input type="email" name="email" class="form-control is-invalid" required>' : '<input type="email" name="email" class="form-control" required>'; ?>
            <?= (isset($_GET['errEmail']) && $_GET['errEmail'] === "Incorrect_Email") ? '<span class="text-danger">Incorrect Email</span>' : ''; ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <?= (isset($_GET['errPass']) && $_GET['errPass'] === "Incorrect_password") ? '<input type="password" name="password" class="form-control is-invalid" required>' : '<input type="password" name="password" class="form-control" required>'; ?>
            <?= (isset($_GET['errPass']) && $_GET['errPass'] === "Incorrect_password") ? '<span class="text-danger">Incorrect Passsword</span>' : ''; ?>
        </div>

        <input type="submit" class="btn-block btn-info mt-4" value="Login" name="loginNurse">
    </form>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>