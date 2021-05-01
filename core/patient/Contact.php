<?php

namespace core\patient;
use PDO;

class Contact
{

    public $id;
    public $name;
    public $email;
    public $address;
    public $mobileNumber;
    public $message;

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

    public function insertMessage()
    {

        $sql = "INSERT INTO message(msgPatientId,msgPatientName,msgContent)VALUES(:id,:name,:content)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":content", $this->message, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "success_message";
        }

    }

}