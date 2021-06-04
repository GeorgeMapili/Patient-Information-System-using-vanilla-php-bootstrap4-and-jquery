<?php
session_start();
require_once '../connect.php';

if (isset($_SESSION['ddId'])) {
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
    <title>Doctor | Login</title>
</head>

<body>
    <h2 class="display-4 mb-3" style="color: rgb(15, 208, 214);">Doctor Login</h2>

    <?php
    if (isset($_POST['login'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        $passwords = trim(htmlspecialchars($_POST['password']));

        $sql = "SELECT * FROM doctor WHERE dEmail = :email";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        while ($password = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($passwords, $password['dPassword'])) {
                $_SESSION['ddId'] = $password['dId'];
                $_SESSION['ddName'] = $password['dName'];
                $_SESSION['ddEmail'] = $password['dEmail'];
                $_SESSION['ddAddress'] = $password['dAddress'];
                $_SESSION['ddMobile'] = $password['dMobile'];
                $_SESSION['ddSpecialization'] = $password['dSpecialization'];
                $_SESSION['ddSpecializationInfo'] = $password['dSpecializationInfo'];
                $_SESSION['ddProfileImg'] = $password['dProfileImg'];
                $_SESSION['ddFee'] = $password['dFee'];
                $_SESSION['log_doctor_login'] = date("m/d/y h:iA", time());

                $_SESSION['log_doctor_dashboard'] = null;
                $_SESSION['log_doctor_walkin_patient'] = null;
                $_SESSION['log_doctor_walkin_add_prescription'] = null;
                $_SESSION['log_doctor_walkin_update_prescription'] = null;
                $_SESSION['log_doctor_walkin_update_disease'] = null;
                $_SESSION['log_doctor_walkin_watch_medical_information'] = null;
                $_SESSION['log_doctor_medical_history'] = null;
                $_SESSION['log_doctor_patient_appointment'] = null;
                $_SESSION['log_doctor_patient_appointment_add_prescription'] = null;
                $_SESSION['log_doctor_patient_appointment_update_prescription'] = null;
                $_SESSION['log_doctor_patient_appointment_update_disease'] = null;
                $_SESSION['log_doctor_patient_appointment_appointment_history'] = null;
                $_SESSION['log_doctor_incoming_appointment'] = null;
                $_SESSION['log_doctor_done_appointment'] = null;
                $_SESSION['log_doctor_cancel_appointment'] = null;
                $_SESSION['log_doctor_cancelled_appointment'] = null;
                $_SESSION['log_doctor_finished_appointment'] = null;
                $_SESSION['log_doctor_lab_appointment'] = null;
                $_SESSION['log_doctor_lab_appointment_add_test'] = null;
                $_SESSION['log_doctor_lab_appointment_update_test'] = null;
                $_SESSION['log_doctor_lab_appointment_add_result'] = null;
                $_SESSION['log_doctor_lab_appointment_update_result'] = null;
                $_SESSION['log_doctor_lab_walkin'] = null;
                $_SESSION['log_doctor_lab_walkin_add_test'] = null;
                $_SESSION['log_doctor_lab_walkin_update_test'] = null;
                $_SESSION['log_doctor_lab_walkin_add_result'] = null;
                $_SESSION['log_doctor_lab_walkin_update_result'] = null;
                $_SESSION['log_doctor_information'] = null;
                $_SESSION['log_doctor_update_info'] = null;
                $_SESSION['log_doctor_update_pass'] = null;
                $_SESSION['log_doctor_update_img'] = null;

                header("location:dashboard.php");
                exit(0);
            } else {
                header("location:index.php?errPass=Incorrect_password");
                exit(0);
            }
        }

        if ($stmt->rowCount() < 1) {
            header("location:index.php?errEmail=Incorrect_email");
            exit(0);
        }
    }
    ?>
    <form action="index.php" method="post">
        <div class="form-group">
            <label>Email address</label>
            <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "Incorrect_email") ? '<input type="email" name="email" class="form-control is-invalid" required>' : '<input type="email" name="email" class="form-control" required>' ?>
            <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "Incorrect_email") ? '<small class="text-danger">Incorrect email!</small>' : ''; ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <?= (isset($_GET['errPass']) && $_GET['errPass'] == "Incorrect_password") ? '<input type="password" name="password" class="form-control is-invalid" required>' : '<input type="password" name="password" class="form-control" required>'; ?>
            <?= (isset($_GET['errPass']) && $_GET['errPass'] == "Incorrect_password") ? '<small class="text-danger">Incorrect password!</small>' : ''; ?>
        </div>

        <input type="submit" class="btn-block btn-info mt-4" value="Login" name="login">
    </form>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>