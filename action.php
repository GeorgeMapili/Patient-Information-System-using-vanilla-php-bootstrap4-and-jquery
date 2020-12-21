<?php

session_start();
require_once 'connect.php';

// Health Library live search
if (isset($_REQUEST["term"])) {
    // Prepare a select statement
    $sql = "SELECT * FROM `diseases&treatment` WHERE dtName LIKE :name";

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
                        <a href="singlePage.php?id=' . $diseaseTreatment['dtId'] . '" class="list-group-item list-group-item-action">' . $diseaseTreatment['dtName'] . '</a>
                    </div>
                    ';
                }
            } else {
                echo "<p>No matches found</p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql";
        }
    }
}


// my profile update info
if (isset($_POST['updateInformation'])) {
    $id = $_POST['id'];
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $address = trim(htmlspecialchars($_POST['address']));
    $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));

    // Check the name if valid
    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
        header("location:myaccount.php?errName=name_is_not_valid");
        exit(0);
    }

    // Check if name is already existed
    $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pName = :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->execute();

    $nameCount = $stmt->rowCount();

    if ($nameCount >= 1) {
        header("location:myaccount.php?errName1=name_is_already_taken");
        exit(0);
    }

    // Check if the email if already existed
    $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pEmail = :email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    $emailCount = $stmt->rowCount();

    if ($emailCount >= 1) {
        header("location:myaccount.php?errEmail=email_is_already_existed");
        exit(0);
    }

    // Check the mobile number is already existed
    $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pMobile = :mobile";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
    $stmt->execute();

    $mobileCount = $stmt->rowCount();

    if ($mobileCount >= 1) {
        header("location:myaccount.php?errMobile=mobile_number_is_already_existed");
        exit(0);
    }

    if ($_SESSION['name'] == $name && $_SESSION['email'] == $email && $_SESSION['address'] == $address && $_SESSION['mobile'] == $mobileNumber) {
        header("location:myaccount.php?errInfo=Nothing_to_update");
        exit(0);
    }

    $sql = "UPDATE patientappointment SET pName = :name, pEmail = :email, pAddress = :address, pMobile = :mobile WHERE pId = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":address", $address, PDO::PARAM_STR);
    $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['address'] = $address;
        $_SESSION['mobile'] = $mobileNumber;
        header("location:myaccount.php?successInfo=Updated_successfully");
        exit(0);
    }
}

if (isset($_POST['updatePassword'])) {

    $currentPassword = trim(htmlspecialchars($_POST['currentPassword']));
    $newPassword = trim(htmlspecialchars($_POST['newPassword']));
    $confirmNewPassword = trim(htmlspecialchars($_POST['confirmNewPassword']));

    $sql = "SELECT * FROM patientappointment WHERE pId = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

    while ($usePass = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($currentPassword, $usePass['pPassword'])) {
            if ($newPassword === $confirmNewPassword) {
                // hash the new password 
                $hashPass = password_hash($newPassword, PASSWORD_DEFAULT);

                $sql = "UPDATE patientappointment SET pPassword = :password WHERE pId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();
                header("location:myaccount.php?succPass=Successfully_updated_password");
                exit(0);
            } else {
                header("location:myaccount.php?errCurrPass1=Confirm_Password_do_not_match");
                exit(0);
            }
        } else {
            header("location:myaccount.php?errCurrPass=Incorrect_current_password");
            exit(0);
        }
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
