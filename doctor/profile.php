<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['ddId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_doctor_information'] = true;

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="icon" href="../img/sumc.png">
    <title>Doctor | Profile</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <?php
                    $discharge = 0;
                    $sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInDischarged = :discharge";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
                    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                    $stmt->execute();
                    $walkinCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="walkinpatient.php">Walk in Patient&nbsp;<?= ($walkinCount > 0) ? '<span id="walkin-count" class="badge bg-danger">' . $walkinCount . '</span>' : '<span id="walkin-count" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <?php
                    $status1 = "accepted";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                    $stmt->execute();
                    $upcomingAppointmentCount = $stmt->rowCount();
                    ?>
                    <div class="btn-group dropbottom">
                        <a class="nav-link" href="incoming-appointment.php">Upcoming&nbsp;<?= ($upcomingAppointmentCount > 0) ? '<span id="upcoming-count" class="badge bg-danger">' . $upcomingAppointmentCount . '</span>' : '<span id="upcoming-count" class="badge bg-danger"></span>'; ?></a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="cancelled-appointment.php">Cancelled</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finished-appointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
                    <div class="dropdown nav-item">
                        <span class="nav-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Laboratory
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="lab-patient-appointment.php">Patient Appointment</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="lab-patient-walkin.php">Walk in Patient</a>
                        </div>
                    </div>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/doc_profile_img/<?= $_SESSION['ddProfileImg'] ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['ddName'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['ddEmail'] ?></a>
                            <a class="dropdown-item" href="profile.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Doctor Information</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['errUpdate']) && $_GET['errUpdate'] == "Nothing_to_update") ? '<small class="text-danger">Nothing to update!</small>' : ''; ?>
                <?= (isset($_GET['updateSuccInfo']) && $_GET['updateSuccInfo'] == "Successfully_updated_information") ? '<small class="text-success">Successfully updated information!</small>' : ''; ?>
            </div>

            <?php
            if (isset($_POST['updateDoctor'])) {
                $dId = $_POST['dId'];
                $dName = trim(htmlspecialchars($_POST['dName']));
                $dEmail = trim(htmlspecialchars($_POST['dEmail']));
                $dAddress = trim(htmlspecialchars($_POST['dAddress']));
                $dMobile = trim(htmlspecialchars($_POST['dMobile']));
                $dSpecialization = trim(htmlspecialchars($_POST['dSpecialization']));
                $dFee = trim(htmlspecialchars($_POST['dFee']));
                $specializationInfo = trim(htmlspecialchars($_POST['specializationInfo']));

                // check if nothing changed
                if ($dName == $_SESSION['ddName'] && $dEmail == $_SESSION['ddEmail'] && $dAddress == $_SESSION['ddAddress'] && $dMobile == $_SESSION['ddMobile'] && $dSpecialization == $_SESSION['ddSpecialization'] && $dFee == $_SESSION['ddFee'] && $specializationInfo == $_SESSION['ddSpecializationInfo']) {
                    header("location:profile.php?errUpdate=Nothing_to_update");
                    ob_end_flush();
                    exit(0);
                }

                // check if the doct name is already taken
                $sql = "SELECT * FROM doctor WHERE dName = :name AND dId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $dName, PDO::PARAM_STR);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                $nameCount = $stmt->rowCount();

                if ($nameCount > 0) {
                    header("location:profile.php?errNameUpdate=Doctor_name_is_already_taken");
                    exit(0);
                }

                // check if the email is valid
                if (!filter_var($dEmail, FILTER_VALIDATE_EMAIL)) {
                    header("location:profile.php?errEmail=email_is_invalid");
                    exit(0);
                }

                // check if the email is already existed
                $sql = "SELECT * FROM doctor WHERE dEmail = :email AND dId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":email", $dEmail, PDO::PARAM_STR);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                $emailCount = $stmt->rowCount();

                if ($emailCount > 0) {
                    header("location:profile.php?errEmail1=Email_is_already_existed");
                    exit(0);
                }

                // check if the mobile number is already taken
                $sql = "SELECT * FROM doctor WHERE dMobile = :mobile AND dId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":mobile", $dMobile, PDO::PARAM_INT);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                $mobileCount = $stmt->rowCount();

                if ($mobileCount > 0) {
                    header("location:profile.php?errMobile=Mobile_number_is_already_existed");
                    exit(0);
                }

                $sql = "UPDATE doctor SET dName = :name, dEmail = :email, dAddress = :address, dMobile = :mobile, dSpecialization = :specialization, dSpecializationInfo = :speInfo, dFee = :dfee WHERE dId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $dName, PDO::PARAM_STR);
                $stmt->bindParam(":email", $dEmail, PDO::PARAM_STR);
                $stmt->bindParam(":address", $dAddress, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $dMobile, PDO::PARAM_STR);
                $stmt->bindParam(":specialization", $dSpecialization, PDO::PARAM_STR);
                $stmt->bindParam(":speInfo", $specializationInfo, PDO::PARAM_STR);
                $stmt->bindParam(":dfee", $dFee, PDO::PARAM_INT);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['ddName'] = $dName;
                $_SESSION['ddEmail'] = $dEmail;
                $_SESSION['ddAddress'] = $dAddress;
                $_SESSION['ddMobile'] = $dMobile;
                $_SESSION['ddSpecialization'] = $dSpecialization;
                $_SESSION['ddSpecializationInfo'] = $specializationInfo;
                $_SESSION['ddFee'] = $dFee;

                header("location:profile.php?updateSuccInfo=Successfully_updated_information");
                $_SESSION['log_doctor_update_info'] = true;
                exit(0);
            }
            ?>

            <form action="profile.php" method="post" enctype="multipart/form-data" class="shadow p-3 mb-5 bg-white rounded">
                <div class="row my-4">
                    <input type="hidden" name="dId" value="<?= $_SESSION['ddId'] ?>">
                    <div class="col">
                        <label>Doctor Name</label>
                        <?= (isset($_GET['errNameUpdate']) && $_GET['errNameUpdate'] == "Doctor_name_is_already_taken") ? '<input type="text" name="dName" class="form-control is-invalid" required>' : '<input type="text" name="dName" class="form-control" value="' . $_SESSION['ddName'] . '" required>' ?>
                        <?= (isset($_GET['errNameUpdate']) && $_GET['errNameUpdate'] == "Doctor_name_is_already_taken") ? '<small class="text-danger">Name is already taken!</small>' : ''; ?>
                    </div>
                    <div class="col">
                        <label>Doctor Email</label>
                        <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="dEmail" class="form-control is-invalid" required>' : '<input type="email" name="dEmail" class="form-control" value=' . $_SESSION['ddEmail'] . ' required>'; ?>
                        <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Email is invalid!</small>' : ''; ?>
                        <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Email is already existed!</small>' : ''; ?>
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col">
                        <label>Doctor Address</label>
                        <input type="text" name="dAddress" class="form-control" value="<?= $_SESSION['ddAddress'] ?>" required>
                    </div>
                    <div class="col">
                        <label>Doctor Mobile Number</label>
                        <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="dMobile" class="form-control is-invalid" required>' : '<input type="tel" name="dMobile" class="form-control" value=' . $_SESSION['ddMobile'] . ' required>'; ?>
                        <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : ''; ?>
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col">
                        <label>Doctor Specialization</label>
                        <input type="text" name="dSpecialization" class="form-control" value="<?= $_SESSION['ddSpecialization'] ?>" required>
                    </div>
                    <div class="col">
                        <label>Doctor Fee</label>
                        <input type="text" name="dFee" class="form-control" value="<?= $_SESSION['ddFee'] ?>" required>
                    </div>
                </div>

                <div class="my-4">
                    <label>Doctor Specialization Information</label>
                    <textarea name="specializationInfo" class="form-control" id="" cols="30" rows="10" required><?= $_SESSION['ddSpecializationInfo'] ?></textarea>
                </div>

                <div class="text-center my-3">
                    <input type="submit" class="btn btn-info" value="Update Doctor Information" name="updateDoctor">
                </div>
            </form>

            <?php

            if (isset($_POST['updateProfileImg'])) {

                $dId = $_POST['dId'];
                $profileImg = $_FILES['doctorProfileImg'];

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
                    header("location:profile.php?errorImgExt=image_is_not_valid");
                    exit(0);
                }

                // Check if the image size is valid
                if ($profileImg['size'] > 5000000) {
                    header("location:profile.php?errImgSize=Image_invalid_size");
                    exit(0);
                }

                // Current Profile Img
                $currentProfile = $_SESSION['ddProfileImg'];

                // New Profile Img
                $newProfile = $profileName;

                // Path of the current profile
                $path = __DIR__ . "/../upload/doc_profile_img/" . $currentProfile;

                // Delete the current Profile Img
                unlink($path);

                // Add the img of the new profile
                move_uploaded_file($tmpname, $dest);

                // New session Image
                $_SESSION['ddProfileImg'] = $newProfile;

                $sql = "UPDATE doctor SET dProfileImg = :profile WHERE dId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                header("location:profile.php?succUpdateImg=Successfully_update_the_img");
                $_SESSION['log_doctor_update_img'] = true;
                exit(0);
            }

            ?>
            <hr>

            <div class="text-center my-3">
                <?= (isset($_GET['succUpdatePass']) && $_GET['succUpdatePass'] == "Successfully_updated_password") ? '<span class="text-success">Sucessfully updated password!</span>' : ''; ?>
            </div>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Doctor Password</h1>
            </div>

            <?php

            if (isset($_POST['updatePassword'])) {
                $dId = $_POST['dId'];
                $currentPass = trim(htmlspecialchars($_POST['currentPass']));
                $newPass = trim(htmlspecialchars($_POST['newPass']));
                $confirmNewPass = trim(htmlspecialchars($_POST['confirmNewPass']));

                // check if the current password is valid
                $sql = "SELECT * FROM doctor WHERE dId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                $stmt->execute();

                $currentAcc = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($currentPass, $currentAcc['dPassword'])) {

                    // check if the password and confirm password match
                    if ($newPass !== $confirmNewPass) {
                        header("location:profile.php?errConfirmPass=password_did_not_match");
                        exit(0);
                    } else {

                        $hashPass = password_hash($newPass, PASSWORD_DEFAULT);
                        $sql = "UPDATE doctor SET dPassword = :password WHERE dId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:profile.php?succUpdatePass=Successfully_updated_password");
                        $_SESSION['log_doctor_update_pass'] = true;
                        exit(0);
                    }
                } else {
                    header("location:profile.php?errCurrPass=incorrect_current_password");
                    ob_end_flush();
                    exit(0);
                }
            }

            ?>

            <form action="profile.php" method="post" class="shadow p-3 mb-5 bg-white rounded">
                <div>
                    <label for="exampleInputEmail1">Current Password</label>
                    <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<input type="password" name="currentPass" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="currentPass" minlength="6" class="form-control" required>'; ?>
                    <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<small class="text-danger">Incorrect Current Password!</small>' : ''; ?>
                </div>
                <div class="row my-3">
                    <div class="col">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" name="newPass" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1">Confirm New Password</label>
                        <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<input type="password" name="confirmNewPass" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="confirmNewPass" minlength="6" class="form-control" required>'; ?>
                        <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<small class="text-danger">Password do not match!</small>' : ''; ?>
                    </div>
                </div>

                <div class="text-center my-3">
                    <input type="hidden" name="dId" value="<?= $_SESSION['ddId'] ?>">
                    <input type="submit" class="btn btn-info" value="Update Password" name="updatePassword">
                </div>
            </form>

            <hr>

            <form action="profile.php" method="post" enctype="multipart/form-data" class="shadow p-3 mb-5 bg-white rounded">
            <div class="text-center">
                <?= (isset($_GET['succUpdateImg']) && $_GET['succUpdateImg'] == "Successfully_update_the_img") ? '<span class="text-success">Successfully updated image!</span>' : ''; ?>
            </div>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Doctor Profile</h1>
            </div>

            <!-- Image -->
            <div>
                <img src="../upload/doc_profile_img/<?= $_SESSION['ddProfileImg'] ?>" class="rounded-circle shadow p-3 mb-5 bg-white rounded" alt="profile" width="150" height="150">
            </div>
                <label>Doctor Profile Img</label>
                <input type="hidden" name="dId" value="<?= $_SESSION['ddId'] ?>">
                <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<input type="file" name="doctorProfileImg" class="form-control is-invalid" required>' : '<input type="file" name="doctorProfileImg" class="form-control" required>'; ?>
                <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : '' ?>
                <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : '' ?>
                <div class="text-center my-3">
                    <input type="submit" class="btn btn-info" value="Update Profile Image" name="updateProfileImg">
                </div>
            </form>

            <hr>


        </div>
        <div>
        </div>
        </div>
    </main>



    <!-- FOOTER -->
    <footer class="container">
        <p class="float-right"><a href="#" class="text-dark">Back to top</a></p>
        <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
    </footer>



    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('33e38cfddf441ae84e2d', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            // alert(JSON.stringify(data));
            $.ajax({
                url: "walkinCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkin-count").html(result);
                        result = "0";
                        $("#walkin-count-dashboard").html(result);
                    } else {
                        $("#walkin-count").html(result);
                        $("#walkin-count-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "upcomingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#upcoming-count").html(result);
                        result = "0";
                        $("#upcoming-count-dashboard").html(result);
                    } else {
                        $("#upcoming-count").html(result);
                        $("#upcoming-count-dashboard").html(result);
                    }

                }
            });

        });
    </script>

</body>

</html>