<?php
session_start();
require_once '../connect.php';

$ip = $_SERVER['REMOTE_ADDR']; //Client IP
$login = $_SESSION['log_doctor_login'];
$logout = date("m/d/y h:iA", time()); //Client Date
$id = $_SESSION['dId']; //SESSION_ID
$name = $_SESSION['dName']; //SESSION_NAME

$opens = array();

if($_SESSION['log_doctor_dashboard'] === true){
    $opens[] = "dashboard page";
}

if($_SESSION['log_doctor_walkin_patient'] === true){
    $opens[] = "walkin patient page";
}

if($_SESSION['log_doctor_walkin_add_prescription'] === true){
    $opens[] = "walkin add prescription";
}

if($_SESSION['log_doctor_walkin_update_prescription'] === true){
    $opens[] = "walkin update prescription";
}

if($_SESSION['log_doctor_walkin_update_disease'] === true){
    $opens[] = "walkin update disease";
}

if($_SESSION['log_doctor_walkin_watch_medical_information'] === true){
    $opens[] = "watch medical information";
}

if($_SESSION['log_doctor_medical_history'] === true){
    $opens[] = "watch medical history";
}

if($_SESSION['log_doctor_patient_appointment'] === true){
    $opens[] = "patient appointment page";
}

if($_SESSION['log_doctor_patient_appointment_add_prescription'] === true){
    $opens[] = "patient appointment add prescription";
}

if($_SESSION['log_doctor_patient_appointment_update_prescription'] === true){
    $opens[] = "patient appointment update prescription";
}

if($_SESSION['log_doctor_patient_appointment_update_disease'] === true){
    $opens[] = "patient appointment update disease";
}

if($_SESSION['log_doctor_patient_appointment_appointment_history'] === true){
    $opens[] = "patient appointment history";
}

if($_SESSION['log_doctor_incoming_appointment'] === true && $_SESSION['log_doctor_done_appointment'] === true && $_SESSION['log_doctor_cancel_appointment'] === true){
    $opens[] = "done & cancel for incoming appointment";
}elseif($_SESSION['log_doctor_incoming_appointment'] === true && $_SESSION['log_doctor_done_appointment'] === true){
    $opens[] = "done for incoming appointment";
}elseif($_SESSION['log_doctor_incoming_appointment'] === true && $_SESSION['log_doctor_cancel_appointment'] === true){
    $opens[] = "cancel for incoming appointment";
}elseif($_SESSION['log_doctor_incoming_appointment'] === true){
    $opens[] = "incoming appointment page";
}

if($_SESSION['log_doctor_cancelled_appointment'] === true){
    $opens[] = "called appointment page";
}

if($_SESSION['log_doctor_finished_appointment'] === true){
    $opens[] = "finished appointment page";
}

if($_SESSION['log_doctor_lab_appointment'] === true){
    $opens[] = "lab appointment page";
}

if($_SESSION['log_doctor_lab_appointment_add_test'] === true){
    $opens[] = "add lab test for appointment";
}

if($_SESSION['log_doctor_lab_appointment_update_test'] === true){
    $opens[] = "update lab test for appointment";
}

if($_SESSION['log_doctor_lab_appointment_add_result'] === true){
    $opens[] = "add lab result for appointment";
}

if($_SESSION['log_doctor_lab_appointment_update_result'] === true){
    $opens[] = "update lab result for appointment";
}

if($_SESSION['log_doctor_lab_walkin'] === true){
    $opens[] = "walkin lab page";
}

if($_SESSION['log_doctor_lab_walkin_add_test'] === true){
    $opens[] = "add walkin lab test";
}

if($_SESSION['log_doctor_lab_walkin_update_test'] === true){
    $opens[] = "update walkin lab test";
}

if($_SESSION['log_doctor_lab_walkin_add_result'] === true){
    $opens[] = "add walkin lab result";
}

if($_SESSION['log_doctor_lab_walkin_update_result'] === true){
    $opens[] = "update walkin lab result";
}

if($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_info'] === true && $_SESSION['log_doctor_update_pass'] === true && $_SESSION['log_doctor_update_img'] === true){
    $opens[] = "updated info & updated password & updated image";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_info'] === true && $_SESSION['log_doctor_update_pass'] === true){
    $opens[] = "updated info & updated password";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_pass'] === true && $_SESSION['log_doctor_update_img'] === true){
    $opens[] = "updated password & updated image";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_info'] === true && $_SESSION['log_doctor_update_img'] === true){
    $opens[] = "updated info & updated image";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_info'] === true){
    $opens[] = "updated info";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_pass'] === true){
    $opens[] = "updated password";
}elseif($_SESSION['log_doctor_information'] === true && $_SESSION['log_doctor_update_img'] === true){
    $opens[] = "updated image";
}elseif($_SESSION['log_doctor_information'] === true){
    $opens[] = "account page";
}

$actions = implode(", ", $opens);

$log_user_role = "doctor";

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

// Doctor SESSION
unset($_SESSION['dId']);
unset($_SESSION['dName']);
unset($_SESSION['dEmail']);
unset($_SESSION['dAddress']);
unset($_SESSION['dMobile']);
unset($_SESSION['dSpecialization']);
unset($_SESSION['dSpecializationInfo']);
unset($_SESSION['dProfileImg']);
unset($_SESSION['dFee']);

// UPDATE PRESCRIPTION PATIENT APPOINTMENT SESSION
unset($_SESSION['updatePrescription']);
unset($_SESSION['updateAppointmentDisease']);

// UPDATE PRESCRIPTION WALKIN SESSION
unset($_SESSION['updateWalkInPrescription']);
unset($_SESSION['updateDiseaseWalkIn']);

header("location:index.php");
exit(0);
