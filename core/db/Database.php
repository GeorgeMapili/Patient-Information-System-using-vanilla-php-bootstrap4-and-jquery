<?php

namespace core\db;
use PDO;

class Database
{

    public function connect(){
        date_default_timezone_set('Asia/Manila');

        // Development Connection
        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        // $dbname = "pis";

        // Remote DB Connection
        $servername = "remotemysql.com";
        $username = "QA9u2YYTw5";
        $password = "zoTCUABrxM";
        $dbname = "QA9u2YYTw5";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }

    }

}