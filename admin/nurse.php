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
                            <a class="nav-link" href="room.php">
                                <span data-feather="users"></span>
                                View All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php" id="primaryColor">
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

            if (isset($_POST['deleteNurse'])) {
                $nId = $_POST['id'];

                // Select the nurse image
                $sql = "SELECT * FROM nurse_receptionist WHERE nId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                $stmt->execute();

                // Nurse image
                $nurse = $stmt->fetch(PDO::FETCH_ASSOC);

                //Nurse current image
                $nurseImage = $nurse['nProfileImg'];

                // Delete the image
                $path = __DIR__ . "/../upload/nurse_profile_img/" . $nurseImage;
                unlink($path);

                $sql = "DELETE FROM nurse_receptionist WHERE nId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $nId, PDO::PARAM_INT);
                $stmt->execute();

                header("location:nurse.php?succDeleteNurse=Successfully_deleted_nurse");
                ob_end_flush();
                exit(0);
            }
            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">All Nurse Receptionist</h1>
                </div>

                <div class="text-center">
                    <?= (isset($_GET['succAddedNurse']) && $_GET['succAddedNurse'] == "Successfully_added_nurse") ? '<span class="text-success">Successfully added nurse!</span>' : '' ?>
                    <?= (isset($_GET['errUpdateNurseChanged']) && $_GET['errUpdateNurseChanged'] == "Nothing_to_changed") ? '<span class="text-danger">Nothing to changed!</span>' : '' ?>
                    <?= (isset($_GET['succDeleteNurse']) && $_GET['succDeleteNurse'] == "Successfully_deleted_nurse") ? '<span class="text-success">Successfully deleted nurse!</span>' : '' ?>
                    <?= (isset($_GET['succUpdateNurse']) && $_GET['succUpdateNurse'] == "Successfully_updated_nurse") ? '<span class="text-success">Successfully updated nurse!</span>' : '' ?>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <form class="form-inline">
                        <input class="form-control" type="search" id="search" placeholder="Search Nurse Name" aria-label="Search">
                    </form>
                    <div>
                        <a href="addNurse.php" class="btn btn-success mt-3 ">Add Nurse</a>
                    </div>
                </div>

                <table class="table table-hover" id="table-data">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nurse ID</th>
                            <th scope="col">Nurse Profile Img</th>
                            <th scope="col">Nurse Name</th>
                            <th scope="col">Nurse Email</th>
                            <th scope="col">Nurse Address</th>
                            <th scope="col">Nurse Mobile</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM nurse_receptionist";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($nurse = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $nurse['nId'] ?></th>
                                <td><img src="../upload/nurse_profile_img/<?= $nurse['nProfileImg'] ?>" width="50" height="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                                <td><?= $nurse['nName'] ?></td>
                                <td><?= $nurse['nEmail'] ?></td>
                                <td><?= $nurse['nAddress'] ?></td>
                                <td><?= $nurse['nMobile'] ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="updateNurse.php" method="post">
                                                <input type="hidden" name="id" value="<?= $nurse['nId'] ?>">
                                                <input type="submit" value="Update" class="btn btn-secondary" name="updateNurseBtn">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="nurse.php" method="post">
                                                <input type="hidden" name="id" value="<?= $nurse['nId'] ?>">
                                                <input type="submit" value="Delete" class="btn btn-danger" name="deleteNurse" onclick="return confirm('Are you sure to delete?');">
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
                        searchNurse: search
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