<?php
ob_start();
require_once('PHPMailer/PHPMailerAutoload.php');

require_once("connect.php");
session_start();

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
    <h1 class="mb-3 text-center welcome_title" style="color: rgb(15, 208, 214);">Recover your password</h1>
    <?= (isset($_GET['RegSuccess'])) ? '<span class="text-success my-4">Register Successfully</span>' : ""; ?>
    <form class="w-25" action="forgot-password.php" method="post">

        <p class="text-white">Please enter your email address you used to sign up on this website and we will assist you in recovering you password.</p>

        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>


        <input type="submit" class="btn-block btn-info mt-4" value="Recover password" name="forgot-password">

    </form>

    <?php
    if(isset($_POST['forgot-password'])){
        $email = htmlspecialchars($_POST['email']);

        if(empty($email)){
            header("location:forgot-password.php?error=email-is-empty");
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("location:forgot-password.php?error=email-is-invalid-format");
        }

        $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() == 0){
            header("location:forgot-password.php?error=email-is-not-available");
        }else{

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $id = $user['pId'];

            $body = 'Click the link below to reset your password: <a href="http:localhost/pis/index.php?password_id='.$id.'">Reset password</a>';

            // Create a message
            $emailTo = $email;
            $subject = "Reset your password";
            $content = $body;
            $headers = "From: biblethump69@gmail.com";

            // PHP MAIL
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 1; 
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '465';
            $mail->isHTML();
            $mail->Username = 'bibblethump69@gmail.com';
            $mail->Password = "Thegamemaker1";
            $mail->SetFrom('biblethump69@gmail.com');
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($emailTo);

            if($mail->Send()){
                header("location:password-message.php");
                exit;
            }else{
                header("location:index.php?error=forgot-password-error");
                exit;
            }



        }

    }
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>