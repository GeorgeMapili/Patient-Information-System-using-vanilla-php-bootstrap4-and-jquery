<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

if(isset($_POST['dId'])){
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
    <title>Admin | Update Doctor</title>
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
                    <h1 class="h2">Update</h1>
                </div>
                <div class="container">

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Doctor Information</h1>
                    </div>

                    <?php
                    if (isset($_POST['updateDoctorBtn'])) {
                        $dId = $_POST['dId'];

                        $sql = "SELECT * FROM doctor WHERE dId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                        $stmt->execute();

                        $updateDoctor = $stmt->fetch(PDO::FETCH_ASSOC);

                        // SESSION OF DOCTORS INFO
                        $_SESSION['dId'] = $updateDoctor['dId'];
                        $_SESSION['dName'] = $updateDoctor['dName'];
                        $_SESSION['dEmail'] = $updateDoctor['dEmail'];
                        $_SESSION['dAddress'] = $updateDoctor['dAddress'];
                        $_SESSION['dMobile'] = $updateDoctor['dMobile'];
                        $_SESSION['dSpecialization'] = $updateDoctor['dSpecialization'];
                        $_SESSION['dFee'] = $updateDoctor['dFee'];
                        $_SESSION['dSpecializationInfo'] = $updateDoctor['dSpecializationInfo'];
                        $_SESSION['dProfileImg'] = $updateDoctor['dProfileImg'];
                    }
                    ?>

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
                        if ($dName == $_SESSION['dName'] && $dEmail == $_SESSION['dEmail'] && $dAddress == $_SESSION['dAddress'] && $dMobile == $_SESSION['dMobile'] && $dSpecialization == $_SESSION['dSpecialization'] && $dFee == $_SESSION['dFee'] && $specializationInfo == $_SESSION['dSpecializationInfo']) {
                            header("location:doctor.php?errUpdate=Nothing_to_update");
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
                            header("location:updateDoctor.php?errNameUpdate=Doctor_name_is_already_taken");
                            exit(0);
                        }

                        // check if the email is valid
                        if (!filter_var($dEmail, FILTER_VALIDATE_EMAIL)) {
                            header("location:updateDoctor.php?errEmail=email_is_invalid");
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
                            header("location:updateDoctor.php?errEmail1=Email_is_already_existed");
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
                            header("location:updateDoctor.php?errMobile=Mobile_number_is_already_existed");
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

                        header("location:doctor.php?updateSuccInfo=Successfully_updated_information");
                        exit(0);
                    }
                    ?>

                    <form action="updateDoctor.php" method="post" enctype="multipart/form-data">
                        <div class="row my-4">
                            <input type="hidden" name="dId" value="<?= $_SESSION['dId'] ?>">
                            <div class="col">
                                <label>Doctor Name</label>
                                <?= (isset($_GET['errNameUpdate']) && $_GET['errNameUpdate'] == "Doctor_name_is_already_taken") ? '<input type="text" name="dName" class="form-control is-invalid" required>' : '<input type="text" name="dName" class="form-control" value="' . $_SESSION['dName'] . '" required>' ?>
                                <?= (isset($_GET['errNameUpdate']) && $_GET['errNameUpdate'] == "Doctor_name_is_already_taken") ? '<small class="text-danger">Name is already taken!</small>' : ''; ?>
                            </div>
                            <div class="col">
                                <label>Doctor Email</label>
                                <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="dEmail" class="form-control is-invalid" required>' : '<input type="email" name="dEmail" class="form-control" value=' . $_SESSION['dEmail'] . ' required>'; ?>
                                <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Email is invalid!</small>' : ''; ?>
                                <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Email is already existed!</small>' : ''; ?>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <label>Doctor Address</label>
                                <input type="text" name="dAddress" class="form-control" value="<?= $_SESSION['dAddress'] ?>" required>
                            </div>
                            <div class="col">
                                <label>Doctor Mobile Number</label>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="dMobile" class="form-control is-invalid" required>' : '<input type="tel" name="dMobile" class="form-control" value=' . $_SESSION['dMobile'] . ' required>'; ?>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : ''; ?>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col">
                                <label>Doctor Specialization</label>
                                <input type="text" name="dSpecialization" class="form-control" value="<?= $_SESSION['dSpecialization'] ?>" required>
                            </div>
                            <div class="col">
                                <label>Doctor Fee</label>
                                <input type="text" name="dFee" class="form-control" value="<?= $_SESSION['dFee'] ?>" required>
                            </div>
                        </div>

                        <div class="my-4">
                            <label>Doctor Specialization Information</label>
                            <textarea name="specializationInfo" class="form-control" id="" cols="30" rows="10" required><?= $_SESSION['dSpecializationInfo'] ?></textarea>
                        </div>

                        <div class="text-center my-3">
                            <input type="submit" class="btn btn-primary" value="Update Doctor Information" name="updateDoctor">
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
                            header("location:updateDoctor.php?errorImgExt=image_is_not_valid");
                            exit(0);
                        }

                        // Check if the image size is valid
                        if ($profileImg['size'] > 5000000) {
                            header("location:updateDoctor.php?errImgSize=Image_invalid_size");
                            exit(0);
                        }

                        // Current Profile Img
                        $currentProfile = $_SESSION['dProfileImg'];

                        // New Profile Img
                        $newProfile = $profileName;

                        // Path of the current profile
                        $path = __DIR__ . "/../upload/doc_profile_img/" . $currentProfile;

                        // Delete the current Profile Img
                        unlink($path);

                        // Add the img of the new profile
                        move_uploaded_file($tmpname, $dest);

                        // New session Image
                        $_SESSION['dProfileImg'] = $newProfile;

                        $sql = "UPDATE doctor SET dProfileImg = :profile WHERE dId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:doctor.php?succUpdateImg=Successfully_update_the_img");
                        exit(0);
                    }

                    ?>
                    <hr>

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
                                header("location:updateDoctor.php?errConfirmPass=password_did_not_match");
                                exit(0);
                            } else {

                                $hashPass = password_hash($newPass, PASSWORD_DEFAULT);
                                $sql = "UPDATE doctor SET dPassword = :password WHERE dId = :id";
                                $stmt = $con->prepare($sql);
                                $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                                $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                                $stmt->execute();

                                header("location:doctor.php?succUpdatePass=Successfully_updated_password");
                                exit(0);
                            }
                        } else {
                            header("location:updateDoctor.php?errCurrPass=incorrect_current_password");
                            exit(0);
                        }
                    }

                    ?>

                    <form action="updateDoctor.php" method="post">
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
                            <input type="hidden" name="dId" value="<?= $_SESSION['dId'] ?>">
                            <input type="submit" class="btn btn-primary" value="Update Password" name="updatePassword">
                        </div>
                    </form>

                    <hr>

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Doctor Profile</h1>
                    </div>

                    <form action="updateDoctor.php" method="post" enctype="multipart/form-data">
                        <label>Doctor Profile Img</label>
                        <input type="hidden" name="dId" value="<?= $_SESSION['dId'] ?>">
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<input type="file" name="doctorProfileImg" class="form-control is-invalid" required>' : '<input type="file" name="doctorProfileImg" class="form-control" required>'; ?>
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : '' ?>
                        <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : '' ?>
                        <div class="text-center my-3">
                            <input type="submit" class="btn btn-primary" value="Update Profile Image" name="updateProfileImg">
                        </div>
                    </form>

                    <hr>


                </div>
                <div>
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
<?php
}else{
    header("location:dashboard.php");
    exit;
}
?>