<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
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
    <link rel="stylesheet" href="../css/admin.css" />
    <title>Admin | Add Doctor</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">SUMC Doctors Clinic</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="dashboard.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="patientUser.php">
                                <span data-feather="file"></span>
                                View All Patient Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="patient.php">
                                <span data-feather="file"></span>
                                View All Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doctor.php" id="primaryColor">
                                <span data-feather="shopping-cart"></span>
                                View All Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="walkInPatient.php">
                                <span data-feather="shopping-cart"></span>
                                View All Walk in patient
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php">
                                <span data-feather="users"></span>
                                View All Nurse Receptionist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doneAppointment.php">
                                <span data-feather="users"></span>
                                View Finished Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancelledAppointment.php">
                                <span data-feather="users"></span>
                                View Cancelled Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="walkInDischarged.php">
                                <span data-feather="users"></span>
                                View Walkin Patient Discharged
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="messages.php">
                                <span data-feather="users"></span>
                                View All Messages
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">All Doctors</h1>
                </div>
                <div class="container">

                    <div class="text-center">
                        <?= (isset($_GET['succAdd']) && $_GET['succAdd'] == "Successfully_added_doctor") ? '<span class="text-success">Successfully added doctor</span>' : ''; ?>
                    </div>

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Add Doctor</h1>
                    </div>

                    <?php

                    if (isset($_POST['addDoctor'])) {

                        $dName = trim(htmlspecialchars($_POST['dName']));
                        $dEmail = trim(htmlspecialchars($_POST['dEmail']));
                        $dAddress = trim(htmlspecialchars($_POST['dAddress']));
                        $dMobile = trim(htmlspecialchars($_POST['dMobile']));
                        $dSpecialization = trim(htmlspecialchars($_POST['dSpecialization']));
                        $dFee = trim(htmlspecialchars($_POST['dFee']));
                        $profileImg = $_FILES['dProfileImg'];
                        $dSpecializationInfo = trim(htmlspecialchars($_POST['dSpecializationInfo']));
                        $dPassword = trim(htmlspecialchars($_POST['dPassword']));
                        $dConfirmPassword = trim(htmlspecialchars($_POST['dConfirmPassword']));

                        // check if the doctor name is already taken
                        $sql = "SELECT * FROM doctor WHERE dName= :dname";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":dname", $dName, PDO::PARAM_STR);
                        $stmt->execute();

                        $nameCount = $stmt->rowCount();

                        if ($nameCount > 0) {
                            header("location:addDoctor.php?errName1=Doctor_name_is_already_existed");
                            exit(0);
                        }

                        // check if email is invalid
                        if (!filter_var($dEmail, FILTER_VALIDATE_EMAIL)) {
                            header("location:addDoctor.php?errEmail=email_is_invalid");
                            exit(0);
                        }

                        // check if the email is already taken
                        $sql = "SELECT * FROM doctor WHERE dEmail = :email";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":email", $dEmail, PDO::PARAM_STR);
                        $stmt->execute();

                        $emailCount = $stmt->rowCount();

                        if ($emailCount > 0) {
                            header("location:addDoctor.php?errEmail1=Email_is_already_existed");
                            exit(0);
                        }

                        // check if the mobile number is already taken
                        $sql = "SELECT * FROM doctor WHERE dMobile = :mobile";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":mobile", $dMobile, PDO::PARAM_INT);
                        $stmt->execute();

                        $mobileCount = $stmt->rowCount();

                        if ($mobileCount > 0) {
                            header("location:addDoctor.php?errMobile=Mobile_number_is_already_existed");
                            exit(0);
                        }

                        // Image
                        $ext = $profileImg['type'];
                        $extF = explode('/', $ext);

                        // Unique Image Name
                        $profileName =  uniqid(rand()) . "." . $extF[1];

                        $tmpname = $profileImg['tmp_name'];
                        $dest = __DIR__ . "/../upload/doc_profile_img/" . $profileName;

                        //check if the image is valid
                        $allowed = array('jpg', 'jpeg', 'png');

                        if (!in_array(strtolower($extF[1]), $allowed)) {
                            header("location:addDoctor.php?errorImgExt=image_is_not_valid");
                            exit(0);
                        }

                        // Check if the image size is valid
                        if ($profileImg['size'] > 5000000) {
                            header("location:addDoctor.php?errImgSize=Image_invalid_size");
                            exit(0);
                        }

                        if ($dPassword !== $dConfirmPassword) {
                            header("location:addDoctor.php?errConfirmPass=password_did_not_match");
                            exit(0);
                        }

                        // hashPassword
                        $hashPass = password_hash($dPassword, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO doctor (dName,dEmail,dAddress,dMobile,dSpecialization,dSpecializationInfo,dProfileImg,dFee,dPassword)VALUES(:name,:email,:address,:mobile,:specialization,:specializationInfo,:profile,:fee,:password)";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":name", $dName, PDO::PARAM_STR);
                        $stmt->bindParam(":email", $dEmail, PDO::PARAM_STR);
                        $stmt->bindParam(":address", $dAddress, PDO::PARAM_STR);
                        $stmt->bindParam(":mobile", $dMobile, PDO::PARAM_STR);
                        $stmt->bindParam(":specialization", $dSpecialization, PDO::PARAM_STR);
                        $stmt->bindParam(":specializationInfo", $dSpecializationInfo, PDO::PARAM_STR);
                        $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                        $stmt->bindParam(":fee", $dFee, PDO::PARAM_INT);
                        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                        $stmt->execute();

                        // Upload the doctor profile image
                        move_uploaded_file($tmpname, $dest);

                        header("location:addDoctor.php?succAdd=Successfully_added_doctor");
                        exit(0);
                    }

                    ?>
                    <form action="addDoctor.php" method="post" enctype="multipart/form-data">
                        <div class="row my-3">
                            <div class="col">
                                <label>Doctor Name</label>
                                <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Doctor_name_is_already_existed") ? '<input type="text" name="dName" class="form-control is-invalid" placeholder="Ex: Dr. John Doe" required>' : '<input type="text" name="dName" class="form-control" placeholder="Ex: Dr. John Doe" required>'; ?>
                                <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Doctor_name_is_already_existed") ? '<small class="text-danger">Doctor name is already existed!</small>' : ''; ?>
                            </div>
                            <div class="col">
                                <label>Doctor Email</label>
                                <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="dEmail" class="form-control is-invalid" required>' : '<input type="email" name="dEmail" class="form-control" required>'; ?>
                                <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Doctor email is invalid!</small>' : ''; ?>
                                <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Doctor email is already existed!</small>' : ''; ?>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <label>Doctor Address</label>
                                <input type="text" name="dAddress" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Doctor Mobile Number</label>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="dMobile" class="form-control is-invalid" required>' : '<input type="tel" name="dMobile" class="form-control" required>'; ?>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : ''; ?>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col">
                                <label>Doctor Specialization</label>
                                <input type="text" name="dSpecialization" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Doctor Fee</label>
                                <input type="number" name="dFee" class="form-control" required>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <label>Doctor Profile Img</label>
                                <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<input type="file" name="dProfileImg" class="form-control is-invalid" required>' : '<input type="file" name="dProfileImg" class="form-control" required>'; ?>
                                <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : ''; ?>
                                <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : ''; ?>
                            </div>
                            <div class="col">
                                <label>Doctor Specialization Information</label>
                                <textarea name="dSpecializationInfo" cols="10" rows="10" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <label>Password</label>
                                <input type="password" minlength="6" name="dPassword" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Confirm Password</label>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<input type="password" minlength="6" name="dConfirmPassword" class="form-control is-invalid" required>' : '<input type="password" minlength="6" name="dConfirmPassword" class="form-control" required>'; ?>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<small class="text-danger">Confirm password did not match!</small>' : ''; ?>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <form action="addDoctor.php" method="post">
                                <input type="submit" class="btn btn-primary" value="Add Doctor" name="addDoctor">
                            </form>
                        </div>
                    </form>
                </div>


            </main>
        </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>