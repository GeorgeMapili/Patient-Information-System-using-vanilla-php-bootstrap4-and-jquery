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

    public function allCheckRequired(){
        if(empty($this->name) || empty($this->email) || empty($this->address) || empty($this->age) || empty($this->gender) | empty($this->mobileNumber) || empty($this->password) || empty($this->confirmPassword) || empty($this->profileImg)/* */){
            header("location:../../register.php?errRequired=require_all_fields&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "require_all_fields";
    }

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

    public function validateBirthday()
    {
        if(date_diff(date_create($this->age), date_create('now'))->y <= 10){
            header("location:../../register.php?errBirthday=invalid_date&name=$this->name&email=$this->email&address=$this->address&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
    }

    public function mobileNumberValidation()
    {
        if(!preg_match("/^(09|\+639)\d{9}$/",$this->mobileNumber)){
            header("location:../../register.php?errMobileValidation=incorrect_mobile_number_format&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender");
            exit(0);
        }
        return "mobile_number_incorrect_format";
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

    public function passwordMinimumCharacter()
    {
        if(strlen($this->password) < 6){
            header("location:../../register.php?errPass1=password_minimum_character&name=$this->name&email=$this->email&address=$this->address&age=$this->age&gender=$this->gender&mobile=$this->mobileNumber");
            exit(0);
        }
        return "password_minimum_character";
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
            move_uploaded_file($this->tmpname, $this->dest);
            return "register_success";
        }

    }

}

$register = new Register();

if (isset($_POST['register'])){

    function post_captcha($user_response){
        $fields_string = '';
        $fields = array(
            'secret' => '6Lch-PgaAAAAALVk7SOe2vQfDbmc-TLyTeI86fJm',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    // Call the function post_captcha
    $res = post_captcha($_POST['g-recaptcha-response']);
  
    $register->name = trim(htmlspecialchars($_POST['name']));
    $register->email = trim(htmlspecialchars($_POST['email']));
    $register->address = trim(htmlspecialchars($_POST['address']));
    $register->age = trim(htmlspecialchars($_POST['age']));
    $register->gender = trim(htmlspecialchars($_POST['gender']));
    $register->mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
    $register->password = trim(htmlspecialchars($_POST['password']));
    $register->confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));
    $register->profileImg = $_FILES['profileImg'];

    $register->allCheckRequired();
    $register->nameValid();
    $register->nameCheckTaken();
    $register->emailValid();
    $register->emailCheckTaken();
    $register->validateBirthday();
    $register->mobileNumberValidation();
    $register->mobileNumberCheckTaken();
    $register->passwordMinimumCharacter();
    $register->passwordMatchCheck();
    $register->imageData();
    $register->imageExtesionCheck();
    $register->imageSizeCheck();
    $register->hashPassword();

    if (!$res['success']) {
    
        header("location:../../register.php?error=check-the-security-CAPTCHA-box&name=$register->name&email=$register->email&address=$register->address&age=$register->age&gender=$register->gender&mobile=$register->mobileNumber");
        exit(0);

    } else {

        if($register->insertNewPatient() == "register_success"){
            header("location:../../index.php?RegSuccess=Register_success");
            exit(0);
        }else{
            header("location:../../register.php");
            exit(0);
        }

    }


}else{
    header("location:../../register.php");
    exit;
}