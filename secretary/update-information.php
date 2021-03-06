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
    <title>Secretary | Medical Information</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="patient-appointments.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM walkinpatient";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $walkinpatient = $stmt->rowCount();
                    ?>
                    <li class="nav-item active">
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

    <?php
    if (isset($_GET['medicalInformation'])) {
    ?>

        <main role="main">
            <div class="container">

                <h3 class="display-4 mt-5 my-4" id="primaryColor">Update Medical Information</h3>


                <?php

                if (isset($_GET['id'])) {
                    $id = trim(htmlspecialchars($_GET['id']));

                    $sql = "SELECT * FROM medicalinformation WHERE pId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $updateMedicalInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                    $arrayInfo = explode(",", $updateMedicalInfo['pMedicalInfo']);
                    $arrsLen = count($arrayInfo);
                }

                ?>


                <form action="action.php" method="post">
                    <?php $id = $_GET['id']; ?>
                    <input type="hidden" name="id" value="<?= $id; ?>">

                    <div class="row">
                        <div class="col m-1">
                            <label class="text-white">Height</label>
                            <input type="number" name="height" value="<?= $updateMedicalInfo['pHeight'] ?>" min="0" class="form-control" required>
                        </div>
                        <div class="col m-1">
                            <label class="text-white">Weight</label>
                            <input type="number" name="weight" min="0" value="<?= $updateMedicalInfo['pWeight'] ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col m-1">
                            <label class="text-white">Blood Type</label>
                            <select name="bloodType" class="form-control" required>
                                <option value="">select a blood type</option>
                                <?php
                                $sql = "SELECT * FROM bloodtype";
                                $stmt = $con->prepare($sql);
                                $stmt->execute();

                                while ($bloodType = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <option value="<?= $bloodType['bloodType'] ?>" <?= ($bloodType['bloodType'] == $updateMedicalInfo['pBloodType']) ? 'selected' : ''; ?>><?= $bloodType['bloodType'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col m-1">
                            <label class="text-white">Allergy</label>
                            <input type="text" name="allergy" class="form-control" value="<?= $updateMedicalInfo['pAllergy'] ?>" required>
                        </div>
                    </div>

                    <h6 class=" mt-5 my-4" id="primaryColor">Have you ever the following ?</h6>
                    <?php

                    $sql = "SELECT * FROM ffmedicaldisease";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $inc = 0;
                    while ($md = $stmt->fetch(PDO::FETCH_ASSOC)) :

                    ?>
                        <label for="" class="text-white"><?= ucwords($md['md_name']); ?></label>
                        <input type="checkbox" name="followingMed[]" value="<?= $md['md_name'] ?>" <?php
                                                                                                    for ($i = 0; $i < $arrsLen; $i++) {
                                                                                                        if ($md['md_name'] == $arrayInfo[$i]) {
                                                                                                            echo 'checked';
                                                                                                        }
                                                                                                    }
                                                                                                    ?>><br>
                    <?php endwhile; ?>
                    <div class="text-center">
                        <input type="submit" value="Update Medical Information" name="updateMedInfo" class="btn btn-primary mt-3">
                    </div>
                </form>


                <hr class="featurette-divider">

                <!-- /END THE FEATURETTES -->

                <!-- FOOTER -->
                <footer class="container">
                    <p class="float-right"><a href="#" class="text-dark">Back to top</a></p>
                    <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
                </footer>
            </div>
        </main>

    <?php
    } else {
        header("location:dashboard.php");
        exit(0);
    }
    ?>


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