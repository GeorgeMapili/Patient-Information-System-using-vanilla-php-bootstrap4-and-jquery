<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="icon" href="../img/sumc.png">
    <title>Admin | Add Patient</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">SUMC Doctors Clinic</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="dashboard.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="patientUser.php">
                                <span data-feather="file"></span>
                                View All Patient Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="patient.php">
                                <span data-feather="file"></span>
                                View All Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doctor.php">
                                <span data-feather="shopping-cart"></span>
                                View All Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="walkInPatient.php" id="primaryColor">
                                <span data-feather="shopping-cart"></span>
                                View All Walk in patient
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php">
                                <span data-feather="users"></span>
                                View All Secretary
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doneAppointment.php">
                                <span data-feather="users"></span>
                                View Finished Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancelledAppointment.php">
                                <span data-feather="users"></span>
                                View Cancelled Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="walkInDischarged.php">
                                <span data-feather="users"></span>
                                View Walkin Patient Discharged
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="messages.php">
                                <span data-feather="users"></span>
                                View All Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="diseaseTreatment.php">
                                <span data-feather="users"></span>
                                View All Diseases & Treatment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="audit-log.php">
                                <span data-feather="users"></span>
                                View Audit Logs
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <?php

            if (isset($_POST['walkInBtn'])) {

                $name = trim(htmlspecialchars($_POST['name']));
                $address = trim(htmlspecialchars($_POST['address']));
                $mobile = trim(htmlspecialchars($_POST['mobile']));
                $disease = trim(htmlspecialchars($_POST['disease']));
                $age = trim(htmlspecialchars($_POST['age']));
                $gender = trim(htmlspecialchars($_POST['gender']));
                $doctor = trim(htmlspecialchars($_POST['doctor']));

                // check if name is valid
                if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                    header("location:addWalkInPatient.php?errName=name_is_not_valid&email=$email&address=$address&mobile=$mobile&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    ob_end_flush();
                    exit(0);
                }

                // check if the name is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->execute();

                $nameCount = $stmt->rowCount();

                if ($nameCount > 0) {
                    header("location:addWalkInPatient.php?errName1=Name_is_already_existed&email=$email&address=$address&mobile=$mobile&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                // check if the mobile number is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInMobile = :mobile";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":mobile", $mobile, PDO::PARAM_INT);
                $stmt->execute();

                $mobileCount = $stmt->rowCount();

                if ($mobileCount > 0) {
                    header("location:addWalkInPatient.php?errMobile=Mobile_number_is_already_existed&name=$name&email=$email&address=$address&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                // get the doctor fee
                $sql = "SELECT * FROM doctor WHERE dName = :doctor";
                $stmt  = $con->prepare($sql);
                $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                $stmt->execute();
                $docResult = $stmt->fetch(PDO::FETCH_ASSOC);

                $doctorFee = $docResult['dFee'];

                // Set walkinTotalPay
                $walkInTotalPay = $doctorFee;

                $sql = "INSERT INTO walkinpatient(walkInName,walkInAddress,walkInMobile,walkInDisease,walkInAge,walkInGender,walkInDoctor,doctorFee,walkInTotalPay)VALUES(:name,:address,:mobile,:disease,:age,:gender,:doctor,:doctorFee,:walkInTotalPay)";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
                $stmt->bindParam(":disease", $disease, PDO::PARAM_STR);
                $stmt->bindParam(":age", $age, PDO::PARAM_STR);
                $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
                $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                $stmt->bindParam(":doctorFee", $doctorFee, PDO::PARAM_STR);
                $stmt->bindParam(":walkInTotalPay", $walkInTotalPay, PDO::PARAM_STR);
                $stmt->execute();

                header("location:walkInPatient.php?succAddWalkIn=Successfully_added_walk_in_patient");
                exit(0);
            }

            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="container">

                    <div class="mt-4 mb-4">
                        <h1 class="Display-4" id="primaryColor">Add Walk in Patient</h1>
                    </div>

                    <form action="addWalkInPatient.php" method="post">
                        <div class="row my-4">
                            <div class="col">
                                <label>Patient Name</label>
                                <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed")) ? '<input type="text" name="name" class="form-control is-invalid" required>' : ((isset($_GET['name'])) ? '<input type="text" name="name" value="' . $_GET['name'] . '" class="form-control" required>' : '<input type="text" name="name" class="form-control" required>') ?>
                                <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<small class="text-danger">Name is not valid!</small>' : '' ?>
                                <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed") ? '<small class="text-danger">Name is already existed!</small>' : '' ?>
                            </div>
                            <div class="col">
                                <label>Patient Mobile Number</label>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" class="form-control is-invalid" name="mobile" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : ((isset($_GET['mobile'])) ? '<input type="tel" class="form-control" name="mobile" value= "' . str_replace(' ', '+', $_GET['mobile']) . '" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" class="form-control" name="mobile" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'); ?>
                                <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already taken!</small>' : '' ?>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <label>Patient Address</label>
                                <?= (isset($_GET['address']) ? '<input type="text" name="address" class="form-control" value="' . $_GET['address'] . '" required>' : '<input type="text" name="address" class="form-control" required>') ?>
                            </div>
                            <div class="col">
                                <label>Patient Age</label>
                                <?= (isset($_GET['age']) ? '<input type="date" class="form-control" name="age" value=' . $_GET['age'] . ' required>' : '<input type="date" class="form-control" name="age" required>') ?>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col">
                                <label>Patient Disease</label>
                                <?= (isset($_GET['disease']) ? '<input type="text" name="disease" value="' . $_GET['disease'] . '" class="form-control" required>' : '<input type="text" name="disease" class="form-control" required>') ?>
                            </div>
                            <div class="col">
                                <label>Gender</label>
                                <div class="d-flex justify-content-around">
                                    <?php
                                    if (isset($_GET['gender']) && $_GET['gender'] === "male") {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="male" value="male" checked required>
                                            <label for="male">Male</label>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="male" value="male" required>
                                            <label for="male">Male</label>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if (isset($_GET['gender']) && $_GET['gender'] === "female") {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="female" value="female" checked required>
                                            <label for="female">Female</label>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="female" value="female" required>
                                            <label for="female">Female</label>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label>Doctor</label>
                            <select name="doctor" class="form-control" required>
                                <option value="">Select a doctor</option>
                                <?php
                                $sql = "SELECT * FROM doctor";
                                $stmt = $con->prepare($sql);
                                $stmt->execute();

                                while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <?php
                                    if ($doctor['dName'] == $_GET['doctor']) {
                                    ?>
                                        <option value="<?= $doctor['dName']; ?>" selected><?= $doctor['dName']; ?> -> <?= $doctor['dSpecialization']; ?> </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $doctor['dName']; ?>"><?= $doctor['dName']; ?> -> <?= $doctor['dSpecialization']; ?> </option>
                                    <?php
                                    }
                                    ?>

                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="text-center mt-3">
                            <input type="submit" class="btn btn-primary" value="Add Walk in Patient" name="walkInBtn">
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>