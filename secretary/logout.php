<?php

session_start();
require_once('../connect.php');

if(isset($_POST['logout'])){

$ip = $_SERVER['REMOTE_ADDR']; //Client IP
$login = $_SESSION['log_secretary_login'];
$logout = date("m/d/y h:iA", time()); //Client Date
$id = $_SESSION['nId']; //SESSION_ID
$name = $_SESSION['nName']; //SESSION_NAME

$opens= array();

if($_SESSION['log_secretary_dashboard'] === true){
    $opens[] = "dashboard page";
}

if($_SESSION['log_secretary_appointment'] === true && $_SESSION['log_secretary_accept'] === true && $_SESSION['log_secretary_cancel'] === true){
    $opens[] = "accept & cancel appointment";
}elseif($_SESSION['log_secretary_appointment'] === true && $_SESSION['log_secretary_accept'] === true){
    $opens[] = "accept appointment";
}elseif($_SESSION['log_secretary_appointment'] === true && $_SESSION['log_secretary_cancel'] === true){
    $opens[] = "cancel appointment";
}elseif($_SESSION['log_secretary_appointment'] === true){
    $opens[] = "pending appointment page";
}

if($_SESSION['log_secretary_patient_appointment'] === true && $_SESSION['log_secretary_generate_bill_appointment'] === true && $_SESSION['log_secretary_discharge_appointment'] === true && $_SESSION['log_secretary_pdf_appointment'] === true){
    $opens[] = "generate bill & discharge & print billings for patient appointment";
}elseif($_SESSION['log_secretary_patient_appointment'] === true && $_SESSION['log_secretary_generate_bill_appointment'] === true && $_SESSION['log_secretary_discharge_appointment'] === true){
    $opens[] = "generate bill & discharge for patient appointment";
}elseif($_SESSION['log_secretary_patient_appointment'] === true && $_SESSION['log_secretary_generate_bill_appointment'] === true){
    $opens[] = "generate bill for patient appointment";
}elseif($_SESSION['log_secretary_patient_appointment'] === true){
    $opens[] = "patient appointment page";
}

if($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_medical_information'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true && $_SESSION['log_secretary_discharge_walkin'] === true && $_SESSION['log_secretary_pdf_walkin'] === true){
    $opens[] = "add medical information & generate bill & discharge & print billings for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true && $_SESSION['log_secretary_discharge_walkin'] === true && $_SESSION['log_secretary_pdf_walkin'] === true){
    $opens[] = "generate bill & discharge & print billings for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true && $_SESSION['log_secretary_discharge_walkin'] === true){
    $opens[] = "generate bill & discharge for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true){
    $opens[] = "generate bill for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_medical_information'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true && $_SESSION['log_secretary_discharge_walkin'] === true){
    $opens[] = "add medical information & generate bill & discharge for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_medical_information'] === true && $_SESSION['log_secretary_generate_bill_walkin'] === true){
    $opens[] = "add medical information & generate bill for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_medical_information'] === true){
    $opens[] = "add medical information for walkin patient";
}elseif($_SESSION['log_secretary_walkin_patient'] === true){
    $opens[] = "walkin patient page";
}elseif($_SESSION['log_secretary_walkin_patient'] === true && $_SESSION['log_secretary_delete_walkin_patient'] === true){
    $opens[] = "delete walkin patient";
}

if($_SESSION['log_secretary_add_walkin'] === true && $_SESSION['log_secretary_add_walkin_patient'] === true){
    $opens[] = "added new walkin patient";
}elseif($_SESSION['log_secretary_add_walkin'] === true){
    $opens[] = "add walkin page";
}

if($_SESSION['log_secretary_patient_before'] === true && $_SESSION['log_secretary_patient_records'] === true && $_SESSION['log_secretary_add_new_records'] === true){
    $opens[] = "added new patient before patient";
}elseif($_SESSION['log_secretary_patient_before'] === true && $_SESSION['log_secretary_patient_records'] === true){
    $opens[] = "watched patient records";
}elseif($_SESSION['log_secretary_patient_before'] === true){
    $opens[] = "patient before page";
}

if($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_info'] === true && $_SESSION['log_secretary_update_pass'] === true && $_SESSION['log_secretary_update_img'] === true){
    $opens[] = "updated info & updated password & updated image";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_info'] === true && $_SESSION['log_secretary_update_pass'] === true){
    $opens[] = "updated info & updated password";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_pass'] === true && $_SESSION['log_secretary_update_img'] === true){
    $opens[] = "updated password & updated image";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_info'] === true && $_SESSION['log_secretary_update_img'] === true){
    $opens[] = "updated info & updated image";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_info'] === true){
    $opens[] = "updated info";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_pass'] === true){
    $opens[] = "updated password";
}elseif($_SESSION['log_secretary_information'] === true && $_SESSION['log_secretary_update_img'] === true){
    $opens[] = "updated image";
}elseif($_SESSION['log_secretary_information'] === true){
    $opens[] = "account page";
}

$actions = implode(", ", $opens);

$log_user_role = "secretary";

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
unset($_SESSION['log_secretary_dashboard']);
unset($_SESSION['log_secretary_appointment']);
unset($_SESSION['log_secretary_accept']);
unset($_SESSION['log_secretary_cancel']);
unset($_SESSION['log_secretary_patient_appointment']);
unset($_SESSION['log_secretary_generate_bill_appointment']);
unset($_SESSION['log_secretary_discharge_appointment']);
unset($_SESSION['log_secretary_pdf_appointment']);
unset($_SESSION['log_secretary_walkin_patient']);
unset($_SESSION['log_secretary_medical_information']);
unset($_SESSION['log_secretary_generate_bill_walkin']);
unset($_SESSION['log_secretary_discharge_walkin']);
unset($_SESSION['log_secretary_pdf_walkin']);
unset($_SESSION['log_secretary_add_walkin']);
unset($_SESSION['log_secretary_add_walkin_patient']);
unset($_SESSION['log_secretary_patient_before']);
unset($_SESSION['log_secretary_patient_records']);
unset($_SESSION['log_secretary_add_new_records']);
unset($_SESSION['log_secretary_information']);
unset($_SESSION['log_secretary_update_info']);
unset($_SESSION['log_secretary_update_pass']);
unset($_SESSION['log_secretary_update_img']);
unset($_SESSION['log_secretary_delete_walkin_patient']);


// nurse receptionist session
unset($_SESSION['nId']);
unset($_SESSION['nName']);
unset($_SESSION['nEmail']);
unset($_SESSION['nAddress']);
unset($_SESSION['nMobile']);
unset($_SESSION['nProfileImg']);

// Walk in Patient SESSION
unset($_SESSION['walkInId']);
unset($_SESSION['walkInName']);
unset($_SESSION['walkInEmail']);
unset($_SESSION['walkInAddress']);
unset($_SESSION['walkInMobile']);
unset($_SESSION['walkInDoctor']);
unset($_SESSION['walkInPrescription']);
unset($_SESSION['medicineFee']);
unset($_SESSION['walkInTotalPay']);
unset($_SESSION['dFee']);
unset($_SESSION['amountInput']);
unset($_SESSION['change']);
unset($_SESSION['walkInDisease']);
unset($_SESSION['amountInput']);
unset($_SESSION['change']);
unset($_SESSION['walkInLabTest']);
unset($_SESSION['walkInLabResult']);

// Patient information SESSION
unset($_SESSION['Pa_aId']);
unset($_SESSION['Pa_pId']);
unset($_SESSION['Pa_name']);
unset($_SESSION['Pa_email']);
unset($_SESSION['Pa_address']);
unset($_SESSION['Pa_mobile']);
unset($_SESSION['Pa_doctor']);
unset($_SESSION['Pa_prescription']);
unset($_SESSION['Pa_mFee']);
unset($_SESSION['Pa_totalPay']);
unset($_SESSION['Pa_dFee']);
unset($_SESSION['Pa_Disease']);

// Patient Walkin Doctor Prescription Real Time
unset($_SESSION['doctorUpdatePrescription']);

header("location:index.php");
exit(0);

}else{
    header("location:dashboard.php");
    exit;
}

