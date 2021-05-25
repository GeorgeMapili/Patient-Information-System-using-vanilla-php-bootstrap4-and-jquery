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
    <link rel="icon" href="../img/sumc.png">
    <title>Admin | Add Secretary</title>
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
                            <a class="nav-link" href="doctor.php">
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
                            <a class="nav-link" href="nurse.php" id="primaryColor">
                                <span data-feather="users"></span>
                                View All Secretary
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
                        <li class="nav-item">
                            <a class="nav-link" href="diseaseTreatment.php">
                                <span data-feather="users"></span>
                                View All Diseases & Treatment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="audit-log.php">
                                <span data-feather="users"></span>
                                View Audit Logs
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">Add Nurse Receptionist</h1>
                </div>
                <div class="container">

                    <?php

                    if (isset($_POST['addNurseBtn'])) {
                        $name = trim(htmlspecialchars($_POST['name']));
                        $email = trim(htmlspecialchars($_POST['email']));
                        $address = trim(htmlspecialchars($_POST['address']));
                        $mobile = trim(htmlspecialchars($_POST['mobile']));
                        $profileImg = $_FILES['profileImg'];
                        $password = trim(htmlspecialchars($_POST['password']));
                        $confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));

                        // check if name is valid
                        if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                            header("location:addNurse.php?errName=name_is_not_valid");
                            ob_end_flush();
                            exit(0);
                        }

                        // check if the name is already taken
                        $sql = "SELECT * FROM nurse_receptionist WHERE nName= :name";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                        $stmt->execute();

                        $nameCount = $stmt->rowCount();

                        if ($nameCount > 0) {
                            header("location:addNurse.php?errName1=Name_is_already_existed");
                            exit(0);
                        }

                        // check if email is invalid
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            header("location:addNurse.php?errEmail=email_is_invalid");
                            exit(0);
                        }

                        // check if the email is already taken
                        $sql = "SELECT * FROM nurse_receptionist WHERE nEmail = :email";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                        $stmt->execute();

                        $emailCount = $stmt->rowCount();

                        if ($emailCount > 0) {
                            header("location:addNurse.php?errEmail1=Email_is_already_existed");
                            exit(0);
                        }

                        // check if the mobile number is already taken
                        $sql = "SELECT * FROM nurse_receptionist WHERE nMobile = :mobile";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":mobile", $mobile, PDO::PARAM_INT);
                        $stmt->execute();

                        $mobileCount = $stmt->rowCount();

                        if ($mobileCount > 0) {
                            header("location:addNurse.php?errMobile=Mobile_number_is_already_existed");
                            exit(0);
                        }

                        // Image
                        $ext = $profileImg['type'];
                        $extF = explode('/', $ext);

                        // Unique Image Name
                        $profileName =  uniqid(rand()) . "." . $extF[1];

                        $tmpname = $profileImg['tmp_name'];
                        $dest = __DIR__ . "/../upload/nurse_profile_img/" . $profileName;

                        //check if the image is valid
                        $allowed = array('jpg', 'jpeg', 'png');

                        if (!in_array(strtolower($extF[1]), $allowed)) {
                            header("location:addNurse.php?errorImgExt=image_is_not_valid");
                            exit(0);
                        }

                        // Check if the image size is valid
                        if ($profileImg['size'] > 5000000) {
                            header("location:addNurse.php?errImgSize=Image_invalid_size");
                            exit(0);
                        }

                        // Check if password does match
                        if ($password !== $confirmPassword) {
                            header("location:addNurse.php?errConfirmPass=password_did_not_match");
                            exit(0);
                        }

                        // Hash Password
                        $hashPass = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO nurse_receptionist (nName,nEmail,nAddress,nMobile,nProfileImg,nPassword)VALUES(:name,:email,:address,:mobile,:profile,:password)";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                        $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
                        $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                        $stmt->execute();

                        // upload the file
                        move_uploaded_file($tmpname, $dest);

                        header("location:nurse.php?succAddedNurse=Successfully_added_secretary");
                        exit(0);
                    }

                    ?>

                    <form action="addNurse.php" method="post" enctype="multipart/form-data">
                        <div class="row my-4">
                            <div class="col">
                                <label>Secretary Name</label>
                                <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed")) ? '<input type="text" name="name" class="form-control is-invalid" required>' : '<input type="text" name="name" class="form-control" required>' ?>
                                <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<small class="text-danger">Name is not valid!</small>' : ''; ?>
                                <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed") ? '<small class="text-danger">Name is already existed!</small>' : ''; ?>

                            </div>
                            <div class="col">
                                <label>Secretary Email</label>
                                <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="email" class="form-control is-invalid" required>' : '<input type="email" name="email" class="form-control" required>'; ?>
                                <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Email is invalid format!</small>' : ''; ?>
                                <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Email is already existed!</small>' : ''; ?>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <label>Secretary Address</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Secretary Mobile Number</label>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="mobile" class="form-control is-invalid" placeholder="+639551243145 or 09123456789" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" name="mobile" class="form-control" placeholder="+639551243145 or 09123456789" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'; ?>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : ''; ?>
                            </div>
                        </div>

                        <label>Secretary Profile Img</label>
                        <?= ((isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") || (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size")) ? '<input type="file" name="profileImg" class="form-control is-invalid" name="profileImg" required>' : '<input type="file" name="profileImg" class="form-control" name="profileImg" required>'; ?>
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : ''; ?>
                        <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : ''; ?>

                        <div class="row my-4">
                            <div class="col">
                                <label>Password</label>
                                <input type="password" name="password" minlength="6" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Confirm Password</label>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<input type="password" name="confirmPassword" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="confirmPassword" minlength="6" class="form-control" required>'; ?>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<small class="text-danger">Confirm Password did not match!</small>' : ''; ?>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <input type="submit" class="btn btn-info" value="Add Secretary" name="addNurseBtn">
                        </div>
                    </form>
                </div>

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