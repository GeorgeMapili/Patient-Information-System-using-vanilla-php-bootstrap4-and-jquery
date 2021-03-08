<?php
date_default_timezone_set('Asia/Manila');

// Development Connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "pis";

// Remote DB Connection
$servername = "remotemysql.com";
$username = "CTfobJ84TI";
$password = "IYDrpRw3nQ";
$dbname = "CTfobJ84TI";

try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
