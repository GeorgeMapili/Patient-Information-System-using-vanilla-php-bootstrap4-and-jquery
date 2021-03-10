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

<body>
    <h2 class="display-4 mb-3" style="color: rgb(15, 208, 214);">Welcome to SUMC Doctors Clinic</h2>
    <?= (isset($_GET['RegSuccess'])) ? '<span class="text-success my-4">Register Successfully</span>' : ""; ?>
    <form action="index.php" method="post">

        <div class="form-group">
            <label>Email address</label>
            <?= (isset($_GET['errEmail'])) ? '<input type="email" name="email" class="form-control is-invalid" required>' : '<input type="email" name="email" class="form-control" required>'; ?>
            <?= (isset($_GET['errEmail'])) ? '<span class="text-danger">Incorrect Email</span>' : ''; ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <?= (isset($_GET['errPass'])) ? '<input type="password" name="password" class="form-control is-invalid" required>' : '<input type="password" name="password" class="form-control" required>'; ?>
            <?= (isset($_GET['errPass'])) ? '<span class="text-danger">Incorrect Password</span>' : ''; ?><br>
           
            <?= (isset($_GET['pid']) && (3 - $logCount) >= 0) ? '<span class="text-danger"> tries left:  ' . $totalCount = (3 - $logCount) . '' : ''; ?><br>
        </div>
        <?php
        if (isset($_GET['pid']) && (int) (3 - $logCount) <= 0) { ?>
            <div class="text-center">
                <a href="#" class="btn-block btn-info mt-4 disabled">Login</a>
                <p class="text-danger mt-3">Wait for 30 seconds</p>
            </div>
        <?php
        } else {
        ?>
            <input type="submit" class="btn-block btn-info mt-4" value="Login" name="login">
        <?php
        }
        ?>

        <div class="text-center mt-5">
            <a class="btn btn-danger" href="register.php">No account yet?</a>
        </div>
    </form>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>