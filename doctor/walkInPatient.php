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
    <title>Doctor | Walk in Patient</title>
</head>

<body>

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
                    <li class="nav-item">
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

        <div class="container-fluid">

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">My Walkin Patient</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['succAddPrescription']) && $_GET['succAddPrescription'] == "Successfully_added_prescription") ? '<span class="text-success">Successfully added prescription!</span>' : ''; ?>
                <?= (isset($_GET['succUp']) && $_GET['succUp'] == "Successfully_updated_prescription") ? '<span class="text-success">Successfully updated prescription!</span>' : ''; ?>
                <?= (isset($_GET['errUp']) && $_GET['errUp'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['succDiseaseUp']) && $_GET['succDiseaseUp'] == "Successfully_updated_disease") ? '<span class="text-success">Successfully updated disease!</span>' : ''; ?>
                <?= (isset($_GET['errDiseaseUp']) && $_GET['errDiseaseUp'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['errWatch']) && $_GET['errWatch'] == "No_medical_information_avalable") ? '<span class="text-danger">No medical information available!</span>' : ''; ?>
                <?= (isset($_GET['errWalkInMedHistory']) && $_GET['errWalkInMedHistory'] == "No_medical_history") ? '<span class="text-danger">No medical history!</span>' : ''; ?>
            </div>

            <div class="row">
                <div class="col">
                    <form class="form-inline">
                        <input class="form-control mb-3" id="search" autocomplete="off" type="search" placeholder="Search Patient" aria-label="Search">
                        <input type="hidden" name="doctorName" class="doctorName" value="<?= $_SESSION['dName']; ?>">
                    </form>
                </div>
            </div>
            <table class="table table-hover" id="table-data">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Patient Address</th>
                        <th scope="col">Patient Mobile</th>
                        <th scope="col">Patient Disease</th>
                        <th scope="col">Prescription</th>
                        <th scope="col">Update</th>
                        <th scope="col">Information</th>
                        <th scope="col">History</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $discharge = 0;
                    $sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInDischarged = :discharge";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                    $stmt->execute();

                    while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <tr>
                            <td><?= $walkInPatient['walkInName'] ?></td>
                            <td><?= $walkInPatient['walkInAddress'] ?></td>
                            <td><?= $walkInPatient['walkInMobile'] ?></td>
                            <td><?= $walkInPatient['walkInDisease'] ?></td>
                            <td>
                                <?php
                                if (empty($walkInPatient['walkInPrescription'])) {
                                ?>
                                    <form action="addPrescriptionWalkIn.php" method="post">
                                        <input type="hidden" name="id" value="<?= $walkInPatient['walkInId'] ?>">
                                        <input type="submit" value="Add Prescription" class="btn btn-primary" name="addPrescriptionWalkIn">
                                    </form>
                                <?php
                                } else {
                                ?>
                                    <form action="updatePrescriptionWalkIn.php" method="post">
                                        <input type="hidden" name="id" value="<?= $walkInPatient['walkInId'] ?>">
                                        <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionWalkIn">
                                    </form>
                                <?php
                                }
                                ?>

                            </td>
                            <td>
                                <form action="updateDiseaseWalkIn.php" method="post">
                                    <input type="hidden" name="id" value="<?= $walkInPatient['walkInId'] ?>">
                                    <input type="submit" value="Update Disease" class="btn btn-primary" name="updateDiseaseWalkIn">
                                </form>
                            </td>
                            <td>
                                <form action="watchMedicalInfo.php" method="post">
                                    <input type="hidden" name="id" value="<?= $walkInPatient['walkInId'] ?>">
                                    <input type="submit" value="Watch Medical Information" class="btn btn-primary" name="watchMedInfo">
                                </form>
                            </td>
                            <td>
                                <form action="watchMedicalHistory.php" method="post">
                                    <input type="hidden" name="name" value="<?= $walkInPatient['walkInName'] ?>">
                                    <input type="submit" value="Watch Medical History" class="btn btn-info" name="watchMedHistory">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>

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

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var search = $(this).val();
                var doctorName = $('.doctorName').val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        walkInQuery: search,
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