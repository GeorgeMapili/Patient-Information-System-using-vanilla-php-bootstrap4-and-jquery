<?php

namespace core\patient;
session_start();
use PDO;

class Login
{

    public $email;
    public $password;
    
    private $con;

    public function connect()
    {
        date_default_timezone_set('Asia/Manila');

        // Development Connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pis";

        // Remote DB Connection
        // $servername = "remotemysql.com";
        // $username = "QA9u2YYTw5";
        // $password = "zoTCUABrxM";
        // $dbname = "QA9u2YYTw5";

        try {
            $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

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

        header("location:../../main.php");
        exit(0);

    }else{

        header("location:../../index.php");
        exit(0);

    }

}