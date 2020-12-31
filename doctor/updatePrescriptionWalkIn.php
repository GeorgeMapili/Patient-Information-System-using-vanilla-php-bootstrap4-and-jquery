<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['dId'])) {
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
    <title>Doctor | Update Prescription</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="walkInPatient.php">Walk in Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="incomingAppointment.php">Upcoming Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="cancelledAppointment.php">Cancelled Appointment</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " href="doneAppointment.php">Finished Appointment</a>
                    </li>
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

        <?php

        if (isset($_POST['UpdateWalkInPrescription'])) {
            $id = $_POST['id'];
            $updatePrescription = trim(htmlspecialchars($_POST['prescription']));

            if ($_SESSION['updateWalkInPrescription'] == $updatePrescription) {
                header("location:walkInPatient.php?errUp=Nothing_to_update");
                exit(0);
            }

            $sql = "UPDATE walkinpatient SET walkInPrescription = :prescription WHERE walkInId = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":prescription", $updatePrescription, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            header("location:walkInPatient.php?succUp=Successfully_updated_prescription");
            exit(0);
        }
        ?>

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Update Prescription</h1>
            </div>

            <form action="updatePrescriptionWalkIn.php" method="post">

                <?php
                if (isset($_POST['updatePrescriptionWalkIn'])) {
                    $id = $_POST['id'];

                    $sql = "SELECT * FROM walkinpatient WHERE walkInId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $updateWalkIn = $stmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['updateWalkInPrescription'] = $updateWalkIn['walkInPrescription'];
                } else {
                    header("location:dashboard.php");
                    exit(0);
                }
                ?>

                <div class="row">
                    <div class="col">
                        <input type="hidden" name="id" value="<?= $updateWalkIn['walkInId'] ?>">
                        <label for="exampleInputEmail1">Patient Name</label>
                        <input type="text" class="form-control" value="<?= $updateWalkIn['walkInName'] ?>" readonly>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1">Patient Disease</label>
                        <input type="text" class="form-control" value="<?= $updateWalkIn['walkInDisease'] ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="exampleInputEmail1">Address</label>
                        <input type="text" class="form-control" value="<?= $updateWalkIn['walkInAddress'] ?>" readonly>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1">Mobile Number</label>
                        <input type="tel" class="form-control" value="<?= $updateWalkIn['walkInMobile'] ?>" readonly>
                    </div>
                </div>

                <label for="">Prescription or Medicines</label>
                <textarea name="prescription" class="form-control resize-0" cols="30" rows="10"><?= $updateWalkIn['walkInPrescription'] ?></textarea>
                <div class="text-center mt-3">
                    <input type="submit" class="btn" id="docBtnApt" value="Update" name="UpdateWalkInPrescription">
                </div>
            </form>
        </div>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; <?= date("Y") ?> Company, Inc. &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>