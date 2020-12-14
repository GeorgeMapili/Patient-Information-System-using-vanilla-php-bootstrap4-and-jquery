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
    <title>Admin | Dashboard</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">Company name</a>
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
                            <a class="nav-link " href="patient.php" id="primaryColor">
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
                            <a class="nav-link" href="room.php">
                                <span data-feather="users"></span>
                                View All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php">
                                <span data-feather="users"></span>
                                View All Nurse Receptionist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doneAppointment.php">
                                <span data-feather="users"></span>
                                View Done Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancelledAppointment.php">
                                <span data-feather="users"></span>
                                View Cancelled Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dischargedPatient.php">
                                <span data-feather="users"></span>
                                View Discharged Patients
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <?php

            if (isset($_POST['deleteAppointment'])) {

                $aId = $_POST['aId'];
                $pId = $_POST['pId'];

                $sql = "DELETE FROM appointment WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();

                header("location:patient.php?succDeleteAppointment=Successfully_deleted_a_appointment");
                ob_end_flush();
                exit(0);
            }
            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">All Patients</h1>
                </div>

                <div class="text-center">
                    <?= (isset($_GET['succDeleteAppointment']) && $_GET['succDeleteAppointment'] == "Successfully_deleted_a_appointment") ? '<span class="text-success">Successfully deleted a appointment!</span>' : '' ?>
                </div>

                <div class="mt-4 mb-4">
                    <h1 class="Display-4 my-4" id="primaryColor">Appointments</h1>
                </div>
                <div class="d-flex justify-content-between">
                    <form class="form-inline">
                        <input class="form-control mb-3" id="search" autocomplete="off" type="search" placeholder="Search Patient Name" aria-label="Search">
                    </form>
                </div>
                <table class="table table-hover" id="table-data">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Appointment ID</th>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Patient Address</th>
                            <th scope="col">Patient Mobile</th>
                            <th scope="col">Patient Disease</th>
                            <th scope="col">Doctor Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $status1 = "accepted";
                        $status2 = "done";
                        $sql = "SELECT * FROM appointment WHERE aStatus IN(:status1,:status2)";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                        $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
                        $stmt->execute();

                        while ($patientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $patientAppointment['aId'] ?></th>
                                <td><?= $patientAppointment['pName'] ?></td>
                                <td><?= $patientAppointment['pAddress'] ?></td>
                                <td><?= $patientAppointment['pMobile'] ?></td>
                                <td><?= $patientAppointment['aReason'] ?></td>
                                <td><?= $patientAppointment['pDoctor'] ?></td>
                                <td>
                                    <div class="col">
                                        <form action="patient.php" method="post">
                                            <input type="hidden" name="aId" value="<?= $patientAppointment['aId'] ?>">
                                            <input type="hidden" name="pId" value="<?= $patientAppointment['pId'] ?>">
                                            <input type="submit" value="Delete" class="btn btn-danger" name="deleteAppointment" onclick="return confirm('Are you sure to delete ?')">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <hr>



            </main>
        </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var search = $(this).val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        searchPatientAppointment: search
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