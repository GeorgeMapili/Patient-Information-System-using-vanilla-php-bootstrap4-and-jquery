<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css" />
    <title>Nurse | Profile</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointmentPending.php">Pending Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient from appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patientWalkIn.php">Patient Walk in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room.php">Room</a>
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
                            Nurse&nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="nurseProfile.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
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
                header("location:nurseProfile.php?errNurseInfoUpdate=Nothing_to_update");
                exit(0);
            }

            // Check the name if valid
            if (!preg_match("/^([a-zA-Z' ]+)$/", $nurseName)) {
                header("location:nurseProfile.php?errNurseName=nurse_name_is_not_valid");
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
                header("location:nurseProfile.php?errNurseName1=Nurse_name_is_already_taken");
                exit(0);
            }

            // check if the email is valid
            if (!filter_var($nurseEmail, FILTER_VALIDATE_EMAIL)) {
                header("location:nurseProfile.php?errEmail=email_is_invalid");
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
                header("location:nurseProfile.php?errEmail1=Email_is_already_existed");
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
                header("location:nurseProfile.php?errMobile=Mobile_number_is_already_existed");
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

            header("location:nurseProfile.php?succUpdateNurse=Successfully_updated_information");
            exit(0);
        }
        ?>

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Profile</h1>
            </div>

            <div class="text-center my-3">
                <?= (isset($_GET['errNurseInfoUpdate']) && $_GET['errNurseInfoUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : '' ?>
                <?= (isset($_GET['succUpdateNurse']) && $_GET['succUpdateNurse'] == "Successfully_updated_information") ? '<span class="text-success">Successfully updated information!</span>' : '' ?>
            </div>

            <form action="nurseProfile.php" method="post">
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
                        header("location:nurseProfile.php?errConfirmPass=password_did_not_match");
                        exit(0);
                    } else {
                        $hashPass = password_hash($newPassword, PASSWORD_DEFAULT);
                        $sql = "UPDATE nurse_receptionist SET nPassword = :password WHERE nId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:nurseProfile.php?succUpdatePass=Successfully_updated_password");
                        exit(0);
                    }
                } else {
                    header("location:nurseProfile.php?errCurrPass=incorrect_current_password");
                    ob_end_flush();
                    exit(0);
                }
            }
            ?>

            <div class="text-center my-4">
                <?= (isset($_GET['succUpdatePass']) && $_GET['succUpdatePass'] == "Successfully_updated_password") ? '<span class="text-success">Successfully updated password!</span>' : '' ?>
            </div>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Password</h1>
            </div>

            <form action="nurseProfile.php" method="post">
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

            <hr class="featurette-divider">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Profile Image</h1>
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
                    header("location:nurseProfile.php?errorImgExt=image_is_not_valid");
                    exit(0);
                }

                // Check if the image size is valid
                if ($profileImg['size'] > 5000000) {
                    header("location:nurseProfile.php?errImgSize=Image_invalid_size");
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

                header("location:nurseProfile.php?succUpdateImg=Successfully_update_the_img");
                exit(0);
            }
            ?>

            <div class="text-center my-4">
                <?= (isset($_GET['succUpdateImg']) && $_GET['succUpdateImg'] == "Successfully_update_the_img") ? '<span class="text-success">Successfully update the image!</span>' : '' ?>
            </div>

            <form action="nurseProfile.php" class="my-4" method="post" enctype="multipart/form-data">
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
    </main>



    <!-- FOOTER -->
    <footer class="container text-center">
        <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>



    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>