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
                        <li class="nav-item">
                            <a class="nav-link" href="messages.php">
                                <span data-feather="users"></span>
                                View All Messages
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <?php

            if (isset($_POST['updateRoom'])) {

                $id = $_POST['id'];
                $roomNumber = $_POST['roomNumber'];
                $roomFee = $_POST['roomFee'];

                // check if nothing changed
                if ($_SESSION['roomNumber'] == $roomNumber && $_SESSION['roomFee'] == $roomFee) {
                    header("location:room.php?errRoomUpdate=Nothing_to_update");
                    ob_end_flush();
                    exit(0);
                }

                // check if the room number is already taken
                $sql = "SELECT * FROM rooms WHERE room_number = :number AND room_id <> :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":number", $roomNumber, PDO::PARAM_INT);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $roomNumbers = $stmt->rowCount();

                if ($roomNumbers > 0) {
                    header("location:updateRoom.php?errRoomNumber=Room_number_is_already_existed");
                    exit(0);
                }



                $sql = "UPDATE rooms SET room_number = :number, room_fee = :fee WHERE room_id = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":number", $roomNumber, PDO::PARAM_INT);
                $stmt->bindParam(":fee", $roomFee, PDO::PARAM_INT);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                header("location:room.php?succRoomUpdate=Successfully_updated_room");
                exit(0);
            }

            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Update Rooms</h1>
                </div>
                <div class="container">

                    <?php
                    if (isset($_POST['updateRoomBtn'])) {

                        $roomId = $_POST['roomId'];

                        $sql = "SELECT * FROM rooms WHERE room_id = :roomid";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":roomid", $roomId, PDO::PARAM_INT);
                        $stmt->execute();

                        $room = $stmt->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['roomId'] = $room['room_id'];
                        $_SESSION['roomNumber'] = $room['room_number'];
                        $_SESSION['roomFee'] = $room['room_fee'];
                    }
                    ?>

                    <form action="updateRoom.php" method="post">
                        <input type="hidden" name="id" value="<?= $_SESSION['roomId'] ?>">
                        <label>Room Number</label>
                        <?= (isset($_GET['errRoomNumber']) && $_GET['errRoomNumber'] == "Room_number_is_already_existed") ? '<input type="number" name="roomNumber" class="form-control is-invalid" required>' : '<input type="number" name="roomNumber" class="form-control" value=' . $_SESSION['roomNumber'] . '>' ?>
                        <?= (isset($_GET['errRoomNumber']) && $_GET['errRoomNumber'] == "Room_number_is_already_existed") ? '<span class="text-danger">Room number is already existed!</span>' : '' ?>

                        <br>
                        <label>Room Fee</label>
                        <input type="number" name="roomFee" class="form-control" value="<?= $_SESSION['roomFee'] ?>" required>

                        <div class="text-center mt-3">
                            <input type="submit" class="btn btn-primary" value="Update Room" name="updateRoom">
                        </div>
                    </form>
                </div>
                <div>
                </div>

        </div>


        </main>
    </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>