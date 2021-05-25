<?php
session_start();
require_once("vendor/autoload.php");
use core\db\Database;

$database = new Database();

$con = $database->connect();

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_update_history'] = true;

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Appointment History</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
            <a class="navbar-brand " i id="primaryColor" href="home.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="library.php">Health Library</a>
                    </li>
                    <div class="btn-group dropbottom">
                        <a href="current-appointment.php" class="nav-link">
                            Appointment
                        </a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="accepted-appointment.php">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finished-appointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <img src="upload/user_profile_img/<?= $_SESSION['profile']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="account.php">My account</a>
                            <a class="dropdown-item" href="appointment-history.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="delete.php" onclick="return confirm('Are you sure?')">Delete Account</a>
                            <div class="dropdown-divider"></div>
                            <form action="logout.php" method="post">
                                <input type="hidden" name="logout" value="true">
                                <input type="submit" value="Logout" class="dropdown-item">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">My appointments History</h1>
            </div>

            <?php
            $status1 = "discharged";
            $status2 = "cancelled";
            $sql = "SELECT * FROM appointment WHERE (aStatus = :status1 OR aStatus = :status2) AND pId = :id ORDER BY aDate DESC";

            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
            $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() > 0){
            ?>

            <div class="table-responsive-xl">
                <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded">
                    <thead class="bg-info text-light">
                        <tr>
                            <th scope="col">Patient Doctor</th>
                            <th scope="col">Appointment Fee</th>
                            <th scope="col">Appointment Reason</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowCount = $stmt->rowCount();

                        while ($myAppointmentHistory = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <td><?= $myAppointmentHistory['pDoctor']; ?></td>
                                <td>₱ <?= number_format($myAppointmentHistory['dFee'], 2); ?></td>
                                <td><?= $myAppointmentHistory['aReason']; ?></td>
                                <td><?= date("M d, Y", strtotime($myAppointmentHistory['aDate'])); ?> at <?= $myAppointmentHistory['aTime'] ?></td>
                                <td>
                                    <?php
                                    if ($myAppointmentHistory['aStatus'] === "discharged") { ?>
                                        <p class="btn btn-primary disabled">Discharged</p>
                                    <?php
                                    } else if ($myAppointmentHistory['aStatus'] === "cancelled") {
                                    ?>
                                        <p class="btn btn-danger disabled">Cancelled</p>
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php
            }else{
            ?>
            <p class="lead text-center text-white display-4">No appointments history yet</p>
            <?php                
            }
            ?>

        </div>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container">
            <p class="float-right"><a href="#" class="text-dark">Back to top</a></p>
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>