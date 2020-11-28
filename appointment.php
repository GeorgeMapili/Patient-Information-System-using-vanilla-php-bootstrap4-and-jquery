<?php
session_start();
require_once 'connect.php';

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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <title>Patient | Appointment</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="main.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="appointment.php">Set an Appointment</a>
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
                    <li class="nav-item">
                        <a class="nav-link " href="myappointment.php">My Appointments</a>
                    </li>
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

        if (isset($_POST['submitAppointment'])) {
            $pId = $_POST['id'];
            $pName = $_POST['name'];
            $pEmail = $_POST['email'];
            $pAddress = $_POST['address'];
            $pMobile = $_POST['mobileNumber'];
            $pDoctor = $_POST['selectDoctor'];
            $aDate = $_POST['dateOfAppointment'];
            $aTime = $_POST['timeOfAppointment'];
            $aReason = trim(htmlspecialchars($_POST['reasonAppointment']));

            // Get the Doctor Fee
            $sql = "SELECT * FROM doctor WHERE dName = :name";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":name", $pDoctor, PDO::PARAM_STR);
            $stmt->execute();
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            $dFee = $doctor['dFee'];

            $sql = "INSERT INTO appointment (pId,pName,pEmail,pAddress,pMobile,pDoctor,dFee,aReason,aDate,aTime)VALUES(:id,:name,:email,:address,:mobile,:doctor,:fee,:reason,:date,:time)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":id", $pId, PDO::PARAM_INT);
            $stmt->bindParam(":name", $pName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $pEmail, PDO::PARAM_STR);
            $stmt->bindParam(":address", $pAddress, PDO::PARAM_STR);
            $stmt->bindParam(":mobile", $pMobile, PDO::PARAM_STR);
            $stmt->bindParam(":doctor", $pDoctor, PDO::PARAM_STR);
            $stmt->bindParam(":fee", $dFee, PDO::PARAM_STR);
            $stmt->bindParam(":reason", $aReason, PDO::PARAM_STR);
            $stmt->bindParam(":date", $aDate, PDO::PARAM_STR);
            $stmt->bindParam(":time", $aTime, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("location:appointment.php?AppointmentSuccess=Successully_request_an_appointment");
                exit(0);
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

            <form action="appointment.php" method="post">
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

                <label for="">Date</label>
                <input type="date" class="form-control" name="dateOfAppointment" required>
                <input type="time" class="form-control" name="timeOfAppointment" required>

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
            <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>