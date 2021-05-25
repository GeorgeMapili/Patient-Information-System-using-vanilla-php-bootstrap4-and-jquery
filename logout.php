<?php

session_start();
require_once('connect.php');

if(isset($_POST['logout'])){

    $ip = $_SERVER['REMOTE_ADDR']; //Client IP
    $login = $_SESSION['log_login'];
    $logout = date("m/d/y h:iA", time()); //Client Date
    $id = $_SESSION['id']; //SESSION_ID
    $name = $_SESSION['name']; //SESSION_NAME

    $opens = array();
    
    if($_SESSION['log_home'] === true){
        $opens[] = "home page";
    }

    if($_SESSION['log_send_appointment'] === true && $_SESSION['log_appointment'] === true){
        $opens[] = "sent appointment";
    }elseif($_SESSION['log_appointment'] === true){
        $opens[] = "appointment page";
    }

    if($_SESSION['log_doctor'] === true){
        $opens[] = "doctor page";
    }

    if($_SESSION['log_send_contact'] === true && $_SESSION['log_contact'] === true){
        $opens[] = "sent contact message";
    }elseif($_SESSION['log_contact'] === true){
        $opens[] = "contact page";
    }

    if($_SESSION['log_library'] === true && $_SESSION['log_health_information'] === true && $_SESSION['log_covid_19_update'] === true){
        $opens[] = "health information page, covid-19 update";
    }elseif($_SESSION['log_library'] === true && $_SESSION['log_health_information'] === true){
        $opens[] = "health information page";
    }elseif($_SESSION['log_library'] === true && $_SESSION['log_covid_19_update'] === true){
        $opens[] = "covid-19 update";
    }elseif($_SESSION['log_library'] === true){
        $opens[] = "library page";
    }

    if($_SESSION['log_current_appointment'] === true){
        $opens[] = "current appointment page";
    }

    if($_SESSION['log_accepted_appointment'] === true){
        $opens[] = "accepted appointment page";
    }

    if($_SESSION['log_finished_appointment'] === true && $_SESSION['log_view_certificate'] === true){
        $opens[] = "view medical certificate";
    }elseif($_SESSION['log_finished_appointment'] === true){
        $opens[] = "finished appointment page";
    }

    if($_SESSION['log_update_information'] === true && $_SESSION['log_update_info'] === true && $_SESSION['log_update_pass'] === true && $_SESSION['log_update_img'] === true){
        $opens[] = "updated information, updated password, updated image";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_info'] === true && $_SESSION['log_update_pass'] === true){
        $opens[] = "updated information, updated password";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_pass'] === true && $_SESSION['log_update_img'] === true){
        $opens[] = "updated password, updated image";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_info'] === true && $_SESSION['log_update_img'] === true){
        $opens[] = "updated information, updated image";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_info'] === true){
        $opens[] = "updated information";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_pass'] === true){
        $opens[] = "updated password";
    }elseif($_SESSION['log_update_information'] === true && $_SESSION['log_update_img'] === true){
        $opens[] = "updated image";
    }elseif($_SESSION['log_update_information'] === true){
        $opens[] = "account page";
    }

    if($_SESSION['log_update_history'] === true){
        $opens[] = "appointment history page";
    }

    $actions = implode(", ",$opens);

    $log_user_role = "patient";

    $sql = "INSERT INTO audit_log(log_login, log_logout, log_ip, log_user_id, log_user_name, log_user_role, log_action)VALUES(:log_login, :log_logout, :log_ip, :log_user_id, :log_user_name, :log_user_role, :log_action)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":log_login", $login, PDO::PARAM_STR);
    $stmt->bindParam(":log_logout", $logout, PDO::PARAM_STR);
    $stmt->bindParam(":log_ip", $ip, PDO::PARAM_STR);
    $stmt->bindParam(":log_user_id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":log_user_name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":log_user_role", $log_user_role, PDO::PARAM_STR);
    $stmt->bindParam(":log_action", $actions, PDO::PARAM_STR);
    $stmt->execute();

    // Logs
    unset($_SESSION['log_login']);
    unset($_SESSION['log_home']);
    unset($_SESSION['log_appointment']);
    unset($_SESSION['log_send_appointment']);
    unset($_SESSION['log_doctor']);
    unset($_SESSION['log_contact']);
    unset($_SESSION['log_send_contact']);
    unset($_SESSION['log_library']);
    unset($_SESSION['log_health_information']);
    unset($_SESSION['log_covid_19_update']);
    unset($_SESSION['log_current_appointment']);
    unset($_SESSION['log_accepted_appointment']);
    unset($_SESSION['log_finished_appointment']);
    unset($_SESSION['log_view_certificate']);
    unset($_SESSION['log_update_information']);
    unset($_SESSION['log_update_info']);
    unset($_SESSION['log_update_pass']);
    unset($_SESSION['log_update_img']);
    unset($_SESSION['log_update_history']);

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


