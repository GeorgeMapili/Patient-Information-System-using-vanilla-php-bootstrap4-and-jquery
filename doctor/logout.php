<?php
session_start();
require_once '../connect.php';

unset($_SESSION['dId']);
unset($_SESSION['dName']);
unset($_SESSION['dEmail']);
unset($_SESSION['dAddress']);
unset($_SESSION['dMobile']);
unset($_SESSION['dSpecialization']);
unset($_SESSION['dSpecializationInfo']);
unset($_SESSION['dProfileImg']);
unset($_SESSION['dFee']);

header("location:index.php");
exit(0);
