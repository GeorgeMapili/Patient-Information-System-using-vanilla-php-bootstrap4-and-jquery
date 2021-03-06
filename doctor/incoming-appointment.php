<?php
ob_start();
session_start();
require_once '../connect.php';
require __DIR__ . '/../vendor/autoload.php';
require_once('../PHPMailer/PHPMailerAutoload.php');

if (!isset($_SESSION['ddId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_doctor_incoming_appointment'] = true;

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
    <link rel="icon" href="../img/sumc.png">
    <title>Doctor | Incoming Appointment</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
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
                    $discharge = 0;
                    $sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInDischarged = :discharge";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
                    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                    $stmt->execute();
                    $walkinCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="walkinpatient.php">Walk in Patient&nbsp;<?= ($walkinCount > 0) ? '<span id="walkin-count" class="badge bg-danger">' . $walkinCount . '</span>' : '<span id="walkin-count" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <?php
                    $status1 = "accepted";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                    $stmt->execute();
                    $upcomingAppointmentCount = $stmt->rowCount();
                    ?>
                    <div class="btn-group dropbottom">
                        <a class="nav-link active" href="incoming-appointment.php">Upcoming&nbsp;<?= ($upcomingAppointmentCount > 0) ? '<span id="upcoming-count" class="badge bg-danger">' . $upcomingAppointmentCount . '</span>' : '<span id="upcoming-count" class="badge bg-danger"></span>'; ?></a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="cancelled-appointment.php">Cancelled</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finished-appointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
                    <div class="dropdown nav-item">
                        <span class="nav-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Laboratory
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="lab-patient-appointment.php">Patient Appointment</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="lab-patient-walkin.php">Walk in Patient</a>
                        </div>
                    </div>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/doc_profile_img/<?= $_SESSION['ddProfileImg'] ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['ddName'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['ddEmail'] ?></a>
                            <a class="dropdown-item" href="profile.php">My account</a>
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
            if (isset($_POST['doneAppointment'])) {

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

                $status = "done";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();
                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:incoming-appointment.php?succDone=Successfully_done_appointment");
                $_SESSION['log_doctor_done_appointment'] = true;
                exit(0);
                ob_end_flush();
            }

            if (isset($_POST['cancelAppointment'])) {
                $aId = $_POST['aId'];
                $pId = $_POST['pId'];
                $status = "cancelled";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();

                $sql = "SELECT * FROM appointment WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();

                $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                $emailTo = $patient['pEmail'];
                $doctor_name = $patient['pDoctor'];
                $time = $patient['aDate']. " at ". $patient['aTime'];

                $subject = "SUMC Appointment";
                $body = "Greetings, We're sorry your appointment have been cancelled by $doctor_name on $time. Try again in another time";

                // PHP MAIL
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPDebug = 1; 
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = '465';
                $mail->isHTML();
                $mail->Username = 'bibblethump69@gmail.com';
                $mail->Password = "Thegamemaker1";
                $mail->SetFrom('biblethump69@gmail.com');
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->AddAddress($emailTo);
                $mail->Send();

                header("location:incoming-appointment.php?succCanc=Successfully_cancelled_appointment");
                $_SESSION['log_doctor_cancel_appointment'] = true;
                exit(0);
            }
            ?>

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">Incoming Appointment</h1>
            </div>


            <div class="text-center">
                <?= (isset($_GET['succCanc']) && $_GET['succCanc'] == "Successfully_cancelled_appointment") ? '<span class="text-success">Successfully Cancelled Appointment!</span>' : ''; ?>
            </div>
            <div class="text-center">
                <?= (isset($_GET['succDone']) && $_GET['succDone'] == "Successfully_done_appointment") ? '<span class="text-success">Successfully Done Appointment!</span>' : ''; ?>
            </div>

            <?php
            $status1 = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1 ORDER BY aDate,aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount() > 0){
            ?>

            <!-- Sort -->
            <div class="form-group">
                <h5 class="text-white">Sort By:</h5>
                <select name="sortBy" class="sortBy form-control w-25">
                    <option value="default">default</option>
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="this_week">This week</option>
                    <option value="next_week">Next week</option>
                    <option value="this_month">This month</option>
                </select>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover shadow p-3 mb-5 bg-white rounded" id="table-data">
                    <thead class="bg-info text-light">
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
                        while ($upcomingAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <td><?= $upcomingAppointment['pName'] ?></td>
                                <td><?= $upcomingAppointment['pAddress'] ?></td>
                                <td><?= $upcomingAppointment['pMobile'] ?></td>
                                <td><?= $upcomingAppointment['aReason'] ?></td>
                                <td><?= date("M d, Y", strtotime($upcomingAppointment['aDate'])); ?> at <?= $upcomingAppointment['aTime']; ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <?php
                                            if (date("M d, Y") === date("M d, Y", strtotime($upcomingAppointment['aDate']))) {
                                            ?>
                                                <form action="incoming-appointment.php" method="post">
                                                    <input type="hidden" name="aId" value="<?= $upcomingAppointment['aId'] ?>">
                                                    <input type="hidden" name="pId" value="<?= $upcomingAppointment['pId'] ?>">
                                                    <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                                </form>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="btn btn-success disabled">Done</p>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col">
                                            <form action="incoming-appointment.php" method="post">
                                                <input type="hidden" name="aId" value="<?= $upcomingAppointment['aId'] ?>">
                                                <input type="hidden" name="pId" value="<?= $upcomingAppointment['pId'] ?>">
                                                <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm('Are you sure to delete ?')">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php
            }else{
            ?>
            <p class="lead text-center text-white display-4">No appointment patient yet</p>
            <?php                
            }
            ?>

        </div>

        <div class="container">
        <hr class="featurette-divider">
        </div>



        <!-- FOOTER -->
        <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
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
                url: "walkinCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#walkin-count").html(result);
                        result = "0";
                        $("#walkin-count-dashboard").html(result);
                    } else {
                        $("#walkin-count").html(result);
                        $("#walkin-count-dashboard").html(result);
                    }
                }
            });

            $.ajax({
                url: "upcomingCount.php",
                success: function(result) {

                    if (result == "0") {
                        result = "";
                        $("#upcoming-count").html(result);
                        result = "0";
                        $("#upcoming-count-dashboard").html(result);
                    } else {
                        $("#upcoming-count").html(result);
                        $("#upcoming-count-dashboard").html(result);
                    }

                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.sortBy').change(function() {
                // Get the sorted Value
                var sortBy = $('.sortBy').val();
                // console.log(sortBy);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        sortBy: sortBy
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