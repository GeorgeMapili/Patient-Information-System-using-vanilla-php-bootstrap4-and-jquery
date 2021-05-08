<?php

session_start();

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
