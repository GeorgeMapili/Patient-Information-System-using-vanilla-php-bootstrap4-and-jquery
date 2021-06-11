<?php

namespace core\patient;
require_once(dirname(dirname(__DIR__)). "/vendor/autoload.php");
use PDO;
use core\db\Database;

class UpdateProfile extends Database
{

    public $id;
    public $name;
    public $email;
    public $address;
    public $mobileNumber;

    public $currentPassword;
    public $newPassword;
    public $confirmNewPassword;

    public function checkNameValid()
    {

        // Check the name if valid
        if (!preg_match("/^([a-zA-Z' ]+)$/", $this->name)) {
            header("location:../../account.php?errName=name_is_not_valid");
            exit(0);
        }
        return "name_valid";

    }

    public function checkNameAlreadyExisted()
    {

        //Check if name is already existed
        $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pName = :name";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->execute();

        $nameCount = $stmt->rowCount();

        if ($nameCount >= 1) {
            header("location:../../account.php?errName1=name_is_already_taken");
            exit(0);
        }
        return "name_valid";

    }

    public function checkEmailAlreadyExisted()
    {

        // Check if the email if already existed
        $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pEmail = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $emailCount = $stmt->rowCount();

        if ($emailCount >= 1) {
            header("location:../../account.php?errEmail=email_is_already_existed");
            exit(0);
        }
        return "email_valid";

    }

    public function checkMobileNumberAlreadyExisted()
    {

        // Check the mobile number is already existed
        $sql = "SELECT * FROM patientappointment WHERE pId <> :id AND pMobile = :mobile";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(":mobile", $this->mobileNumber, PDO::PARAM_STR);
        $stmt->execute();

        $mobileCount = $stmt->rowCount();

        if ($mobileCount >= 1) {
            header("location:../../account.php?errMobile=mobile_number_is_already_existed");
            exit(0);
        }
        return "mobile_valid";

    }

    public function checkUpdatePatient()
    {
        $sql = "UPDATE patientappointment SET pName = :name, pEmail = :email, pAddress = :address, pMobile = :mobile WHERE pId = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":address", $this->address, PDO::PARAM_STR);
        $stmt->bindParam(":mobile", $this->mobileNumber, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['name'] = $this->name;
            $_SESSION['email'] = $this->email;
            $_SESSION['address'] = $this->address;
            $_SESSION['mobile'] = $this->mobileNumber;
            return "update_success";
        }

    }

    public function checkUpdatePassword()
    {

        $sql = "SELECT * FROM patientappointment WHERE pId = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        while ($usePass = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($this->currentPassword, $usePass['pPassword'])) {
                if ($this->newPassword === $this->confirmNewPassword) {
                    // hash the new password 
                    $hashPass = password_hash($this->newPassword, PASSWORD_DEFAULT);
    
                    $sql = "UPDATE patientappointment SET pPassword = :password WHERE pId = :id";
                    $stmt = $this->connect()->prepare($sql);
                    $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
                    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
                    $stmt->execute();
                    return "success_password_update";
                } else {
                    header("location:../../account.php?errCurrPass1=Confirm_Password_do_not_match");
                    exit(0);
                }
            } else {
                header("location:../../account.php?errCurrPass=Incorrect_current_password");
                exit(0);
            }
        }

    }

}

session_start();
$updateProfile = new UpdateProfile();
$db = new Database();

$con = $db->connect();

// my profile update info
if (isset($_POST['updateInformation'])) {
    $updateProfile->id = $_POST['id'];
    $updateProfile->name = trim(htmlspecialchars($_POST['name']));
    $updateProfile->email = trim(htmlspecialchars($_POST['email']));
    $updateProfile->address = trim(htmlspecialchars($_POST['address']));
    $updateProfile->mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));

    $updateProfile->checkNameValid();

    $updateProfile->checkNameAlreadyExisted();

    $updateProfile->checkEmailAlreadyExisted();

    $updateProfile->checkMobileNumberAlreadyExisted();

    if ($_SESSION['name'] == $updateProfile->name && $_SESSION['email'] == $updateProfile->email && $_SESSION['address'] == $updateProfile->address && $_SESSION['mobile'] == $updateProfile->mobileNumber) {
        header("location:../../account.php?errInfo=Nothing_to_update");
        exit(0);
    }

    if($updateProfile->checkUpdatePatient()){

        header("location:../../account.php?successInfo=Updated_successfully");
        $_SESSION['log_update_info'] = true;
        exit(0);

    }

}elseif (isset($_POST['updatePassword'])) {

    $updateProfile->currentPassword = trim(htmlspecialchars($_POST['currentPassword']));
    $updateProfile->newPassword = trim(htmlspecialchars($_POST['newPassword']));
    $updateProfile->confirmNewPassword = trim(htmlspecialchars($_POST['confirmNewPassword']));
    $updateProfile->id = $_SESSION['id'];

    if($updateProfile->checkUpdatePassword() == "success_password_update"){

        header("location:../../account.php?succPass=Successfully_updated_password");
        $_SESSION['log_update_pass'] = true;
        exit(0);

    }

}elseif (isset($_POST['updateImg'])) {

    $img = $_FILES['profileImg'];

    // Image
    $ext = $img['type'];
    $extF = explode('/', $ext);

    // Unique Image Name
    $profileName =  uniqid(rand()) . "." . $extF[1];

    $tmpname = $img['tmp_name'];
    $dest = __DIR__ . "./../../upload/user_profile_img/" . $profileName;

    // Check if the image extension is valid
    $allowed = array('jpg', 'jpeg', 'png');
    $filesize = $img['size'];
    if (!in_array($extF[1], $allowed)) {
        header("location:../../account.php?errInvalidImg=Invalid_image_only(jpg,jpeg,png)");
        exit(0);
    }

    // Check the file size is valid
    if ($img['size'] > 5000000) {
        header("location:../../account.php?errImgSize=Invalid_image_size_ONLY_less_than_5MB");
        exit(0);
    }

    // Current Profile Img
    $currentProfile = $_SESSION['profile'];

    // New Profile Img
    $newProfile = $profileName;

    // Path of the current profile
    $path = __DIR__ . "./../../upload/user_profile_img/" . $currentProfile;

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

    header("location:../../account.php?succUpdateImg=Successfully_update_the_img");
    $_SESSION['log_update_img'] = true;
    exit(0);
}else{
    header("location:../../home.php");
    exit;
}