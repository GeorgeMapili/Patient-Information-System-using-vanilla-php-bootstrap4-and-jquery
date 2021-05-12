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
            header("location:myaccount.php?errName=name_is_not_valid");
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
            header("location:myaccount.php?errName1=name_is_already_taken");
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
            header("location:myaccount.php?errEmail=email_is_already_existed");
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
            header("location:myaccount.php?errMobile=mobile_number_is_already_existed");
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
                    header("location:myaccount.php?errCurrPass1=Confirm_Password_do_not_match");
                    exit(0);
                }
            } else {
                header("location:myaccount.php?errCurrPass=Incorrect_current_password");
                exit(0);
            }
        }

    }

}