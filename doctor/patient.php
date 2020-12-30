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
    <title>Doctor | Patient</title>
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
                        <a class="nav-link" href="walkInPatient.php">Walk in Patient</a>
                    </li>
                    <li class="nav-item active">
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
                <h1 class="Display-4 my-4" id="primaryColor">My Patient from Appointment</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['succAddPrescription']) && $_GET['succAddPrescription'] == "Successfully_added_prescription") ? '<span class="text-success">Successfully added presciption!</span>' : ''; ?>
                <?= (isset($_GET['succUpdatePrescription']) && $_GET['succUpdatePrescription'] == "Successfully_updated_prescription") ? '<span class="text-success">Successfully updated presciption!</span>' : ''; ?>
                <?= (isset($_GET['errUpdate']) && $_GET['errUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['succUpdate']) && $_GET['succUpdate'] == "disease_updated_successfully") ? '<span class="text-success">Updated Disease Successfully!</span>' : ''; ?>
                <?= (isset($_GET['errUpdateDisease']) && $_GET['errUpdateDisease'] == "nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
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
                        <th scope="col">Updated Disease</th>
                        <th scope="col">History</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    $doctor = $_SESSION['dName'];
                    $status = "accepted";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($patientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <tr>
                            <td><?= $patientAppointment['pName']; ?></td>
                            <td><?= $patientAppointment['pAddress']; ?></td>
                            <td><?= $patientAppointment['pMobile'] ?></td>
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



            <hr class="featurette-divider">

            <!-- FOOTER -->
            <footer class="text-center">
                <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
            </footer>
        </div>
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