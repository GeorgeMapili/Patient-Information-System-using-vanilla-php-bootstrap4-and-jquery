<?php

namespace core\db;
use PDO;

class Database
{

    public function connect(){
        date_default_timezone_set('Asia/Manila');

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pis";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }

    }

}