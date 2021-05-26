<?php
date_default_timezone_set('Asia/Manila');

// Development Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pis";

// Remote DB Connection
// $servername = "remotemysql.com";
// $username = "diseJ0X2n6";
// $password = "oQScQg8fm3";
// $dbname = "diseJ0X2n6";

try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
