<?php

namespace core\patient;
require_once(dirname(dirname(__DIR__)). "/vendor/autoload.php");
session_start();
use PDO;
use core\db\Database;

class Login extends Database
{

    public $email;
    public $password;

    public function loginUser()
    {

        $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();


        $tryCount = 0;

        while ($patientUser = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($this->password, $patientUser['pPassword'])) {

                if($_POST['g-recaptcha-response'] === null){

                    $_SESSION['id'] = $patientUser['pId'];
                    $_SESSION['name'] = $patientUser['pName'];
                    $_SESSION['email'] = $patientUser['pEmail'];
                    $_SESSION['address'] = $patientUser['pAddress'];
                    $_SESSION['age'] = $patientUser['pAge'];
                    $_SESSION['gender'] = $patientUser['pGender'];
                    $_SESSION['mobile'] = $patientUser['pMobile'];
                    $_SESSION['profile'] = $patientUser['pProfile'];
                    $_SESSION['log_login'] = date("m/d/y h:iA", time());
    
                    $_SESSION['log_home'] = null;
                    $_SESSION['log_appointment'] = null;
                    $_SESSION['log_send_appointment'] = null;
                    $_SESSION['log_doctor'] = null;
                    $_SESSION['log_contact'] = null;
                    $_SESSION['log_send_contact'] = null;
                    $_SESSION['log_library'] = null;
                    $_SESSION['log_health_information'] = null;
                    $_SESSION['log_covid_19_update'] = null;
                    $_SESSION['log_current_appointment'] = null;
                    $_SESSION['log_accepted_appointment'] = null;
                    $_SESSION['log_finished_appointment'] = null;
                    $_SESSION['log_view_certificate'] = null;
                    $_SESSION['log_update_information'] = null;
                    $_SESSION['log_update_info'] = null;
                    $_SESSION['log_update_pass'] = null;
                    $_SESSION['log_update_img'] = null;
                    $_SESSION['log_update_history'] = null;
    
                    return "login_success";

                }else{

                    $res = $this->post_captcha($_POST['g-recaptcha-response']);
                    if (!$res['success']) {
                
                        header("location:../../index.php?error=check-the-security-CAPTCHA-box");
                        exit(0);
                
                    }
    
                    $_SESSION['id'] = $patientUser['pId'];
                    $_SESSION['name'] = $patientUser['pName'];
                    $_SESSION['email'] = $patientUser['pEmail'];
                    $_SESSION['address'] = $patientUser['pAddress'];
                    $_SESSION['age'] = $patientUser['pAge'];
                    $_SESSION['gender'] = $patientUser['pGender'];
                    $_SESSION['mobile'] = $patientUser['pMobile'];
                    $_SESSION['profile'] = $patientUser['pProfile'];
                    $_SESSION['log_login'] = date("m/d/y h:iA", time());
    
                    $_SESSION['log_home'] = null;
                    $_SESSION['log_appointment'] = null;
                    $_SESSION['log_send_appointment'] = null;
                    $_SESSION['log_doctor'] = null;
                    $_SESSION['log_contact'] = null;
                    $_SESSION['log_send_contact'] = null;
                    $_SESSION['log_library'] = null;
                    $_SESSION['log_health_information'] = null;
                    $_SESSION['log_covid_19_update'] = null;
                    $_SESSION['log_current_appointment'] = null;
                    $_SESSION['log_accepted_appointment'] = null;
                    $_SESSION['log_finished_appointment'] = null;
                    $_SESSION['log_view_certificate'] = null;
                    $_SESSION['log_update_information'] = null;
                    $_SESSION['log_update_info'] = null;
                    $_SESSION['log_update_pass'] = null;
                    $_SESSION['log_update_img'] = null;
                    $_SESSION['log_update_history'] = null;
    
                    return "login_success";

                }

            } else {

                header("location:../../index.php?errPass=Incorrect_password");
                exit(0);
            }
        }

        $patientCount = $stmt->rowCount();

        if ($patientCount == 0) {
            header("location:../../index.php?errEmail=Incorrect_email");
            exit(0);
        }

    }

    public function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            // 'secret' => '6Lch-PgaAAAAALVk7SOe2vQfDbmc-TLyTeI86fJm',
            'secret' => '6LdSbVwbAAAAAI_vqECErBWhMLyJW46HJU51vQv_',
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

    public function requireAllFields()
    {

        if(empty($this->email) || empty($this->password)){
            header("location:../../index.php?errRequired=require_all_fields");
            exit(0);
        }

    }

}

$login = new Login();

if (isset($_POST['login'])) {

    $_POST['g-recaptcha-response'] = $_POST['g-recaptcha-response'] ?? null;

    $login->email = trim(htmlspecialchars($_POST['email']));
    $login->password = trim(htmlspecialchars($_POST['password']));

    $login->requireAllFields();

    if($login->loginUser() == "login_success"){

        header("location:../../home.php");
        exit(0);

    }else{

        header("location:../../index.php");
        exit(0);

    }

}else{
    header("location:../../index.php");
    exit;
}