<?php
session_start();
require_once '../connect.php';

if (isset($_SESSION['adId'])) {
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
    <title>Admin | Login</title>
</head>

<body>
    <h2 class="display-4 mb-3" style="color: rgb(15, 208, 214);">Admin Login</h2>

    <div class="text-center my-4">
        <?= (isset($_GET['errInfo']) && $_GET['errInfo'] = "Incorrect_credentials") ? '<span class="text-danger">Incorrect Credentials</span>' : ''; ?>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        $sql = "SELECT * FROM admin WHERE ad_email = :email";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $adminAccount = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $adminAccount['ad_pass'])) {
            $_SESSION['adId'] = $adminAccount['ad_id'];
            $_SESSION['adName'] = $adminAccount['ad_name'];
            $_SESSION['adEmail'] = $adminAccount['ad_email'];

            header("location:dashboard.php");
            exit(0);
        } else {
            header("location:index.php?errInfo=Incorrect_credentials");
            exit(0);
        }
    }
    ?>

    <form action="index.php" method="post">
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <input type="submit" class="btn-block btn-info mt-4" value="Login" name="login">
    </form>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>