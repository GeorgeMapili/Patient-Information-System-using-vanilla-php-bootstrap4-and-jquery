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
    <title>Nurse | Patient Discharged</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointmentPending.php">Pending Appointments</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient.php">Patient from appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patientWalkIn.php">Patient Walk in</a>
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
                            Nurse&nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="nurseProfile.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
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

            if (isset($_GET['dischargedAppointment']) && $_GET['dischargedAppointment'] == 1) {
            ?>

                <div class="container">
                    <div class="row justify-content-center bg-light">
                        <div class="col-lg-6 px-4 pb-4" id="order">
                            <form action="checkout.php" method="post" id="placeOrder">
                                <input type="hidden" name="orderedfood" value="123">
                                <input type="hidden" name="orderedtotalamount" value="123">
                                <input type="hidden" name="userId" value="123">

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
                                        <a href='pdfDischargeAppointment.php?printBillings=true' class="btn btn-primary" target="_blank">Print Billings</a>
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
            <footer class="text-center">
                <p>&copy; <?= date("Y") ?> Company, Inc. &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
            </footer>
        </div>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>