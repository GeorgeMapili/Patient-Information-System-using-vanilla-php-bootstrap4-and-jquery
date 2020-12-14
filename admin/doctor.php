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
                            <a class="nav-link" href="doctor.php" id="primaryColor">
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

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">All Doctors</h1>
                </div>

                <div class="text-center">
                    <?= (isset($_GET['errUpdate']) && $_GET['errUpdate'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                    <?= (isset($_GET['succUpdateImg']) && $_GET['succUpdateImg'] == "Successfully_update_the_img") ? '<span class="text-success">Successfully updated image!</span>' : ''; ?>
                    <?= (isset($_GET['updateSuccInfo']) && $_GET['updateSuccInfo'] == "Successfully_updated_information") ? '<span class="text-success">Successfully updated information!</span>' : ''; ?>
                    <?= (isset($_GET['succUpdatePass']) && $_GET['succUpdatePass'] == "Successfully_updated_password") ? '<span class="text-success">Successfully updated password!</span>' : ''; ?>
                    <?= (isset($_GET['succDelete']) && $_GET['succDelete'] == "Successfully_deleted_a_doctor") ? '<span class="text-success">Successfully deleted a doctor account!</span>' : ''; ?>
                </div>

                <div class="d-flex justify-content-between">
                    <form class="form-inline">
                        <input class="form-control mb-3" type="search" id="search" placeholder="Search Doctor" aria-label="Search">
                    </form>
                    <div class="mb-3">
                        <a href="addDoctor.php" class="btn btn-success mt-3 ">Add Doctor</a>
                    </div>
                </div>

                <?php

                if (isset($_POST['deleteDoctorBtn'])) {
                    $dId = $_POST['dId'];

                    // select the doctor image
                    $sql = "SELECT * FROM doctor WHERE dId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                    $stmt->execute();

                    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

                    $doctorImage = $doctor['dProfileImg'];

                    $sql = "DELETE FROM doctor WHERE dId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $dId, PDO::PARAM_INT);
                    $stmt->execute();

                    // Delete all the appointments WHERE status = pending/accepted/done if doctor acc deleted
                    $status1 = "pending";
                    $status2 = "accepted";
                    $status3 = "done";
                    $sql = "DELETE FROM appointment WHERE pDoctor = :doctor AND aStatus IN(:status1,:status2,:status3)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $doctor['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                    $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
                    $stmt->bindParam(":status3", $status3, PDO::PARAM_STR);
                    $stmt->execute();


                    $path = __DIR__ . "/../upload/doc_profile_img/" . $doctorImage;
                    unlink($path);
                    // var_dump(unlink($path));
                    // die();

                    header("location:doctor.php?succDelete=Successfully_deleted_a_doctor");
                    ob_end_flush();
                    exit(0);
                }

                ?>

                <table class="table table-hover " id="table-data">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Doctor ID</th>
                            <th scope="col">Doctor Profile Img</th>
                            <th scope="col">Doctor Name</th>
                            <th scope="col">Doctor Email</th>
                            <th scope="col">Doctor Address</th>
                            <th scope="col">Doctor Mobile</th>
                            <th scope="col">Doctor Specialization</th>
                            <th scope="col">Doctor Fee</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "SELECT * FROM doctor";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $doctor['dId'] ?></th>
                                <td><img src="../upload/doc_profile_img/<?= $doctor['dProfileImg'] ?>" width="50" height="50" style="border:1px solid #333; border-radius: 100%;" alt=""></td>
                                <td><?= $doctor['dName'] ?></td>
                                <td><?= $doctor['dEmail'] ?></td>
                                <td><?= $doctor['dAddress'] ?></td>
                                <td><?= $doctor['dMobile'] ?></td>
                                <td><?= $doctor['dSpecialization'] ?></td>
                                <td>₱<?= number_format($doctor['dFee'], 2) ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="updateDoctor.php" method="post">
                                                <input type="hidden" name="dId" value="<?= $doctor['dId'] ?>">
                                                <input type="submit" value="Update" class="btn btn-secondary" name="updateDoctorBtn">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="doctor.php" method="post">
                                                <input type="hidden" name="dId" value="<?= $doctor['dId'] ?>">
                                                <input type="submit" value="Delete" class="btn btn-danger" name="deleteDoctorBtn" onclick="return confirm('Are you sure to delete?');">
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
                        searchDoctor: search
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