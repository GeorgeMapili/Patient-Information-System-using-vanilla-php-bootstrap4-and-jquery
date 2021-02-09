<?php
session_start();
require_once '../connect.php';
require_once '../vendor/autoload.php';

if (!isset($_SESSION['nId'])) {
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
    <title>Nurse | Pending Appointment</title>
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
                    <?php
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $pendingCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="appointmentPending.php">Pending Appointments&nbsp;<?= ($pendingCount > 0) ? '<span id="pending-appointment" class="badge bg-danger">' . $pendingCount . '</span>' : '<span id="pending-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $status = "done";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $patientAppointment = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM walkinpatient";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $walkinpatient = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patientWalkIn.php">Patient Walk in&nbsp;<?= ($walkinpatient > 0) ? '<span id="walkinpatient" class="badge bg-danger">' . $walkinpatient . '</span>' : '<span id="walkinpatient" class="badge bg-danger"></span>'; ?></a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/nurse_profile_img/<?= $_SESSION['nProfileImg']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Nurse&nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="nurseProfile.php">My account</a>
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

            <?php
            if (isset($_POST['acceptStatus'])) {

                $options = array(
                    'cluster' => 'ap1',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    '33e38cfddf441ae84e2d',
                    '9d6c92710887d31d41b4',
                    '1149333',
                    $options
                );

                $aId = $_POST['aId'];
                $pId = $_POST['pId'];
                $status = "accepted";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();

                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:appointmentPending.php");
                exit(0);
            }

            if (isset($_POST['cancelStatus'])) {
                $aId = $_POST['aId'];
                $pId = $_POST['pId'];
                $status = "cancelled";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();

                header("location:appointmentPending.php");
                exit(0);
            }
            ?>

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">Pending Appointments</h1>
            </div>

            <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Patient Address</th>
                        <th scope="col">Patient Mobile</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Appointment Reason</th>
                        <th scope="col">Date of Appointment</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($appointmentPending = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <tr>
                            <td><?= $appointmentPending['pName'] ?></td>
                            <td><?= $appointmentPending['pAddress'] ?></td>
                            <td><?= $appointmentPending['pMobile'] ?></td>
                            <td><?= $appointmentPending['pDoctor'] ?></td>
                            <td><?= $appointmentPending['aReason'] ?></td>
                            <td>
                                <?= date("M d, Y", strtotime($appointmentPending['aDate'])); ?>
                                at
                                <?= $appointmentPending['aTime']; ?>
                            </td>
                            <td>
                                <div class="row">


                                    <div class="col">
                                        <form action="appointmentPending.php" method="post">
                                            <input type="hidden" name="aId" value="<?= $appointmentPending['aId'] ?>">
                                            <input type="hidden" name="pId" value="<?= $appointmentPending['pId'] ?>">
                                            <input type="submit" value="Accept" class="btn btn-info" name="acceptStatus">
                                        </form>
                                    </div>
                                    <div class="col">
                                        <form action="appointmentPending.php" method="post">
                                            <input type="hidden" name="aId" value="<?= $appointmentPending['aId'] ?>">
                                            <input type="hidden" name="pId" value="<?= $appointmentPending['pId'] ?>">
                                            <input type="submit" value="Cancel" class="btn btn-danger" name="cancelStatus" onclick="return confirm('Are you sure?')">
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <hr class="featurette-divider">

            <!-- FOOTER -->
            <footer class="text-center">
                <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
            </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('33e38cfddf441ae84e2d', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            // alert(JSON.stringify(data));
            $.ajax({
                url: "pendingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#pending-appointment").html(result);
                        result = "0";
                        $("#pending-appointment-dashboard").html(result);
                    } else {
                        $("#pending-appointment").html(result);
                        $("#pending-appointment-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "patientAppointment.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#patient-appointment").html(result);
                        result = "0";
                        $("#patient-appointment-dashboard").html(result);
                    } else {
                        $("#patient-appointment").html(result);
                        $("#patient-appointment-dashboard").html(result);
                    }

                }
            });

            $.ajax({
                url: "walkinpatient.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkinpatient").html(result);
                        result = "0";
                        $("#walkinpatient-dashboard").html(result);
                    } else {
                        $("#walkinpatient").html(result);
                        $("#walkinpatient-dashboard").html(result);
                    }

                }
            });

        });
    </script>
</body>

</html>