<?php
ob_start();
session_start();
require_once '../connect.php';
require __DIR__ . '/../vendor/autoload.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_secretary_add_walkin'] = true;

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
    <title>Secretary | Add Patient</title>
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
                    $status = "pending";
                    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                    $stmt->execute();
                    $pendingCount = $stmt->rowCount();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pendings.php">Pending Appointments&nbsp;<?= ($pendingCount > 0) ? '<span id="pending-appointment" class="badge bg-danger">' . $pendingCount . '</span>' : '<span id="pending-appointment" class="badge bg-danger"></span>'; ?></a>
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
                        <a class="nav-link" href="patient-appointments.php">Patient from appointments&nbsp;<?= ($patientAppointment > 0) ? '<span id="patient-appointment" class="badge bg-danger">' . $patientAppointment . '</span>' : '<span id="patient-appointment" class="badge bg-danger"></span>'; ?></a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM walkinpatient";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $walkinpatient = $stmt->rowCount();
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient-walkin.php">Patient Walk in&nbsp;<?= ($walkinpatient > 0) ? '<span id="walkinpatient" class="badge bg-danger">' . $walkinpatient . '</span>' : '<span id="walkinpatient" class="badge bg-danger"></span>'; ?></a>
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
                            &nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="account.php">My account</a>
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

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Add Patient</h3>

            <?php

            if (isset($_POST['addPatient'])) {

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

                $name = trim(htmlspecialchars($_POST['name']));
                $address = trim(htmlspecialchars($_POST['address']));
                $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
                $disease = trim(htmlspecialchars($_POST['disease']));
                $age = trim(htmlspecialchars($_POST['age']));
                $gender = trim(htmlspecialchars($_POST['gender']));
                $doctor = trim(htmlspecialchars($_POST['doctor']));

                // check if the patient is already been patient before
                $sql = "SELECT * FROM returnee_patient WHERE pName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->execute();

                $patientRow = $stmt->rowCount();
                if ($patientRow > 0) {
                    header("location:add-patient.php?errPatient=already_an_patient_before");
                    exit(0);
                }

                // Check if to fill all fields
                if (empty($name) || empty($address) || empty($mobileNumber) || empty($disease) || empty($age) || empty($gender) || empty($doctor)) {
                    header("location:add-patient.php?errField=please_input_all_fields");
                    exit(0);
                }

                // Check if the name is valid
                if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                    header("location:add-patient.php?errName=name_is_not_valid&address=$address&email=$email&mobile=$mobileNumber&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                // Check if the name is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->execute();

                $nameCount = $stmt->rowCount();

                if ($nameCount > 0) {
                    header("location:add-patient.php?errName1=name_is_already_taken&address=$address&email=$email&mobile=$mobileNumber&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                // Check if the mobile number is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInMobile = :mobile";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_INT);
                $stmt->execute();

                $mobileCount = $stmt->rowCount();

                if ($mobileCount > 0) {
                    header("location:add-patient.php?errMobile=mobile_number_is_already_taken&address=$address&name=$name&email=$email&disease=$disease&age=$age&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                $newAge = strtotime($age);
                $now = strtotime(date("Y-m-d"));

                if(date_diff(date_create($newAge), date_create(date("Y-m-d")))->y <= 0 && $newAge > $now){
                    header("location:add-patient.php?errAge=invalid_date&address=$address&name=$name&email=$email&disease=$disease&mobile=$mobileNumber&gender=$gender&doctor=$doctor");
                    exit(0);
                }

                // Doctor Fee
                $sql = "SELECT * FROM doctor WHERE dName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $doctor, PDO::PARAM_STR);
                $stmt->execute();

                $doctorfee = $stmt->fetch(PDO::FETCH_ASSOC);

                // Total Pay
                $totalPay = $doctorfee['dFee'];

                $sql = "INSERT INTO walkinpatient(walkInName,walkInAddress,walkInAge,walkInGender,walkInDoctor,walkInDisease,walkInMobile,doctorFee,walkInTotalPay)VALUES(:name,:address,:age,:gender,:doctor,:disease,:mobile,:doctorFee,:totalPay)";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                $stmt->bindParam(":age", $age, PDO::PARAM_STR);
                $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
                $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                $stmt->bindParam(":disease", $disease, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
                $stmt->bindParam(":doctorFee", $doctorfee['dFee'], PDO::PARAM_STR);
                $stmt->bindParam(":totalPay", $totalPay, PDO::PARAM_STR);
                $stmt->execute();

                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:add-patient.php?addSucc=Successfully_added_new_walkin_patient");
                $_SESSION['log_secretary_add_walkin_patient'] = true;
                ob_end_flush();
                exit(0);
            }

            ?>

            <div class="shadow-lg p-3 mb-5 bg-white rounded mt-5">

                <?= (isset($_GET['addSucc']) && $_GET['addSucc'] == "Successfully_added_new_walkin_patient") ? '<div class="text-center"><h3 class="text-success">Successfully added new walk in patient!</h3></div>' : '';  ?>
                <?= (isset($_GET['errField']) && $_GET['errField'] == "please_input_all_fields") ? '<div class="text-center"><h3 class="text-danger">Please input all fields!</h3></div>' : '';  ?>
                <?= (isset($_GET['errPatient']) && $_GET['errPatient'] == "already_an_patient_before") ? '<div class="text-center"><h3 class="text-danger">Already an patient before!</h3></div>' : '';  ?>

                <form action="add-patient.php" method="post">
                    <div class="row">
                        <div class="col m-1">
                            <label>Full Name</label>
                            <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || (isset($_GET['errName1']) && $_GET['errName1'] == "name_is_already_taken")) ? '<input type="text" name="name" class="form-control is-invalid" required>' : ((isset($_GET['name'])) ? '<input type="text" name="name" value="' . $_GET['name'] . '" class="form-control" required>' : '<input type="text" name="name" class="form-control" required>') ?>
                            <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<small class="text-danger">Name is not valid!</small>' : ''; ?>
                            <?= (isset($_GET['errName1']) && $_GET['errName1'] == "name_is_already_taken") ? '<small class="text-danger">Name is already taken!</small>' : ''; ?>
                        </div>
                        <div class="col m-1">
                            <label>Address</label>
                            <?= (isset($_GET['address'])) ? '<input type="text" name="address" value="' . $_GET['address'] . '" class="form-control" required>' : '<input type="text" name="address" class="form-control" required>' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col m-1">
                            <label>Disease</label>
                            <?= (isset($_GET['disease'])) ? '<input type="text" name="disease" value="' . $_GET['disease'] . '" class="form-control" required>' : '<input type="text" name="disease" class="form-control" required>' ?>
                        </div>
                        <div class="col m-1">
                            <label>Mobile Number</label>
                            <!-- <input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required> -->
                            <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "mobile_number_is_already_taken") ? '<input type="tel" class="form-control is-invalid" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : ((isset($_GET['mobile'])) ? '<input type="tel" class="form-control" name="mobileNumber" value= "' . str_replace(' ', '+', $_GET['mobile']) . '" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>') ?>
                            <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "mobile_number_is_already_taken") ? '<small class="text-danger">Mobile Number is already taken!</small>' : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col m-1">
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="d-flex justify-content-around">
                                    <?php
                                    if (isset($_GET['gender']) && $_GET['gender'] === "male") {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="male" value="male" checked required>
                                            <label for="male">Male</label>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="male" value="male" required>
                                            <label for="male">Male</label>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if (isset($_GET['gender']) && $_GET['gender'] === "female") {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="female" value="female" checked required>
                                            <label for="female">Female</label>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div>
                                            <input type="radio" name="gender" id="female" value="female" required>
                                            <label for="female">Female</label>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col m-1">
                            <label>Birthday</label>
                            <?= (isset($_GET['age'])) ? '<input type="date" name="age" value="' . $_GET['age'] . '" class="form-control" required>' : ((isset($_GET['errAge']) && $_GET['errAge'] == "invalid_date") ? '<input type="date" name="age" class="form-control is-invalid" required>' : '<input type="date" name="age" class="form-control" required>') ?>
                            <?= (isset($_GET['errAge']) && $_GET['errAge'] == "invalid_date") ? '<small class="text-danger">Invalide date</small>': '' ?>
                        </div>
                    </div>

                    <div>
                        <label>Select a Doctor</label>
                        <select class="form-control" name="doctor" required>
                            <option value="">select a doctor</option>
                            <?php
                            $sql = "SELECT * FROM doctor";
                            $stmt = $con->prepare($sql);
                            $stmt->execute();

                            while ($doctors = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <option value="<?= $doctors['dName'] ?>" <?php
                                                                            if (isset($_GET['doctor']) && $_GET['doctor'] == $doctors['dName']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>><?= $doctors['dName'] ?> -> <?= $doctors['dSpecialization'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <input type="submit" name="addPatient" value="Add Patient" class="mt-5 btn btn-info">
                    </div>
                </form>
            </div>


            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

            <!-- FOOTER -->
            <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
        </footer>
        </div>
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