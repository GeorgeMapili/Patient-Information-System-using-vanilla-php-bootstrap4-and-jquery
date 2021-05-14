<?php

session_start();
require_once 'connect.php';

// Health Library live search
if (isset($_REQUEST["term"])) {
    // Prepare a select statement
    $sql = "SELECT * FROM `diseases_treatment` WHERE dtName LIKE :name";

    if ($stmt  = $con->prepare($sql)) {
        // Bind Parameters
        $stmt->bindParam(":name", $param_term, PDO::PARAM_STR);

        // Set Parameters
        $param_term = "%" . $_REQUEST["term"] . "%";

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $resultRow = $stmt->rowCount();

            if ($resultRow > 0) {
                // Fetch result rows as an associative array
                while ($diseaseTreatment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="list-group" id="data">
                        <form action="singlePage.php" method="post">
                            <input type="hidden" name="id" value="'. $diseaseTreatment['dtId'] .'">
                            <input type="submit" class="list-group-item list-group-item-action" name="healthLibraryBtn" value="'. $diseaseTreatment['dtName'] .'">
                        </form>
                    </div>
                    ';
                }
            } else {
                echo "<p class='text-white'>No matches found</p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql";
        }
    }
}

use core\patient\UpdateProfile;
require_once("vendor/autoload.php");

$updateProfile = new UpdateProfile();

// my profile update info
if (isset($_POST['updateInformation'])) {
    $updateProfile->id = $_POST['id'];
    $updateProfile->name = trim(htmlspecialchars($_POST['name']));
    $updateProfile->email = trim(htmlspecialchars($_POST['email']));
    $updateProfile->address = trim(htmlspecialchars($_POST['address']));
    $updateProfile->mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));

    // Check the name if valid
    // if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
    //     header("location:myaccount.php?errName=name_is_not_valid");
    //     exit(0);
    // }
    $updateProfile->checkNameValid();

    // Check if name is already existed
    // $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pName = :name";
    // $stmt = $con->prepare($sql);
    // $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    // $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    // $stmt->execute();

    // $nameCount = $stmt->rowCount();

    // if ($nameCount >= 1) {
    //     header("location:myaccount.php?errName1=name_is_already_taken");
    //     exit(0);
    // }
    $updateProfile->checkNameAlreadyExisted();

    // // Check if the email if already existed
    // $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pEmail = :email";
    // $stmt = $con->prepare($sql);
    // $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    // $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    // $stmt->execute();

    // $emailCount = $stmt->rowCount();

    // if ($emailCount >= 1) {
    //     header("location:myaccount.php?errEmail=email_is_already_existed");
    //     exit(0);
    // }

    $updateProfile->checkEmailAlreadyExisted();

    // // Check the mobile number is already existed
    // $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pMobile = :mobile";
    // $stmt = $con->prepare($sql);
    // $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    // $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
    // $stmt->execute();

    // $mobileCount = $stmt->rowCount();

    // if ($mobileCount >= 1) {
    //     header("location:myaccount.php?errMobile=mobile_number_is_already_existed");
    //     exit(0);
    // }

    $updateProfile->checkMobileNumberAlreadyExisted();

    if ($_SESSION['name'] == $updateProfile->name && $_SESSION['email'] == $updateProfile->email && $_SESSION['address'] == $updateProfile->address && $_SESSION['mobile'] == $updateProfile->mobileNumber) {
        header("location:myaccount.php?errInfo=Nothing_to_update");
        exit(0);
    }

    // $sql = "UPDATE patientappointment SET pName = :name, pEmail = :email, pAddress = :address, pMobile = :mobile WHERE pId = :id";
    // $stmt = $con->prepare($sql);
    // $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    // $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    // $stmt->bindParam(":address", $address, PDO::PARAM_STR);
    // $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
    // $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);

    // if ($stmt->execute()) {
    //     $_SESSION['name'] = $name;
    //     $_SESSION['email'] = $email;
    //     $_SESSION['address'] = $address;
    //     $_SESSION['mobile'] = $mobileNumber;
    //     header("location:myaccount.php?successInfo=Updated_successfully");
    //     exit(0);
    // }
    if($updateProfile->checkUpdatePatient()){

        header("location:myaccount.php?successInfo=Updated_successfully");
        exit(0);

    }

}

if (isset($_POST['updatePassword'])) {

    $updateProfile->currentPassword = trim(htmlspecialchars($_POST['currentPassword']));
    $updateProfile->newPassword = trim(htmlspecialchars($_POST['newPassword']));
    $updateProfile->confirmNewPassword = trim(htmlspecialchars($_POST['confirmNewPassword']));
    $updateProfile->id = $_SESSION['id'];

    if($updateProfile->checkUpdatePassword() == "success_password_update"){

        header("location:myaccount.php?succPass=Successfully_updated_password");
        exit(0);

    }
}

if (isset($_POST['updateImg'])) {

    $img = $_FILES['profileImg'];

    // Image
    $ext = $img['type'];
    $extF = explode('/', $ext);

    // Unique Image Name
    $profileName =  uniqid(rand()) . "." . $extF[1];

    $tmpname = $img['tmp_name'];
    $dest = __DIR__ . "/upload/user_profile_img/" . $profileName;

    // Check if the image extension is valid
    $allowed = array('jpg', 'jpeg', 'png');
    $filesize = $img['size'];
    if (!in_array($extF[1], $allowed)) {
        header("location:myaccount.php?errInvalidImg=Invalid_image_only(jpg,jpeg,png)");
        exit(0);
    }

    // Check the file size is valid
    if ($img['size'] > 5000000) {
        header("location:myaccount.php?errImgSize=Invalid_image_size_ONLY_less_than_5MB");
        exit(0);
    }

    // Current Profile Img
    $currentProfile = $_SESSION['profile'];

    // New Profile Img
    $newProfile = $profileName;

    // Path of the current profile
    $path = __DIR__ . "/upload/user_profile_img/" . $currentProfile;

    // Remove the img of the current profile
    unlink($path);

    // Add the img of the new profile
    move_uploaded_file($tmpname, $dest);

    // Set the new Value of the img
    $_SESSION['profile'] = $newProfile;

    // Update the img
    $sql = "UPDATE patientappointment SET pProfile = :profile WHERE pId = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":profile", $newProfile, PDO::PARAM_STR);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

    header("location:myaccount.php?succUpdateImg=Successfully_update_the_img");
    exit(0);
}
