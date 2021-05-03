<?php
ob_start();
session_start();
require_once 'connect.php';
require __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['id'])) {
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
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Set Appointment</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="main.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contactus.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="healthLibrary.php">Health Library</a>
                    </li>
                    <div class="btn-group dropbottom">
                        <a href="myappointment.php" class="nav-link">
                            Appointment
                        </a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="acceptedAppointment.php">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finishedAppointment.php">Finished</a>
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
                            <a class="dropdown-item" href="myaccount.php">My account</a>
                            <a class="dropdown-item" href="myAppointmentHistory.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="deleteAcc.php" onclick="return confirm('Are you sure?')">Delete Account</a>
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
        use core\patient\Appointment;
        require_once("vendor/autoload.php");

        $appointment = new Appointment();

        if (isset($_POST['submitAppointment'])) {

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

            $appointment->pId = $_POST['id'];
            $appointment->pName = $_POST['name'];
            $appointment->pEmail = $_POST['email'];
            $appointment->pAddress = $_POST['address'];
            $appointment->pMobile = $_POST['mobileNumber'];
            $appointment->pDoctor = $_POST['selectDoctor'];
            $appointment->aDate = $_POST['dateOfAppointment'];
            $appointment->aTime = $_POST['selectTime'];
            $appointment->aReason = trim(htmlspecialchars($_POST['reasonAppointment']));

            $appointment->datePassByCheck();

            // $appointment->noWeekEndServiceCheck();

            $appointment->alreadyHaveAnAppointDiffDoctor();

            $appointment->duplicateAppointmentCheck();

            $appointment->sameDateAppointCheck();

            // Get the Doctor Fee
            $sql = "SELECT * FROM doctor WHERE dName = :name";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":name", $appointment->pDoctor, PDO::PARAM_STR);
            $stmt->execute();
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            $dFee = $doctor['dFee'];

            $sql = "INSERT INTO appointment (pId,pName,pEmail,pAddress,pMobile,pDoctor,dFee,aReason,aDate,aTime,pTotalPay)VALUES(:id,:name,:email,:address,:mobile,:doctor,:fee,:reason,:date,:time,:totalPay)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":id", $appointment->pId, PDO::PARAM_INT);
            $stmt->bindParam(":name", $appointment->pName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $appointment->pEmail, PDO::PARAM_STR);
            $stmt->bindParam(":address", $appointment->pAddress, PDO::PARAM_STR);
            $stmt->bindParam(":mobile", $appointment->pMobile, PDO::PARAM_STR);
            $stmt->bindParam(":doctor", $appointment->pDoctor, PDO::PARAM_STR);
            $stmt->bindParam(":fee", $dFee, PDO::PARAM_STR);
            $stmt->bindParam(":reason", $appointment->aReason, PDO::PARAM_STR);
            $stmt->bindParam(":date", $appointment->aDate, PDO::PARAM_STR);
            $stmt->bindParam(":time", $appointment->aTime, PDO::PARAM_STR);
            $stmt->bindParam(":totalPay", $dFee, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:appointment.php?AppointmentSuccess=Successully_request_an_appointment");
                exit(0);
                ob_end_flush();
            }
        }

        ?>

        <div class="container">

            <?php if (isset($_GET['AppointmentSuccess'])) : ?>
                <div class="alert alert-success alert-dismissible my-4">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Successfully request an appointment!</strong>
                </div>
            <?php endif; ?>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Set an Appointment</h1>
            </div>


            <form action="appointment.php" method="post" class="shadow p-3 mb-5 bg-white rounded">
                <div class="row">
                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">
                    <div class="col">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $_SESSION['name']; ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?= $_SESSION['email']; ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $_SESSION['address']; ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobileNumber" class="form-control" value="<?= $_SESSION['mobile']; ?>" readonly>
                    </div>
                </div>
                <?php
                if (isset($_GET['docId'])) {
                    $dId = $_GET['docId'];
                ?>

                    <label for="">Choose a physician</label>
                    <select class="form-control" name="selectDoctor" required>
                        <?php
                        $sql = "SELECT * FROM doctor";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <?php
                            if ($doctor['dId'] == $_GET['docId']) {
                            ?>
                                <option value="<?= $doctor['dName']; ?>" selected><?= $doctor['dName']; ?> -> <?= $doctor['dSpecialization']; ?> </option>
                            <?php
                            } else {
                            ?>
                                <option value="<?= $doctor['dName']; ?>"><?= $doctor['dName']; ?> -> <?= $doctor['dSpecialization']; ?> </option>
                            <?php
                            }
                            ?>

                        <?php endwhile; ?>
                    </select>

                <?php
                } else {
                ?>
                    <label for="">Choose a physician</label>
                    <select class="form-control" name="selectDoctor" required>
                        <option value="">Select</option>
                        <?php
                        $sql = "SELECT * FROM doctor";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <option value="<?= $doctor['dName']; ?>"><?= $doctor['dName']; ?> -> <?= $doctor['dSpecialization']; ?> </option>
                        <?php endwhile; ?>
                    </select>
                <?php
                }
                ?>
                <div>
                    <small>Read specialization info below</small>
                </div>
                <label for="">Date & Time</label><br>
                <small><span class="text-danger h6 font-weight-bold">*</span>No sunday service</small>
                <?= ((isset($_GET['errDate']) && $_GET['errDate'] == "date_already_pass_by") || (isset($_GET['errDate1']) && $_GET['errDate1'] == "no_weekends")) ? '<input type="date" class="form-control is-invalid" name="dateOfAppointment" required>' : '<input type="date" class="form-control" name="dateOfAppointment" required>'; ?>
                <?= (isset($_GET['errDate']) && $_GET['errDate'] == "date_already_pass_by") ? '<span class="text-danger">Date has already passed!</span>' : ''; ?>
                <?= (isset($_GET['errDate1']) && $_GET['errDate1'] == "no_weekends") ? '<span class="text-danger">No weekends service!</span>' : ''; ?>

                <?= ((isset($_GET['errTime']) && $_GET['errTime'] == "all_ready_taken_time") || (isset($_GET['errDup']) && $_GET['errDup'] == "you_have_already_an_appointment_in_that_time_with_different_doctor") || (isset($_GET['errDup1']) && $_GET['errDup1'] == "you_already_made_an_appointment")) ? '<select class="form-control is-invalid" name="selectTime" required>' : '<select class="form-control" name="selectTime" required>'; ?>
                <option value="">select a time</option>
                <option value="8:00-9:00 A.M.">8:00-9:00 A.M.</option>
                <option value="9:00-10:00 A.M.">9:00-10:00 A.M.</option>
                <option value="10:00-11:00 A.M.">10:00-11:00 A.M.</option>
                <option value="11:00-12:00 A.M.">11:00-12:00 A.M.</option>
                <option value="1:00-2:00 P.M.">1:00-2:00 P.M.</option>
                <option value="2:00-3:00 P.M.">2:00-3:00 P.M.</option>
                <option value="3:00-4:00 P.M.">3:00-4:00 P.M.</option>
                <option value="4:00-5:00 P.M.">4:00-5:00 P.M.</option>
                </select>
                <?= (isset($_GET['errTime']) && $_GET['errTime'] == "all_ready_taken_time") ? '<span class="text-danger">Someone already have an appointment that time!</span> <br>' : ''; ?>
                <?= (isset($_GET['errDup']) && $_GET['errDup'] == "you_have_already_an_appointment_in_that_time_with_different_doctor") ? '<span class="text-danger text-center">Already have an appointment in that time with different doctor!</span> <br>' : ''; ?>
                <?= (isset($_GET['errDup1']) && $_GET['errDup1'] == "you_already_made_an_appointment") ? '<span class="text-danger text-center">You can\'t duplicate a appointment!</span> <br>' : ''; ?>

                <label for="">Reason for Appointment or Diagnosis</label>
                <textarea name="reasonAppointment" class="form-control resize-0" cols="30" rows="10" required></textarea>
                <div class="text-center mt-3">
                    <input type="submit" class="btn" id="docBtnApt" value="Submit" name="submitAppointment">
                </div>
            </form>
        </div>


        <div class="container">
            <h4 class="lead my-5">Doctor Specialization</h4>

            <?php
            $sql = "SELECT * FROM doctor";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            while ($doctorSpec = $stmt->fetch(PDO::FETCH_ASSOC)) :
            ?>
                <div class="my-5">
                    <h6><?= $doctorSpec['dSpecialization']; ?></h6>
                    <p><?= $doctorSpec['dSpecializationInfo']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>


        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>