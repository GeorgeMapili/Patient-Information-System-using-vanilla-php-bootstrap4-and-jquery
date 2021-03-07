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
    <title>Secretary | Patient Record</title>
</head>

<body>

    <?php
    if (isset($_GET['pName'])) {
    ?>
        <header>
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
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
                            <a class="nav-link" href="appointmentPending.php">Pending Appointments&nbsp;<?= ($pendingCount > 0) ? '<span id="pending-appointment" class="badge bg-danger">' . $pendingCount . '</span>' : '<span id="pending-appointment" class="badge bg-danger"></span>'; ?></a>
                        </li>
                        <?php
                        $status = "done";
                        $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                        $stmt->execute();
                        $patientAppointment = $stmt->rowCount();
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="patient.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                        </li>
                        <?php
                        $sql = "SELECT * FROM walkinpatient";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();
                        $walkinpatient = $stmt->rowCount();
                        ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="patientWalkIn.php">Patient Walk in&nbsp;<?= ($walkinpatient > 0) ? '<span id="walkinpatient" class="badge bg-danger">' . $walkinpatient . '</span>' : '<span id="walkinpatient" class="badge bg-danger"></span>'; ?></a>
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

            <div class="container-fluid">
                <div class="mt-4 mb-4">
                    <h1 class="Display-4" id="primaryColor">Patient record</h1>
                </div>

                <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded mt-5" id="table-data">
                    <thead class="bg-info text-light">
                        <tr>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Patient Address</th>
                            <th scope="col">Patient Email</th>
                            <th scope="col">Patient Mobile</th>
                            <th scope="col">Patient Doctor</th>
                            <th scope="col">Doctor Prescription</th>
                            <th scope="col">Patient Disease</th>
                            <th scope="col">Patient Discharged Status</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Patient Amount Pay</th>
                            <th scope="col">Patient Change</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (isset($_GET['pName'])) {
                            $pName = $_GET['pName'];

                            $sql = "SELECT * FROM returnee_patient WHERE pName = :name";
                            $stmt = $con->prepare($sql);
                            $stmt->bindParam(":name", $pName, PDO::PARAM_STR);
                            $stmt->execute();

                            while ($returneePatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <tr>
                                    <td><?= $returneePatient['pName'] ?></td>
                                    <td><?= $returneePatient['pAddress'] ?></td>
                                    <td><?= $returneePatient['pEmail'] ?></td>
                                    <td><?= $returneePatient['pMobile'] ?></td>
                                    <td><?= $returneePatient['pDoctor'] ?></td>
                                    <td><?= $returneePatient['pPrescription'] ?></td>
                                    <td><?= $returneePatient['pDisease'] ?></td>
                                    <td><?= ucwords($returneePatient['pStatus']) ?></td>
                                    <td>₱<?= number_format($returneePatient['pTotalAmount'], 2) ?></td>
                                    <td>₱<?= number_format($returneePatient['pAmountPay'], 2) ?></td>
                                    <td>₱<?= number_format($returneePatient['pChange'], 2) ?></td>
                                    <td><?= date("M d, Y", strtotime($returneePatient['rpMadeOn'])) ?></td>
                                </tr>
                        <?php
                            }
                        } ?>

                    </tbody>
                </table>

                <div>
                    <a href="addNewRecord.php?addNewRec=true&pName=<?= $pName ?>" class="btn btn-info mb-3 margin-right-auto">Add New Record</a>
                </div>

            </div>

        <?php
    } else {
        header("location:dashboard.php");
        exit(0);
    }
        ?>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
        </footer>
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

        <script>
            $(document).ready(function() {
                $('#search').keyup(function() {
                    // Get input value on change
                    var patientBefore = $(this).val();
                    var resultDropdown = $(this).siblings("#data");

                    if (patientBefore.length) {
                        $.get("action1.php", {
                            patientBefore: patientBefore
                        }).done(function(data) {
                            // Display the returned data in browser
                            resultDropdown.html(data);
                        });
                    } else {
                        resultDropdown.empty();
                    }
                });
            });
        </script>
</body>

</html>