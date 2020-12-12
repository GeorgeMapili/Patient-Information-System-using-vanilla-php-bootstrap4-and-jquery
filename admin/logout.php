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

header("location:index.php");
exit(0);
