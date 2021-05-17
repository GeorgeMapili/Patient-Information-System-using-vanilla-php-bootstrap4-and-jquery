<?php

session_start();

if(isset($_POST['logout'])){

    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['email']);
    unset($_SESSION['address']);
    unset($_SESSION['age']);
    unset($_SESSION['gender']);
    unset($_SESSION['mobile']);
    unset($_SESSION['profile']);
    
    header("location:index.php");
    exit(0);
}else{
    header("location:home.php");
    exit(0);
}


