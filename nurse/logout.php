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
unset($_SESSION['walkInRoomNumber']);
unset($_SESSION['walkInDoctor']);
unset($_SESSION['walkInPrescription']);
unset($_SESSION['medicineFee']);
unset($_SESSION['walkInTotalPay']);
unset($_SESSION['room_fee']);
unset($_SESSION['dFee']);
unset($_SESSION['amountInput']);
unset($_SESSION['change']);
unset($_SESSION['walkInDisease']);

header("location:index.php");
exit(0);
