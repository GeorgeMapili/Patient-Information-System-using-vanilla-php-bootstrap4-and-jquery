<?php

namespace core\patient;
require_once(dirname(dirname(__DIR__)). "/vendor/autoload.php");
use PDO;
use core\db\Database;

class register extends Database
{

    public $name;
    public $email;
    public $address;
    public $age;
    public $gender;
    public $mobileNumber;
    public $password;
    public $confirmPassword;
    public $profileImg;

    public $profileName;
    public $dest;
    public $extF;
    public $tmpname;
    public $hashPass;

    private $con;

    public function nameValid()
    {
        if (!preg_match("/^([a-zA-Z' ]+)$/", $this->name)) {
            header("location:../../register.php?errName=name_is_not_valid&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "name_valid";
    }

    public function nameCheckTaken()
    {

        $sql = "SELECT * FROM patientappointment WHERE pName= :name";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->execute();

        $nameCount = $stmt->rowCount();

        if ($nameCount >= 1) {
            header("location:../../register.php?errName1=Name_is_already_existed&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "name_is_not_taken";
    }

    public function emailValid()
    {

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            header("location:../../register.php?errEmail1=email_is_not_valid&name=$this->name&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "email_valid";
    }

    public function emailCheckTaken()
    {

        // Check if the email is already taken
        $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $emailCount = $stmt->rowCount();

        if ($emailCount >= 1) {
            header("location:../../register.php?errEmail2=email_already_taken&name=$this->name&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "email_is_not_taken";
    }

    public function mobileNumberCheckTaken()
    {

        // Check if the mobile number is already existed
        $sql = "SELECT * FROM patientappointment WHERE pMobile = :mobile";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":mobile", $this->mobileNumber, PDO::PARAM_INT);
        $stmt->execute();

        $mobileCount = $stmt->rowCount();

        if ($mobileCount >= 1) {
            header("location:../../register.php?errMobile=mobile_number_already_taken&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender");
            exit(0);
        }
        return "mobile_number_is_not_taken";
    }

    public function passwordMatchCheck()
    {

        // Check if the password does not match
        if ($this->password !== $this->confirmPassword) {
            header("location:../../register.php?errPass=password_do_not_match&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "password_match";

    }

    public function imageData()
    {

        // Image
        $ext = $this->profileImg['type'];
        $this->extF = explode('/', $ext);

        // Unique Image Name
        $this->profileName =  uniqid(rand()) . "." . $this->extF[1];

        $this->tmpname = $this->profileImg['tmp_name'];
        $this->dest = "./../../upload/user_profile_img/" . $this->profileName;

    }

    public function imageExtesionCheck()
    {

        // Check if the Extension of image is valid
        $allowed = array('jpg', 'jpeg', 'png');

        if (!in_array(strtolower($this->extF[1]), $allowed)) {
            header("location:../../register.php?errorImg1=image_is_not_valid&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "image_valid_extension";

    }

    public function imageSizeCheck()
    {
        // Check if the image size is valid
        if ($this->profileImg['size'] > 5000000) {
            header("location:../../register.php?errorImg2=image_is_only_less_than_5MB&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "image_size_check";
    }

    public function hashPassword()
    {

        // Hash the password
        $this->hashPass = password_hash($this->password, PASSWORD_DEFAULT);

    }

    public function insertNewPatient()
    {

        $sql = "INSERT into patientappointment (pName,pEmail,pAddress,pAge,pGender,pMobile,pPassword,pProfile)VALUES(:name,:email,:address,:age,:gender,:mobile,:password,:profile)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":address", $this->address, PDO::PARAM_STR);
        $stmt->bindParam(":age", $this->age, PDO::PARAM_STR);
        $stmt->bindParam(":gender", $this->gender, PDO::PARAM_STR);
        $stmt->bindParam(":mobile", $this->mobileNumber, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->hashPass, PDO::PARAM_STR);
        $stmt->bindParam(":profile", $this->profileName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            //move_uploaded_file($this->tmpname, $this->dest);
            return "register_success";
        }

    }

}

$register = new Register();

if (isset($_POST['register'])) {
    $register->name = trim(htmlspecialchars($_POST['name']));
    $register->email = trim(htmlspecialchars($_POST['email']));
    $register->address = trim(htmlspecialchars($_POST['address']));
    $register->age = trim(htmlspecialchars($_POST['age']));
    $register->gender = trim(htmlspecialchars($_POST['gender']));
    $register->mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
    $register->password = trim(htmlspecialchars($_POST['password']));
    $register->confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));
    $register->profileImg = $_FILES['profileImg'];

    $register->nameValid();
    $register->nameCheckTaken();
    $register->emailValid();
    $register->emailCheckTaken();
    $register->mobileNumberCheckTaken();
    $register->passwordMatchCheck();
    $register->imageData();
    $register->imageExtesionCheck();
    $register->imageSizeCheck();
    $register->hashPassword();
    if($register->insertNewPatient() == "register_success"){
        header("location:../../index.php?RegSuccess=Register_success");
        exit(0);
    }else{
        header("location:../../register.php");
        exit(0);
    }
}