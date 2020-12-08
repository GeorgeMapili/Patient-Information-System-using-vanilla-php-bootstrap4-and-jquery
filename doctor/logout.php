<?php
session_start();
require_once '../connect.php';

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
