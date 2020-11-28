<?php

session_start();

unset($_SESSION['nId']);
unset($_SESSION['nName']);
unset($_SESSION['nEmail']);
unset($_SESSION['nAddress']);
unset($_SESSION['nMobile']);
unset($_SESSION['nProfileImg']);

header("location:index.php");
exit(0);
