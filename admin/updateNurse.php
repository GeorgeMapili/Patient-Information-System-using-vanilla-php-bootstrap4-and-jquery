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
    <title>Admin | Dashboard</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">Company name</a>
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
                            <a class="nav-link" href="room.php">
                                <span data-feather="users"></span>
                                View All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php" id="primaryColor">
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
                    </ul>

                </div>
            </nav>

            <?php
            if (isset($_POST['updateNurseInformation'])) {
                $id = $_POST['id'];
                $name = trim(htmlspecialchars($_POST['name']));
                $email = trim(htmlspecialchars($_POST['email']));
                $address = trim(htmlspecialchars($_POST['address']));
                $mobile = trim(htmlspecialchars($_POST['mobile']));

                // check if nothing changed
                if ($_SESSION['nurseName'] == $name && $_SESSION['nurseEmail'] == $email && $_SESSION['nurseAddress'] == $address && $_SESSION['nurseMobile'] == $mobile) {
                    header("location:nurse.php?errUpdateNurseChanged=Nothing_to_changed");
                    ob_end_flush();
                    exit(0);
                }

                // check if name is valid
                if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                    header("location:updateNurse.php?errName=name_is_not_valid");
                    exit(0);
                }

                // check if the name is already existed
                $sql = "SELECT * FROM nurse_receptionist WHERE nName= :name AND nId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":id", $_SESSION['nurseId'], PDO::PARAM_INT);
                $stmt->execute();

                $nameCount = $stmt->rowCount();

                if ($nameCount > 0) {
                    header("location:updateNurse.php?errName1=Name_is_already_existed");
                    exit(0);
                }

                // check if the email is valid
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    header("location:updateNurse.php?errEmail=email_is_invalid");
                    exit(0);
                }

                // check if the email is already taken
                $sql = "SELECT * FROM nurse_receptionist WHERE nEmail = :email AND nId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":id", $_SESSION['nurseId'], PDO::PARAM_INT);
                $stmt->execute();

                $emailCount = $stmt->rowCount();

                if ($emailCount > 0) {
                    header("location:updateNurse.php?errEmail1=Email_is_already_existed");
                    exit(0);
                }

                // check if the mobile number is already taken
                $sql = "SELECT * FROM nurse_receptionist WHERE nMobile = :mobile AND nId <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":mobile", $mobile, PDO::PARAM_INT);
                $stmt->bindParam(":id", $_SESSION['nurseId'], PDO::PARAM_INT);
                $stmt->execute();

                $mobileCount = $stmt->rowCount();

                if ($mobileCount > 0) {
                    header("location:updateNurse.php?errMobile=Mobile_number_is_already_existed");
                    exit(0);
                }

                $sql = "UPDATE nurse_receptionist SET nName = :name, nEmail = :email, nAddress = :address, nMobile = :mobile WHERE nId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['nurseName'] = $name;
                $_SESSION['nurseEmail'] = $email;
                $_SESSION['nurseAddress'] = $address;
                $_SESSION['nurseMobile'] = $mobile;

                header("location:nurse.php?succUpdateNurse=Successfully_updated_nurse");
                exit(0);
            }
            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Update Nurse Receptionist</h1>
                </div>
                <div class="container">

                    <?php

                    if (isset($_POST['updateNurseBtn'])) {

                        $id = $_POST['id'];

                        $sql = "SELECT * FROM nurse_receptionist WHERE nId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->execute();

                        $nurse = $stmt->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['nurseId'] = $nurse['nId'];
                        $_SESSION['nurseName'] = $nurse['nName'];
                        $_SESSION['nurseEmail'] = $nurse['nEmail'];
                        $_SESSION['nurseAddress'] = $nurse['nAddress'];
                        $_SESSION['nurseMobile'] = $nurse['nMobile'];
                        $_SESSION['nurseProfile'] = $nurse['nProfileImg'];
                    }
                    ?>

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Nurse Information</h1>
                    </div>

                    <form action="updateNurse.php" method="post">
                        <div class="row my-4">
                            <input type="hidden" name="id" value="<?= $_SESSION['nurseId'] ?>">
                            <div class="col">
                                <label>Nurse Name</label>
                                <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || ((isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed"))) ? '<input type="text" name="name" class="form-control is-invalid" required>' : '<input type="text" name="name" class="form-control" value="' . $_SESSION['nurseName'] . '" required>'; ?>
                                <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<small class="text-danger">Name is not valid!</small>' : ''; ?>
                                <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed") ? '<small class="text-danger">Name is already taken!</small>' : ''; ?>
                            </div>
                            <div class="col">
                                <label>Nurse Email</label>
                                <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="email" class="form-control is-invalid" required>' : '<input type="email" name="email" class="form-control" value="' . $_SESSION['nurseEmail'] . '" required>'; ?>
                                <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Email is invalid!</small>' : ''; ?>
                                <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Email is already existed!</small>' : ''; ?>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <label>Nurse Address</label>
                                <input type="text" name="address" class="form-control" value="<?= $_SESSION['nurseAddress'] ?>" required>
                            </div>
                            <div class="col">
                                <label>Nurse Mobile Number</label>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="mobile" class="form-control is-invalid" placeholder="+639551243145 or 09123456789" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" name="mobile" class="form-control" value="' . $_SESSION['nurseMobile'] . '" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'; ?>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : ''; ?>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <input type="submit" class="btn btn-primary" value="Update Nurse Information" name="updateNurseInformation">
                        </div>

                    </form>

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Nurse Password</h1>
                    </div>

                    <?php

                    if (isset($_POST['updateNursePassword'])) {
                        $id = $_POST['nId'];
                        $currentPassword = trim(htmlspecialchars($_POST['currentPassword']));
                        $newPassword = trim(htmlspecialchars($_POST['newPassword']));
                        $confirmNewPassword = trim(htmlspecialchars($_POST['confirmNewPass']));

                        $sql = "SELECT * FROM nurse_receptionist WHERE nId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->execute();

                        $nurse = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (password_verify($currentPassword, $nurse['nPassword'])) {

                            if ($newPassword !== $confirmNewPassword) {
                                header("location:updateNurse.php?errConfirmPass=password_did_not_match");
                                exit(0);
                            } else {
                                $hashPass = password_hash($newPassword, PASSWORD_DEFAULT);

                                $sql = "UPDATE nurse_receptionist SET nPassword = :password WHERE nId = :id";
                                $stmt = $con->prepare($sql);
                                $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                                $stmt->execute();

                                header("location:updateNurse.php?succUpdatePass=Successfully_updated_password");
                                exit(0);
                            }
                        } else {
                            header("location:updateNurse.php?errCurrPass=incorrect_current_password");
                            exit(0);
                        }
                    }

                    ?>

                    <div class="text-center">
                        <?= (isset($_GET['succUpdatePass']) && $_GET['succUpdatePass'] == "Successfully_updated_password") ? '<span class="text-success">Successfully updated password!</span>' : '';  ?>
                    </div>

                    <form action="updateNurse.php" method="post">
                        <input type="hidden" name="nId" value="<?= $_SESSION['nurseId'] ?>">
                        <label>Current Password</label>
                        <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<input type="password" name="currentPassword" class="form-control is-invalid" minlength="6" required>' : '<input type="password" name="currentPassword" class="form-control" minlength="6" required>'; ?>
                        <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<small class="text-danger">Incorrect Current Password!</small>' : ''; ?>

                        <div class="row my-4">
                            <div class="col">
                                <label>New Password</label>
                                <input type="password" name="newPassword" class="form-control" minlength="6" required>
                            </div>
                            <div class="col">
                                <label>Confirm New Password</label>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<input type="password" name="confirmNewPass" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="confirmNewPass" class="form-control" minlength="6" required>'; ?>
                                <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<small class="text-danger">Password do not match!</small>' : ''; ?>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <input type="submit" class="btn btn-primary" value="Update Password" name="updateNursePassword">
                        </div>
                    </form>

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Nurse Profile Image</h1>
                    </div>

                    <?php
                    if (isset($_POST['updateNurseImage'])) {
                        $nId = $_POST['nId'];
                        $profileImg = $_FILES['profileImg'];

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
                            header("location:updateNurse.php?errorImgExt=image_is_not_valid");
                            exit(0);
                        }

                        // Check if the image size is valid
                        if ($profileImg['size'] > 5000000) {
                            header("location:updateNurse.php?errImgSize=Image_invalid_size");
                            exit(0);
                        }

                        // Current Profile Img
                        $currentProfile = $_SESSION['nurseProfile'];

                        // New Profile Img
                        $newProfile = $profileName;

                        // Path of the current profile
                        $path = __DIR__ . "/../upload/nurse_profile_img/" . $currentProfile;

                        // Delete the current Profile Img
                        unlink($path);

                        // Add the img of the new profile
                        move_uploaded_file($tmpname, $dest);

                        // New session Image
                        $_SESSION['nurseProfile'] = $newProfile;

                        $sql = "UPDATE nurse_receptionist SET nProfileImg = :profile WHERE nId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:updateNurse.php?succUpdateImg=Successfully_update_the_img");
                        exit(0);
                    }
                    ?>

                    <div class="text-center my-5">
                        <?= (isset($_GET['succUpdateImg']) && $_GET['succUpdateImg'] == "Successfully_update_the_img") ? '<span class="text-success">Successfully updated image!<span>' : '' ?>
                    </div>

                    <form action="updateNurse.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="nId" value="<?= $_SESSION['nurseId'] ?>">
                        <label>Nurse Profile Img</label>
                        <?= ((isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") || (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size")) ? '<input type="file" name="profileImg" class="form-control is-invalid" required>' : '<input type="file" name="profileImg" class="form-control" required>' ?>
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : '' ?>
                        <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : '' ?>

                        <div class="text-center my-3">
                            <input type="submit" class="btn btn-primary" value="Update Image" name="updateNurseImage">
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