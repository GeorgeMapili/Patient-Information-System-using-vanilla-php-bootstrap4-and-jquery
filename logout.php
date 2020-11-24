<?php

session_start();

unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['address']);
unset($_SESSION['age']);
unset($_SESSION['gender']);
unset($_SESSION['mobile']);

header("location:index.php");
exit(0);
