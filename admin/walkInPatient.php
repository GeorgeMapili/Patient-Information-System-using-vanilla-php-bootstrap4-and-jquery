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
    <title>Admin | Walk in Patient</title>
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
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">All Walk in Patient</h1>
                </div>

                <div class="text-center">
                    <?= (isset($_GET['succAddWalkIn']) && $_GET['succAddWalkIn'] == "Successfully_added_walk_in_patient") ? '<span class="text-success">Successfully added walk in patient!</span>' : '' ?>
                    <?= (isset($_GET['errUpdate']) && $_GET['errUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : '' ?>
                    <?= (isset($_GET['succUpdateWalkIn']) && $_GET['succUpdateWalkIn'] == "Successfully_added_walk_in_patient") ? '<span class="text-success">Successfully updated walk in patient!</span>' : '' ?>
                    <?= (isset($_GET['succDeleteWalkInPatient']) && $_GET['succDeleteWalkInPatient'] == "Successfully_deleted_a_walk_in_patient") ? '<span class="text-danger">Successfully deleted a walk in patient!</span>' : '' ?>
                </div>

                <div class="d-flex justify-content-between">
                    <form class="form-inline">
                        <input class="form-control mb-3" autocomplete="off" type="search" id="search" placeholder="Search Patient Name" aria-label="Search">
                    </form>
                    <div class="mb-3">
                        <a href="addWalkInPatient.php" class="btn btn-success mt-3 ">Add Walk in Patient</a>
                    </div>
                </div>

                <?php

                if (isset($_POST['deleteWalkInBtn'])) {
                    $walkInId = $_POST['walkInId'];

                    // Get the walk in patien room number
                    $sql = "SELECT * FROM walkinpatient WHERE walkInId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $walkInId, PDO::PARAM_INT);
                    $stmt->execute();

                    $walkIn = $stmt->fetch(PDO::FETCH_ASSOC);

                    $roomNumber = $walkIn['walkInRoomNumber'];

                    // Remove the room status from occupied to available
                    $status = "available";
                    $sql = "UPDATE rooms SET room_status = :status WHERE room_number = :roomNumber";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->bindParam(":roomNumber", $roomNumber, PDO::PARAM_INT);
                    $stmt->execute();

                    $sql = "DELETE FROM walkinpatient WHERE walkInId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $walkInId, PDO::PARAM_INT);
                    $stmt->execute();

                    header("location:walkInPatient.php?succDeleteWalkInPatient=Successfully_deleted_a_walk_in_patient");
                    ob_end_flush();
                    exit(0);
                }

                ?>

                <table class="table table-hover " id="table-data">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Patient ID</th>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Patient Email</th>
                            <th scope="col">Patient Address</th>
                            <th scope="col">Patient Mobile</th>
                            <th scope="col">Patient Disease</th>
                            <th scope="col">Patient Age</th>
                            <th scope="col">Patient Gender</th>
                            <th scope="col">Patient Doctor</th>
                            <th scope="col">Patient Room</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM walkinpatient";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $walkInPatient['walkInId'] ?></th>
                                <td><?= $walkInPatient['walkInName'] ?></td>
                                <td><?= $walkInPatient['walkInEmail'] ?></td>
                                <td><?= $walkInPatient['walkInAddress'] ?></td>
                                <td><?= $walkInPatient['walkInMobile'] ?></td>
                                <td><?= $walkInPatient['walkInDisease'] ?></td>
                                <td><?= $walkInPatient['walkInAge'] ?></td>
                                <td><?= ucwords($walkInPatient['walkInGender']) ?></td>
                                <td><?= $walkInPatient['walkInDoctor'] ?></td>
                                <td><?= $walkInPatient['walkInRoomNumber'] ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="updateWalkInPatient.php" method="post">
                                                <input type="hidden" name="walkInId" value="<?= $walkInPatient['walkInId'] ?>">
                                                <input type="submit" value="Update" class="btn btn-secondary" name="updateWalkInBtn">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="walkInPatient.php" method="post">
                                                <input type="hidden" name="walkInId" value="<?= $walkInPatient['walkInId'] ?>">
                                                <input type="submit" value="Delete" class="btn btn-danger" name="deleteWalkInBtn" onclick="return confirm('Are you sure to delete?');">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

        </div>


        </main>
    </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
                        searchWalkInPatient: search
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