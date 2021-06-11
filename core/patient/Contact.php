<?php

namespace core\patient;
require_once(dirname(dirname(__DIR__)). "/vendor/autoload.php");
use PDO;
use core\db\Database;

class Contact extends Database
{

    public $id;
    public $name;
    public $email;
    public $address;
    public $mobileNumber;
    public $message;

    public function requireAll()
    {
        if(empty($this->message)){
            header("location:contact.php?errorMsg=require_all_fields");
            exit(0);
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