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
                            <a class="nav-link" href="walkInPatient.php">
                                <span data-feather="shopping-cart"></span>
                                View All Walk in patient
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="room.php" id="primaryColor">
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
                    </ul>

                </div>
            </nav>

            <?php

            if (isset($_POST['deleteRoomBtn'])) {

                $id = $_POST['roomId'];
                $sql = "DELETE FROM rooms WHERE room_id = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                header("location:room.php?succDeleteRoom=Successfully_deleted_room");
                ob_end_flush();
                exit(0);
            }

            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">All Rooms</h1>
                </div>

                <div class="text-center">
                    <?= (isset($_GET['succAddedRoom']) && $_GET['succAddedRoom'] == "Successfully_added_room") ? '<span class="text-success">Successfully added room!</span>' : '' ?>
                    <?= (isset($_GET['errRoomUpdate']) && $_GET['errRoomUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : '' ?>
                    <?= (isset($_GET['succRoomUpdate']) && $_GET['succRoomUpdate'] == "Successfully_updated_room") ? '<span class="text-success">Successfully updated room!</span>' : '' ?>
                    <?= (isset($_GET['succDeleteRoom']) && $_GET['succDeleteRoom'] == "Successfully_deleted_room") ? '<span class="text-success">Successfully deleted room!</span>' : '' ?>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <form class="form-inline">
                        <input class="form-control" type="search" id="search" placeholder="Search Room" aria-label="Search">
                    </form>
                    <div>
                        <a href="addRoom.php" class="btn btn-success mt-3 ">Add Room</a>
                    </div>
                </div>
                <table class="table table-hover " id="table-data">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Room ID</th>
                            <th scope="col">Room Number</th>
                            <th scope="col">Room Fee</th>
                            <th scope="col">Room Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM rooms";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($rooms = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $rooms['room_id'] ?></th>
                                <td><?= $rooms['room_number'] ?></td>
                                <td>₱<?= number_format($rooms['room_fee'], 2) ?></td>
                                <td>
                                    <?php
                                    if ($rooms['room_status'] == "available") {
                                    ?>
                                        <p class="btn btn-success disabled"><?= $rooms['room_status'] ?></p>
                                    <?php
                                    } else {
                                    ?>
                                        <p class="btn btn-danger disabled"><?= $rooms['room_status'] ?></p>
                                    <?php
                                    }
                                    ?>

                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="updateRoom.php" method="post">
                                                <input type="hidden" name="roomId" value="<?= $rooms['room_id'] ?>">
                                                <input type="submit" value="Update" class="btn btn-secondary" name="updateRoomBtn">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="room.php" method="post">
                                                <input type="hidden" name="roomId" value="<?= $rooms['room_id'] ?>">
                                                <input type="submit" value="Delete" class="btn btn-danger" name="deleteRoomBtn" onclick="return confirm('Are you sure to delete?');">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var search = $(this).val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        searchRoom: search
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