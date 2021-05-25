<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['dId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_doctor_patient_appointment'] = true;

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
    <title>Doctor | Patient Appointment</title>
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
                    $discharge = 0;
                    $sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInDischarged = :discharge";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                    $stmt->execute();
                    $walkinCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="walkInPatient.php">Walk in Patient&nbsp;<?= ($walkinCount > 0) ? '<span id="walkin-count" class="badge bg-danger">' . $walkinCount . '</span>' : '<span id="walkin-count" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <?php
                    $status1 = "accepted";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                    $stmt->execute();
                    $upcomingAppointmentCount = $stmt->rowCount();
                    ?>
                    <div class="btn-group dropbottom">
                        <a class="nav-link" href="incomingAppointment.php">Upcoming&nbsp;<?= ($upcomingAppointmentCount > 0) ? '<span id="upcoming-count" class="badge bg-danger">' . $upcomingAppointmentCount . '</span>' : '<span id="upcoming-count" class="badge bg-danger"></span>'; ?></a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="cancelledAppointment.php">Cancelled</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="doneAppointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
                    <div class="dropdown nav-item">
                        <span class="nav-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Laboratory
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="labPatientAppointment.php">Patient Appointment</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="labPatientWalkin.php">Walk in Patient</a>
                        </div>
                    </div>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/doc_profile_img/<?= $_SESSION['dProfileImg'] ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['dName'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['dEmail'] ?></a>
                            <a class="dropdown-item" href="doctorProfile.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <div class="container-fluid">

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">Patient from Appointment</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['succAddPrescription']) && $_GET['succAddPrescription'] == "Successfully_added_prescription") ? '<span class="text-success">Successfully added presciption!</span>' : ''; ?>
                <?= (isset($_GET['succUpdatePrescription']) && $_GET['succUpdatePrescription'] == "Successfully_updated_prescription") ? '<span class="text-success">Successfully updated presciption!</span>' : ''; ?>
                <?= (isset($_GET['errUpdate']) && $_GET['errUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['succUpdate']) && $_GET['succUpdate'] == "disease_updated_successfully") ? '<span class="text-success">Updated Disease Successfully!</span>' : ''; ?>
                <?= (isset($_GET['errUpdateDisease']) && $_GET['errUpdateDisease'] == "nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
            </div>

            <?php
            $doctor = $_SESSION['dName'];
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount() > 0){
            ?>

            <div class="row">
                <div class="col">
                    <form class="form-inline">
                        <input class="form-control mb-3" id="search" autocomplete="off" type="search" placeholder="Search Patient" aria-label="Search">
                        <input type="hidden" name="doctorName" class="doctorName" value="<?= $_SESSION['dName']; ?>">
                    </form>
                </div>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover shadow p-3 mb-5 bg-white rounded" id="table-data">
                    <thead class="bg-info text-light">
                        <tr>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Patient Address</th>
                            <th scope="col">Date</th>
                            <th scope="col">Patient Disease</th>
                            <th scope="col">Prescription</th>
                            <th scope="col">Updated Disease</th>
                            <th scope="col">History</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php

                        while ($patientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <td><?= $patientAppointment['pName']; ?></td>
                                <td><?= $patientAppointment['pAddress']; ?></td>
                                <td><?= date("M d, Y", strtotime($patientAppointment['aDate'])); ?> at <?= $patientAppointment['aTime']; ?></td>
                                <td><?= $patientAppointment['aReason']; ?></td>
                                <td>
                                    <?php
                                    if (empty($patientAppointment['pPrescription'])) {
                                    ?>
                                        <form action="addPrescription.php" method="post">
                                            <input type="hidden" name="aid" value="<?= $patientAppointment['aId']; ?>">
                                            <input type="hidden" name="pid" value="<?= $patientAppointment['pId']; ?>">
                                            <input type="submit" value="Add Prescription" class="btn btn-info" name="addPrescriptionBtn">
                                        </form>
                                    <?php
                                    } else {
                                    ?>
                                        <form action="editPrescription.php" method="post">
                                            <input type="hidden" name="aid" value="<?= $patientAppointment['aId']; ?>">
                                            <input type="hidden" name="pid" value="<?= $patientAppointment['pId']; ?>">
                                            <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionBtn">
                                        </form>
                                    <?php
                                    }
                                    ?>

                                </td>
                                <td>
                                    <form action="updateDisease.php" method="post">
                                        <input type="hidden" name="aId" value="<?= $patientAppointment['aId'] ?>">
                                        <input type="hidden" name="pId" value="<?= $patientAppointment['pId'] ?>">
                                        <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                                    </form>
                                </td>
                                <td>
                                    <form action="patientMedicalHistory.php" method="post">
                                        <input type="hidden" name="aId" value="<?= $patientAppointment['aId']; ?>">
                                        <input type="hidden" name="pId" value="<?= $patientAppointment['pId']; ?>">
                                        <input type="submit" value="Watch appointment history" class="btn btn-primary" name="watchHistory">
                                    </form>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

            <?php
            }else{
            ?>
            <p class="lead text-center text-white display-4">No appointment patient yet</p>
            <?php    
            }
            ?>


            <div class="container">
                <hr class="featurette-divider">
            </div>

            <!-- FOOTER -->
            <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="aboutUs.php" id="primaryColor">About Us</a></p>
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
                url: "walkinCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkin-count").html(result);
                        result = "0";
                        $("#walkin-count-dashboard").html(result);
                    } else {
                        $("#walkin-count").html(result);
                        $("#walkin-count-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "upcomingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#upcoming-count").html(result);
                        result = "0";
                        $("#upcoming-count-dashboard").html(result);
                    } else {
                        $("#upcoming-count").html(result);
                        $("#upcoming-count-dashboard").html(result);
                    }

                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var search = $(this).val();
                var doctorName = $('.doctorName').val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        patientQuery: search,
                        doctorName: doctorName
                    },
                    success: function(response) {
                        $('#table-data').html(response);
                    }
                });
            });
        });
    </script>
</body>

</html>