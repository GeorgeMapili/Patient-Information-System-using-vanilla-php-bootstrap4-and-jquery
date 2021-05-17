<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="icon" href="../img/sumc.png">
    <title>Secretary | Patient Discharged</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <?php
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $pendingCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pendings.php">Pending Appointments&nbsp;<?= ($pendingCount > 0) ? '<span id="pending-appointment" class="badge bg-danger">' . $pendingCount . '</span>' : '<span id="pending-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $status = "done";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $patientAppointment = $stmt->rowCount();
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient-appointments.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM walkinpatient";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $walkinpatient = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patient-walkin.php">Patient Walk in&nbsp;<?= ($walkinpatient > 0) ? '<span id="walkinpatient" class="badge bg-danger">' . $walkinpatient . '</span>' : '<span id="walkinpatient" class="badge bg-danger"></span>'; ?></a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/nurse_profile_img/<?= $_SESSION['nProfileImg']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="account.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <form action="logout.php" method="post">
                                <input type="hidden" name="logout" value="true">
                                <input type="submit" value="Logout" class="dropdown-item">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">
        <div class="container">

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Patient Appointment Bill</h3>

            <?php
            if (isset($_POST['dischargeAppointment'])) {
                // Data in field
                $aid = $_POST['aId'];
                $pid = $_POST['pId'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $mobilenumber = $_POST['mobilenumber'];
                $patientStatus = $_POST['patientStatus'];
                $doctorName = $_POST['doctorName'];
                $doctorFee = $_POST['doctorFee'];
                $prescribeMed = $_POST['prescribeMed'];
                $medicineFee = $_POST['medicineFee'];
                $amountInput = $_POST['amountInput'];
                $totalAmount = $_POST['totalAmount'];

                // Change of the bill
                $changeBill = 0;

                // SESSION ------------------------------------------------------
                // $_SESSION['amountInput'] = $amountInput;

                if ($amountInput >= $totalAmount) {
                    $changeBill = $amountInput -  $totalAmount;
                    // var_dump($changeBill);
                    // exit;
                    $date = date("M d, Y");

                    $status = "discharged";
                    $discharge = 1;
                    // UPDATE THE STATUS OF THE PATIENT FROM DONE TO DISCHARGE
                    $sql = "UPDATE appointment SET aStatus = :status, pDischarge = :discharge, patientStatus =:statusPatient, pAmountPay = :amountPay, pChange = :change, dischargedOn = :dischargedOn  WHERE aId = :aid AND pId = :pid";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                    $stmt->bindParam(":statusPatient", $patientStatus, PDO::PARAM_STR);
                    $stmt->bindParam(":amountPay", $amountInput, PDO::PARAM_INT);
                    $stmt->bindParam(":change", $changeBill, PDO::PARAM_INT);
                    $stmt->bindParam(":dischargedOn", $date, PDO::PARAM_STR);
                    $stmt->bindParam(":aid", $aid, PDO::PARAM_INT);
                    $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
                    $stmt->execute();

                    // header("location:dischargePa.php?dischargedAppointment=1");
                    // exit(0);
                } else {
                    header("location:bill-appointments.php?errAmount=too_low_amount");
                    exit(0);
                }
            }

            if (isset($_POST['dischargeAppointment'])) {
            ?>

                <div class="container">
                    <div class="row justify-content-center bg-light shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="col-lg-6 px-4 pb-4" id="order">
                            <form action="pdf-appointments.php" method="post" id="placeOrder" target="_blank">
                                <!-- <input type="hidden" name="orderedfood" value="123">
                                <input type="hidden" name="orderedtotalamount" value="123">
                                <input type="hidden" name="userId" value="123"> -->

                                <?php
                                $discharge = 1;
                                $sql = "SELECT * FROM appointment WHERE aId = :aid AND pId= :pid AND pDischarge = :discharge";
                                $stmt = $con->prepare($sql);
                                $stmt->bindParam(":aid", $_SESSION['Pa_aId'], PDO::PARAM_INT);
                                $stmt->bindParam(":pid", $_SESSION['Pa_pId'], PDO::PARAM_INT);
                                $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                                $stmt->execute();

                                $dischargePatientAppointment = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class="text-center">
                                    <h1 class="display-4 mt-2 text-danger">Patient Discharged Successfully</h1>
                                    <h6 class="lead text-center">Patient Name: <?= $dischargePatientAppointment['pName'] ?></h6>
                                    <h6 class="lead text-center">Email: <?= $dischargePatientAppointment['pEmail'] ?></h6>
                                    <h6 class="lead text-center">Address: <?= $dischargePatientAppointment['pAddress'] ?></h6>
                                    <h6 class="lead text-center">Mobile Number: <?= $dischargePatientAppointment['pMobile'] ?></h6>
                                    <h6 class="lead text-center">Patient Status: <?= ucwords($dischargePatientAppointment['patientStatus']) ?></h6>
                                    <h6 class="lead text-center">Doctor Name: <?= $dischargePatientAppointment['pDoctor'] ?></h6>
                                    <h6 class="lead text-center">Doctor Prescribe Medicine: <?= $dischargePatientAppointment['pPrescription'] ?></h6>
                                    <h6 class="lead text-center">Amount Input: <?= $dischargePatientAppointment['pAmountPay'] ?></h6>
                                    <h6 class="lead text-center">Total Amount: <?= $dischargePatientAppointment['pTotalPay'] ?></h6>
                                    <h6 class="lead text-center">Change: <?= $dischargePatientAppointment['pChange'] ?></h6>
                                </div>

                                <div class="col">
                                    <div class="form-group mt-3">
                                        <!-- <input type="submit" name="placeorder" class="btn btn-primary" value="Show Bill"> -->
                                        <input type="hidden" name="pId" value="<?= $pid ?>">
                                        <input type="hidden" name="aId" value="<?= $$aid ?>">
                                        <input type="submit" value="Print Billings" class="btn btn-primary">
                                        <!-- <a href='pdfDischargeAppointment.php?printBillings=true' class="btn btn-primary" target="_blank">Print Billings</a> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php

            } else {
                header("location:dashboard.php");
                exit(0);
            }
            ?>

            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

            <!-- FOOTER -->
            <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
        </footer>
        </div>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('33e38cfddf441ae84e2d', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            // alert(JSON.stringify(data));
            $.ajax({
                url: "pendingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#pending-appointment").html(result);
                        result = "0";
                        $("#pending-appointment-dashboard").html(result);
                    } else {
                        $("#pending-appointment").html(result);
                        $("#pending-appointment-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "patientAppointment.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#patient-appointment").html(result);
                        result = "0";
                        $("#patient-appointment-dashboard").html(result);
                    } else {
                        $("#patient-appointment").html(result);
                        $("#patient-appointment-dashboard").html(result);
                    }

                }
            });

            $.ajax({
                url: "walkinpatient.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkinpatient").html(result);
                        result = "0";
                        $("#walkinpatient-dashboard").html(result);
                    } else {
                        $("#walkinpatient").html(result);
                        $("#walkinpatient-dashboard").html(result);
                    }

                }
            });

        });
    </script>

</body>

</html>