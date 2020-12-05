<?php
session_start();
require_once '../connect.php';

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
    <title>Doctor | Pending Appointment</title>
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
                        <a class="nav-link" href="appointmentPending.php">Appointment Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="incomingAppointment.php">Upcoming Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="cancelledAppointment.php">Cancelled Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doneAppointment.php">Done Appointment</a>
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
        // QUERY FOR ACCEPT STATUS
        if (isset($_POST['acceptStatus'])) {
            $aid = $_POST['aid'];
            $pid = $_POST['id'];
            // UPDATE appointment
            $status = "accepted";
            $sql = "UPDATE appointment SET aStatus = :status WHERE pId = :id AND pDoctor = :doctor AND aId= :aid";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":id", $pid, PDO::PARAM_INT);
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":aid", $aid, PDO::PARAM_INT);
            $stmt->execute();
            // UPDATE patient appointment
            $value = "1";
            $sql = "UPDATE patientappointment SET pDoctor = :doctor, pValid = :value WHERE pId = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":value", $value, PDO::PARAM_INT);
            $stmt->bindParam(":id", $pid, PDO::PARAM_INT);
            $stmt->execute();

            header("location:appointmentPending.php");
            exit(0);
        }

        if (isset($_POST['cancelStatus'])) {
            $pid = $_POST['id'];

            // UPDATE appointment;
            $status = "cancelled";
            $sql = "UPDATE appointment SET aStatus = :status WHERE pId = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":id", $pid, PDO::PARAM_INT);
            $stmt->execute();

            header("location:appointmentPending.php");
            exit(0);
        }
        ?>

        <div class="container-fluid">

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">My Pending Appointment</h1>
            </div>

            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Patient Address</th>
                        <th scope="col">Patient Mobile</th>
                        <th scope="col">Appointment Reason</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // QUERY FOR PENDING APPOINTMENT
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :name AND aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":name", $_SESSION['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($pendingAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <tr>
                            <td><?= $pendingAppointment['pName'] ?></td>
                            <td><?= $pendingAppointment['pAddress'] ?></td>
                            <td><?= $pendingAppointment['pMobile'] ?></td>
                            <td><?= $pendingAppointment['aReason'] ?></td>
                            <td><?= date("M d, Y", strtotime($pendingAppointment['aDate'])); ?>
                                at
                                <?= date("h:i A", strtotime($pendingAppointment['aTime'])); ?>
                            </td>
                            <td>
                                <div class="row">


                                    <div class="col">
                                        <form action="appointmentPending.php" method="post">
                                            <input type="hidden" name="aid" value="<?= $pendingAppointment['aId'] ?>">
                                            <input type="hidden" name="id" value="<?= $pendingAppointment['pId'] ?>">
                                            <input type="submit" value="Accept" class="btn btn-primary" name="acceptStatus">
                                        </form>
                                    </div>
                                    <div class="col">
                                        <form action="appointmentPending.php" method="post">
                                            <input type="hidden" name="aid" value="<?= $pendingAppointment['aId'] ?>">
                                            <input type="hidden" name="id" value="<?= $pendingAppointment['pId'] ?>">
                                            <input type="submit" value="Cancel" class="btn btn-danger" name="cancelStatus">
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>