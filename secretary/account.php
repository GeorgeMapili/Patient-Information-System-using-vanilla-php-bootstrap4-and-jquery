<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_secretary_information'] = true;

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
    <title>Secretary | Profile</title>
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
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $pendingCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pendings.php">Pending Appointments&nbsp;<?= ($pendingCount > 0) ? '<span id="pending-appointment" class="badge bg-danger">' . $pendingCount . '</span>' : '<span id="pending-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $status = "done";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $patientAppointment = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patient-appointments.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM walkinpatient";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $walkinpatient = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patient-walkin.php">Patient Walk in&nbsp;<?= ($walkinpatient > 0) ? '<span id="walkinpatient" class="badge bg-danger">' . $walkinpatient . '</span>' : '<span id="walkinpatient" class="badge bg-danger"></span>'; ?></a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/nurse_profile_img/<?= $_SESSION['nProfileImg']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="account.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <form action="logout.php" method="post">
                                <input type="hidden" name="logout" value="true">
                                <input type="submit" value="Logout" class="dropdown-item">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <?php
        if (isset($_POST['updateNurseInformationBtn'])) {
            $nId = $_POST['nId'];
            $nurseName = trim(htmlspecialchars($_POST['nurseName']));
            $nurseEmail = trim(htmlspecialchars($_POST['nurseEmail']));
            $nurseAddress = trim(htmlspecialchars($_POST['nurseAddress']));
            $nurseMobile = trim(htmlspecialchars($_POST['nurseMobile']));

            // Check if nothing changed
            if ($_SESSION['nName'] == $nurseName && $_SESSION['nEmail'] == $nurseEmail && $_SESSION['nAddress'] == $nurseAddress && $_SESSION['nMobile'] == $nurseMobile) {
                header("location:account.php?errNurseInfoUpdate=Nothing_to_update");
                exit(0);
            }

            // Check the name if valid
            if (!preg_match("/^([a-zA-Z' ]+)$/", $nurseName)) {
                header("location:account.php?errNurseName=nurse_name_is_not_valid");
                exit(0);
            }

            // check if the doct name is already taken
            $sql = "SELECT * FROM nurse_receptionist WHERE nName = :name AND nId <> :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":name", $nurseName, PDO::PARAM_STR);
            $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
            $stmt->execute();

            $nameCount = $stmt->rowCount();

            if ($nameCount > 0) {
                header("location:account.php?errNurseName1=Nurse_name_is_already_taken");
                exit(0);
            }

            // check if the email is valid
            if (!filter_var($nurseEmail, FILTER_VALIDATE_EMAIL)) {
                header("location:account.php?errEmail=email_is_invalid");
                exit(0);
            }

            // check if the email is already existed
            $sql = "SELECT * FROM nurse_receptionist WHERE nEmail = :email AND nId <> :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":email", $nurseEmail, PDO::PARAM_STR);
            $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
            $stmt->execute();

            $emailCount = $stmt->rowCount();

            if ($emailCount > 0) {
                header("location:account.php?errEmail1=Email_is_already_existed");
                exit(0);
            }

            // check if the mobile number is already taken
            $sql = "SELECT * FROM nurse_receptionist WHERE nMobile = :mobile AND nId <> :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":mobile", $nurseMobile, PDO::PARAM_INT);
            $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
            $stmt->execute();

            $mobileCount = $stmt->rowCount();

            if ($mobileCount > 0) {
                header("location:account.php?errMobile=Mobile_number_is_already_existed");
                exit(0);
            }

            $sql = "UPDATE nurse_receptionist SET nName = :name, nEmail = :email, nAddress = :address, nMobile = :mobile WHERE nId = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":name", $nurseName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $nurseEmail, PDO::PARAM_STR);
            $stmt->bindParam(":address", $nurseAddress, PDO::PARAM_STR);
            $stmt->bindParam(":mobile", $nurseMobile, PDO::PARAM_STR);
            $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['nName'] = $nurseName;
            $_SESSION['nEmail'] = $nurseEmail;
            $_SESSION['nAddress'] = $nurseAddress;
            $_SESSION['nMobile'] = $nurseMobile;

            header("location:account.php?succUpdateNurse=Successfully_updated_information");
            $_SESSION['log_secretary_update_info'] = true;
            exit(0);
        }
        ?>

        <div class="container">

            <div class="shadow-lg p-3 mb-5 bg-white rounded mt-5">
                <div class="mt-4 mb-4">
                    <h1 class="Display-4" id="primaryColor">Profile</h1>
                </div>


                <div class="text-center my-3">
                    <?= (isset($_GET['errNurseInfoUpdate']) && $_GET['errNurseInfoUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : '' ?>
                    <?= (isset($_GET['succUpdateNurse']) && $_GET['succUpdateNurse'] == "Successfully_updated_information") ? '<span class="text-success">Successfully updated information!</span>' : '' ?>
                </div>

                <form action="account.php" method="post">
                    <div class="row">
                        <input type="hidden" name="nId" value="<?= $_SESSION['nId'] ?>">
                        <div class="col">
                            <label>Name</label>
                            <?= ((isset($_GET['errNurseName']) && $_GET['errNurseName'] == "nurse_name_is_not_valid") || (isset($_GET['errNurseName1']) && $_GET['errNurseName1'] == "Nurse_name_is_already_taken")) ? '<input type="text" name="nurseName" class="form-control is-invalid" required>' : '<input type="text" name="nurseName" class="form-control" value="' . $_SESSION['nName'] . '">' ?>
                            <?= (isset($_GET['errNurseName']) && $_GET['errNurseName'] == "nurse_name_is_not_valid") ? '<small class="text-danger">Nurse name is not valid!</small>' : '' ?>
                            <?= (isset($_GET['errNurseName1']) && $_GET['errNurseName1'] == "Nurse_name_is_already_taken") ? '<small class="text-danger">Nurse name is already existed!</small>' : '' ?>
                        </div>
                        <div class="col">
                            <label>Email</label>
                            <?= ((isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") || (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed")) ? '<input type="email" name="nurseEmail" class="form-control is-invalid" required>' : '<input type="email" name="nurseEmail" class="form-control" value="' . $_SESSION['nEmail'] . '">' ?>
                            <?= (isset($_GET['errEmail']) && $_GET['errEmail'] == "email_is_invalid") ? '<small class="text-danger">Email is invalid!</small>' : '' ?>
                            <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "Email_is_already_existed") ? '<small class="text-danger">Email is already existed!</small>' : '' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Address</label>
                            <input type="text" name="nurseAddress" class="form-control" value="<?= $_SESSION['nAddress'] ?>" required>
                        </div>
                        <div class="col">
                            <label>Mobile Number</label>
                            <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="nurseMobile" class="form-control is-invalid" placeholder="+639551243145 or 09123456789" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" name="nurseMobile" class="form-control" value="' . $_SESSION['nMobile'] . '">' ?>
                            <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : '' ?>
                        </div>
                    </div>
                    <div class="text-center my-5">
                        <input type="submit" name="updateNurseInformationBtn" class="btn btn-info" value="Update Information">
                    </div>
                </form>
            </div>

            <hr class="featurette-divider">

            <?php
            if (isset($_POST['updatePasswordBtn'])) {
                $nId = $_POST['nId'];
                $currentPassword = $_POST['currentPassword'];
                $newPassword = $_POST['newPassword'];
                $confirmNewPassword = $_POST['confirmNewPassword'];

                $sql = "SELECT * FROM nurse_receptionist WHERE nId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                $stmt->execute();

                $nurseAcc = $stmt->fetch(PDO::FETCH_ASSOC);

                // check if the current password is correct
                if (password_verify($currentPassword, $nurseAcc['nPassword'])) {

                    if ($newPassword !== $confirmNewPassword) {
                        header("location:account.php?errConfirmPass=password_did_not_match");
                        exit(0);
                    } else {
                        $hashPass = password_hash($newPassword, PASSWORD_DEFAULT);
                        $sql = "UPDATE nurse_receptionist SET nPassword = :password WHERE nId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:account.php?succUpdatePass=Successfully_updated_password");
                        $_SESSION['log_secretary_update_pass'] = true;
                        exit(0);
                    }
                } else {
                    header("location:account.php?errCurrPass=incorrect_current_password");
                    ob_end_flush();
                    exit(0);
                }
            }
            ?>

            <div class="shadow-lg p-3 mb-5 bg-white rounded mt-5">
                <div class="text-center my-4">
                    <?= (isset($_GET['succUpdatePass']) && $_GET['succUpdatePass'] == "Successfully_updated_password") ? '<span class="text-success">Successfully updated password!</span>' : '' ?>
                </div>

                <div class="mt-4 mb-4">
                    <h1 class="Display-4" id="primaryColor">Password</h1>
                </div>

                <form action="account.php" method="post">
                    <input type="hidden" name="nId" value="<?= $_SESSION['nId'] ?>">
                    <label>Current Password</label>
                    <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<input type="password" name="currentPassword" class="form-control is-invalid" required>' : '<input type="password" name="currentPassword" class="form-control" required>' ?>
                    <?= (isset($_GET['errCurrPass']) && $_GET['errCurrPass'] == "incorrect_current_password") ? '<small class="text-danger">Incorrect current password!</small>' : '' ?>
                    <div class="row">
                        <div class="col">
                            <label>New Password</label>
                            <input type="password" name="newPassword" minlength="6" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Confirm Password</label>
                            <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<input type="password" name="confirmNewPassword" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="confirmNewPassword" minlength="6" class="form-control" required>' ?>
                            <?= (isset($_GET['errConfirmPass']) && $_GET['errConfirmPass'] == "password_did_not_match") ? '<small class="text-danger">Password did not match!</small>' : '' ?>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <input type="submit" value="Update Password" name="updatePasswordBtn" class="btn btn-info">
                    </div>
                </form>
            </div>

            <hr class="featurette-divider">

            <div class="shadow-lg p-3 mb-5 bg-white rounded mt-5">
                <div class="mt-4 mb-4">
                    <h1 class="Display-4" id="primaryColor">Profile Image</h1>
                </div>

                <!-- Image -->
                <div>
                    <img src="../upload/nurse_profile_img/<?= $_SESSION['nProfileImg']; ?>" class="rounded-circle shadow p-3 mb-5 bg-white rounded" alt="profile" width="150" height="150">
                </div>

                <?php
                if (isset($_POST['updateImageBtn'])) {
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
                        header("location:account.php?errorImgExt=image_is_not_valid");
                        exit(0);
                    }

                    // Check if the image size is valid
                    if ($profileImg['size'] > 5000000) {
                        header("location:account.php?errImgSize=Image_invalid_size");
                        exit(0);
                    }

                    // Current Profile Img
                    $currentProfile = $_SESSION['nProfileImg'];

                    // New Profile Img
                    $newProfile = $profileName;

                    // Path of the current profile
                    $path = __DIR__ . "/../upload/nurse_profile_img/" . $currentProfile;

                    // Delete the current Profile Img
                    unlink($path);

                    // Add the img of the new profile
                    move_uploaded_file($tmpname, $dest);

                    // New session Image
                    $_SESSION['nProfileImg'] = $newProfile;

                    $sql = "UPDATE nurse_receptionist SET nProfileImg = :profile WHERE nId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);
                    $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                    $stmt->execute();

                    header("location:account.php?succUpdateImg=Successfully_update_the_img");
                    $_SESSION['log_secretary_update_img'] = true;
                    exit(0);
                }
                ?>

                <div class="text-center my-4">
                    <?= (isset($_GET['succUpdateImg']) && $_GET['succUpdateImg'] == "Successfully_update_the_img") ? '<span class="text-success">Successfully update the image!</span>' : '' ?>
                </div>

                <form action="account.php" class="my-4 mx-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="nId" value="<?= $_SESSION['nId'] ?>">
                    <div class="row">
                        <label>Nurse Profile Image</label>
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<input type="file" name="profileImg" class="form-control is-invalid" required>' : '<input type="file" name="profileImg" class="form-control" required>' ?>
                        <?= (isset($_GET['errorImgExt']) && $_GET['errorImgExt'] == "image_is_not_valid") ? '<small class="text-danger">Image is not valid only(JPEG,JPG,PNG)!</small>' : '' ?>
                        <?= (isset($_GET['errImgSize']) && $_GET['errImgSize'] == "Image_invalid_size") ? '<small class="text-danger">Image is not valid only less size(5MB)!</small>' : '' ?>
                    </div>
                    <div class="text-center mt-3">
                        <input type="submit" name="updateImageBtn" value="Update Image" class="btn btn-info">
                    </div>
                </form>
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
                url: "pendingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#pending-appointment").html(result);
                        result = "0";
                        $("#pending-appointment-dashboard").html(result);
                    } else {
                        $("#pending-appointment").html(result);
                        $("#pending-appointment-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "patientAppointment.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#patient-appointment").html(result);
                        result = "0";
                        $("#patient-appointment-dashboard").html(result);
                    } else {
                        $("#patient-appointment").html(result);
                        $("#patient-appointment-dashboard").html(result);
                    }

                }
            });

            $.ajax({
                url: "walkinpatient.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkinpatient").html(result);
                        result = "0";
                        $("#walkinpatient-dashboard").html(result);
                    } else {
                        $("#walkinpatient").html(result);
                        $("#walkinpatient-dashboard").html(result);
                    }

                }
            });

        });
    </script>
</body>

</html>