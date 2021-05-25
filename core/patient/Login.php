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

}

$login = new Login();

if (isset($_POST['login'])) {

    $login->email = trim(htmlspecialchars($_POST['email']));
    $login->password = trim(htmlspecialchars($_POST['password']));

    if($login->loginUser() == "login_success"){

        header("location:../../home.php");
        exit(0);

    }else{

        header("location:../../index.php");
        exit(0);

    }

}