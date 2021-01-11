<?php
session_start();
require_once '../connect.php';

unset($_SESSION['adId']);
unset($_SESSION['adName']);
unset($_SESSION['adEmail']);

// SESSION FROM UPDATE PATIENT APPOINTMENT
unset($_SESSION['ad_updateId']);
unset($_SESSION['ad_updateName']);
unset($_SESSION['ad_updateEmail']);
unset($_SESSION['ad_updateAddress']);
unset($_SESSION['ad_updateMobile']);
unset($_SESSION['ad_updateAge']);
unset($_SESSION['ad_updateGender']);
unset($_SESSION['ad_updateProfile']);

// SESSION FROM UPDATE DOCTOR
unset($_SESSION['dId']);
unset($_SESSION['dName']);
unset($_SESSION['dEmail']);
unset($_SESSION['dAddress']);
unset($_SESSION['dMobile']);
unset($_SESSION['dSpecialization']);
unset($_SESSION['dFee']);
unset($_SESSION['dSpecializationInfo']);
unset($_SESSION['dProfileImg']);

// SESSION FROM UPDATE WALKIN PATIENT
unset($_SESSION['walkInPatientId']);
unset($_SESSION['walkInPatientName']);
unset($_SESSION['walkInPatientEmail']);
unset($_SESSION['walkInPatientAddress']);
unset($_SESSION['walkInPatientMobile']);
unset($_SESSION['walkInPatientDisease']);
unset($_SESSION['walkInPatientAge']);
unset($_SESSION['walkInPatientGender']);
unset($_SESSION['walkInPatientDoctor']);

// SESSION FROM UPDATE NURSE
unset($_SESSION['nurseId']);
unset($_SESSION['nurseName']);
unset($_SESSION['nurseEmail']);
unset($_SESSION['nurseAddress']);
unset($_SESSION['nurseMobile']);
unset($_SESSION['nurseProfile']);

// SESSSION FROM MESSAGES
unset($_SESSION['msgPatientId']);
unset($_SESSION['msgId']);

header("location:index.php");
exit(0);
