<?php

class patientTest extends \PHPUnit\Framework\TestCase{

    // public function testCheckNameValid()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->name = "Test";
    //     $result = $register_name->nameValid();

    //     $this->assertEquals("name_valid",$result);
    // }

    // public function testCheckNameTaken()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->name = "Test";
    //     $result = $register_name->nameCheckTaken();

    //     $this->assertEquals("name_is_not_taken",$result);
    // }

    // public function testCheckEmailValid()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->email = "test@gmail.com";
    //     $result = $register_name->emailValid();

    //     $this->assertEquals("email_valid",$result);
    // }

    // public function testCheckEmailTaken()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->email = "newtest@gmail.com";
    //     $result = $register_name->emailCheckTaken();

    //     $this->assertEquals("email_is_not_taken",$result);
    // }

    // public function testCheckMobileNumberTaken()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->mobileNumber = "+639550596678";
    //     $result = $register_name->mobileNumberCheckTaken();

    //     $this->assertEquals("mobile_number_is_not_taken",$result);
    // }

    // public function testCheckPasswordMatch()
    // {
    //     $register_name = new core\patient\Register();
    //     $register_name->password = "123456";
    //     $register_name->confirmPassword = "123456";
    //     $result = $register_name->passwordMatchCheck();

    //     $this->assertEquals("password_match",$result);
    // }

    // public function testCheckImageExtension()
    // {

    //     $register_name = new core\patient\Register();
    //     $register_name->profileImg = ["name"=> "qwe.jpeg", "type" => "image/jpeg", "tmp_name" => "C:\'xampp\'tmp\phpFC37.tmp", "error" => 0, "size" => "22000"];
    //     $register_name->imageData();
    //     $result = $register_name->imageExtesionCheck();

    //     $this->assertEquals("image_valid_extension",$result);

    // }

    // public function testCheckImageSize()
    // {

    //     $register_name = new core\patient\Register();
    //     $register_name->profileImg = ["name"=> "qwe.jpeg", "type" => "image/jpeg", "tmp_name" => "C:\'xampp\'tmp\phpFC37.tmp", "error" => 0, "size" => "22000"];
    //     $result = $register_name->imageSizeCheck();

    //     $this->assertEquals("image_size_check",$result);

    // }

    // public function testCheckRegister()
    // {

    //     $register_name = new core\patient\Register();
    //     $register_name->name = "Unit Test"; //Need to change this every test
    //     $register_name->email = "unittest@gmail.com"; //Need to change this every test
    //     $register_name->address = "Street Address";
    //     $register_name->age = 21;
    //     $register_name->gender = "male";
    //     $register_name->mobileNumber = "+639550596483"; //Need to change this every test
    //     $register_name->password = "123456";
    //     $register_name->confirmPassword = "123456";
    //     $register_name->profileImg = ["name"=> "qwe.jpeg", "type" => "image/jpeg", "tmp_name" => "C:\'xampp\'tmp\phpFC37.tmp", "error" => 0, "size" => "22000"];
    //     $register_name->nameValid();
    //     $register_name->nameCheckTaken();
    //     $register_name->emailValid();
    //     $register_name->emailCheckTaken();
    //     $register_name->mobileNumberCheckTaken();
    //     $register_name->passwordMatchCheck();
    //     $register_name->imageData();
    //     $register_name->imageExtesionCheck();
    //     $register_name->imageSizeCheck();
    //     $register_name->hashPassword();
    //     $result = $register_name->insertNewPatient();

    //     $this->assertEquals("register_success",$result);

    // }

    // public function testCheckLogin()
    // {
    //     $login = new core\patient\Login();
    //     $login->email = "testone@gmail.com";
    //     $login->password = "123456";
    //     $result = $login->loginUser();

    //     $this->assertEquals("login_success",$result);
    // }

    public function testCheckDatePassBy()
    {
        $appointment = new core\patient\Appointment;
        date_default_timezone_set("Asia/Manila");
        $appointment->aDate = date("Y/m/d"); //Change this not on the weekends
        $result = $appointment->datePassByCheck();

        $this->assertEquals("valid_date", $result);
    }

    // public function testCheckNoWeekends()
    // {
    //     $appointment = new core\patient\Appointment;
    //     date_default_timezone_set("Asia/Manila");
    //     $appointment->aDate = date("Y/m/d"); //Change this not on the weekends
    //     $result = $appointment->noWeekEndServiceCheck();

    //     $this->assertEquals("valid_date", $result);
    // }

    public function testCheckAlreadyAppointmentDiffDoctor()
    {
        $appointment = new core\patient\Appointment;
        date_default_timezone_set("Asia/Manila");
        $appointment->aDate = date("Y/m/d"); //Change this not on the weekends
        $appointment->aTime = "1:00-2:00 P.M."; //Change this not on the weekends
        $appointment->pDoctor = "Dr. Caspar Mclean"; //Change this not on the weekends
        $result = $appointment->alreadyHaveAnAppointDiffDoctor();

        $this->assertEquals("valid_appointment", $result);
    }

    public function testCheckDuplicateAppointment()
    {
        $appointment = new core\patient\Appointment;
        date_default_timezone_set("Asia/Manila");
        $appointment->aDate = date("Y/m/d"); //Change this not on the weekends
        $appointment->aTime = "1:00-2:00 P.M."; //Change this not on the weekends
        $appointment->pDoctor = "Dr. Caspar Mclean"; //Change this not on the weekends
        $appointment->pId = 4; //Change this not on the weekends
        $result = $appointment->alreadyHaveAnAppointDiffDoctor();

        $this->assertEquals("valid_appointment", $result);
    }

    public function testCheckSameDateAppointment()
    {
        $appointment = new core\patient\Appointment;
        date_default_timezone_set("Asia/Manila");
        $appointment->aDate = date("Y/m/d"); //Change this not on the weekends
        $appointment->aTime = "1:00-2:00 P.M."; //Change this not on the weekends
        $appointment->pDoctor = "Dr. Caspar Mclean"; //Change this not on the weekends
        $appointment->pId = 4; //Change this not on the weekends
        $result = $appointment->sameDateAppointCheck();

        $this->assertEquals("valid_appointment", $result);
    }

    public function testCheckInsertMessage()
    {
        $contact = new core\patient\Contact;
        $contact->id = 4;
        $contact->name = "Test One";
        $contact->message = "Tests Message";
        $result = $contact->insertMessage();

        $this->assertEquals("success_message", $result);
    }

    // public function testCheckNameUpdate()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->name = "Unit Test";
    //     $result = $updateProfile->checkNameValid();

    //     $this->assertEquals("name_valid", $result);
    // }

    // public function testCheckNameAlreadyExistedUpdate()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->name = "Unit Test";
    //     $result = $updateProfile->checkNameAlreadyExisted();

    //     $this->assertEquals("name_valid", $result);
    // }

    // public function testCheckEmailAlreadyExistedUpdate()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->email = "unittest@gmail.com";
    //     $result = $updateProfile->checkEmailAlreadyExisted();

    //     $this->assertEquals("email_valid", $result);
    // }

    // public function testCheckMobileNumberAlreadyExistedUpdate()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->mobileNumber = "+639550591400";
    //     $result = $updateProfile->checkMobileNumberAlreadyExisted();

    //     $this->assertEquals("mobile_valid", $result);
    // }

    // public function testCheckUpdateInformation()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->name = "Unit Test";
    //     $updateProfile->email = "unittest@gmail.com";
    //     $updateProfile->address = "East Balabag, Valencia, Negros Oriental";
    //     $updateProfile->mobileNumber = "+639550596483";
    //     $updateProfile->id = 31;
    //     $result = $updateProfile->checkUpdatePatient();

    //     $this->assertEquals("update_success", $result);
    // }

    // public function testCheckUpdatePassword()
    // {
    //     $updateProfile = new core\patient\updateProfile;
    //     $updateProfile->currentPassword = "qwerty";
    //     $updateProfile->newPassword = "123456";
    //     $updateProfile->confirmNewPassword = "123456";
    //     $updateProfile->id = 31;
    //     $result = $updateProfile->checkUpdatePassword();

    //     $this->assertEquals("success_password_update", $result);
    // }


}