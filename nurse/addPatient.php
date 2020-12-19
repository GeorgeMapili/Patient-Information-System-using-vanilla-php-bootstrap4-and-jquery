<?php
session_start();
require_once '../connect.php';

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
    <title>Nurse | Patient</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="appointmentPending.php">Pending Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient from appointments</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="patientWalkIn.php">Patient Walk in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room.php">Room</a>
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
        <div class="container">

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Add Patient</h3>

            <?php

            if (isset($_POST['addPatient'])) {

                $name = trim(htmlspecialchars($_POST['name']));
                $address = trim(htmlspecialchars($_POST['address']));
                $email = trim(htmlspecialchars($_POST['email']));
                $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
                $disease = trim(htmlspecialchars($_POST['disease']));
                $age = trim(htmlspecialchars($_POST['age']));
                $gender = trim(htmlspecialchars($_POST['gender']));
                $doctor = trim(htmlspecialchars($_POST['doctor']));
                $roomNumber = trim(htmlspecialchars($_POST['roomNumber']));

                // Check if to fill all fields
                if (empty($name) || empty($address) || empty($email) || empty($mobileNumber) || empty($disease) || empty($age) || empty($gender) || empty($doctor) || empty($roomNumber)) {
                    header("location:addPatient.php?errField=please_input_all_fields");
                    exit(0);
                }

                // Check if the name is valid
                if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                    header("location:addPatient.php?errName=name_is_not_valid");
                    exit(0);
                }

                // Check if the name is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->execute();

                $nameCount = $stmt->rowCount();

                if ($nameCount > 0) {
                    header("location:addPatient.php?errName1=name_is_already_taken");
                    exit(0);
                }

                // Check if the email is valid
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    header("location:addPatient.php?errEmail1=email_is_not_valid");
                    exit(0);
                }

                // Check  if the email is already existed
                $sql = "SELECT * FROM walkinpatient WHERE walkInEmail = :email";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->execute();

                $emailCount = $stmt->rowCount();

                if ($emailCount > 0) {
                    header("location:addPatient.php?errEmail2=email_is_already_taken");
                    exit(0);
                }

                // Check if the mobile number is already taken
                $sql = "SELECT * FROM walkinpatient WHERE walkInMobile = :mobile";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_INT);
                $stmt->execute();

                $mobileCount = $stmt->rowCount();

                if ($mobileCount > 0) {
                    header("location:addPatient.php?errMobile=mobile_number_is_already_taken");
                    exit(0);
                }

                // Doctor Fee
                $sql = "SELECT * FROM doctor WHERE dName = :name";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $doctor, PDO::PARAM_STR);
                $stmt->execute();

                $doctorfee = $stmt->fetch(PDO::FETCH_ASSOC);

                // Room Fee
                $sql = "SELECT * FROM rooms WHERE room_number = :number";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":number", $roomNumber, PDO::PARAM_INT);
                $stmt->execute();

                $roomfee = $stmt->fetch(PDO::FETCH_ASSOC);

                // Total Pay
                $totalPay = $doctorfee['dFee'] + $roomfee['room_fee'];

                $sql = "INSERT INTO walkinpatient(walkInName,walkInEmail,walkInAddress,walkInAge,walkInGender,walkInDoctor,walkInDisease,walkInRoomNumber,walkInMobile,doctorFee,roomFee,walkInTotalPay)VALUES(:name,:email,:address,:age,:gender,:doctor,:disease,:roomNumber,:mobile,:doctorFee,:roomFee,:totalPay)";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                $stmt->bindParam(":age", $age, PDO::PARAM_STR);
                $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
                $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                $stmt->bindParam(":disease", $disease, PDO::PARAM_STR);
                $stmt->bindParam(":roomNumber", $roomNumber, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
                $stmt->bindParam(":doctorFee", $doctorfee['dFee'], PDO::PARAM_STR);
                $stmt->bindParam(":roomFee", $roomfee['room_fee'], PDO::PARAM_STR);
                $stmt->bindParam(":totalPay", $totalPay, PDO::PARAM_STR);
                $stmt->execute();

                header("location:addPatient.php?addSucc=Successfully_added_new_walkin_patient");

                // Update the rooms from AVAILABLE to OCCUPIED
                $status = "occupied";
                $sql = "UPDATE rooms SET room_status = :status WHERE room_number = :number";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":number", $roomNumber, PDO::PARAM_INT);
                $stmt->execute();

                exit(0);
            }

            ?>

            <?= (isset($_GET['addSucc']) && $_GET['addSucc'] == "Successfully_added_new_walkin_patient") ? '<div class="text-center"><h3 class="text-success">Successfully added new walk in patient!</h3></div>' : '';  ?>
            <?= (isset($_GET['errField']) && $_GET['errField'] == "please_input_all_fields") ? '<div class="text-center"><h3 class="text-danger">Please input all fields!</h3></div>' : '';  ?>

            <form action="addPatient.php" method="post">
                <div class="row">
                    <div class="col m-1">
                        <label>Full Name</label>
                        <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || (isset($_GET['errName1']) && $_GET['errName1'] == "name_is_already_taken")) ? '<input type="text" name="name" class="form-control is-invalid" required>' : '<input type="text" name="name" class="form-control" required>'; ?>
                        <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<span class="text-danger">Name is not valid!</span>' : ''; ?>
                        <?= (isset($_GET['errName1']) && $_GET['errName1'] == "name_is_already_taken") ? '<span class="text-danger">Name is already taken!</span>' : ''; ?>
                    </div>
                    <div class="col m-1">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col m-1">
                        <label>Email</label>
                        <?= ((isset($_GET['errEmail1']) && $_GET['errEmail1'] == "email_is_not_valid") || (isset($_GET['errEmail2']) && $_GET['errEmail2'] == "email_is_already_taken")) ? '<input type="text" name="email" class="form-control is-invalid" required>' : '<input type="text" name="email" class="form-control" required>'; ?>
                        <?= (isset($_GET['errEmail1']) && $_GET['errEmail1'] == "email_is_not_valid") ? '<span class="text-danger">Email is not valid!</span>' : ''; ?>
                        <?= (isset($_GET['errEmail2']) && $_GET['errEmail2'] == "email_is_already_taken") ? '<span class="text-danger">Email is already taken!</span>' : ''; ?>
                    </div>
                    <div class="col m-1">
                        <label>Mobile Number</label>
                        <!-- <input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required> -->
                        <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "mobile_number_is_already_taken") ? '<input type="tel" class="form-control is-invalid" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'; ?>
                        <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "mobile_number_is_already_taken") ? '<span class="text-danger">Mobile Number is already taken!</span>' : ''; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col m-1">
                        <label>Disease</label>
                        <input type="text" name="disease" class="form-control" required>
                    </div>
                    <div class="col m-1">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col m-1">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">select a gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col m-1">
                        <label>Select a Doctor</label>
                        <select class="form-control" name="doctor" required>
                            <option value="">select a doctor</option>
                            <?php
                            $sql = "SELECT * FROM doctor";
                            $stmt = $con->prepare($sql);
                            $stmt->execute();

                            while ($doctors = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <option value="<?= $doctors['dName'] ?>"><?= $doctors['dName'] ?> -> <?= $doctors['dSpecialization'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <!-- <div class="col m-1">
                        <label>Select a Building</label>
                        <select class="form-control" name="doctor" required>
                            <option selected="selected" disabled="disabled" value="">select a building</option>
                            <option value="1">Bldg1</option>
                            <option value="2">Bldg2</option>
                            <option value="3">Bldg3</option>
                            <option value="4">Bldg4</option>
                            <option value="5">Bldg5</option>
                        </select>
                    </div> -->
                    <div class="col m-1">
                        <label>Select a room</label>
                        <select class="form-control" name="roomNumber" required>
                            <option value="">select a room</option>
                            <!-- SELECTING A ROOM QUERY -->

                            <?php
                            $status = "available";
                            $sql = "SELECT * FROM rooms WHERE room_status = :status";
                            $stmt = $con->prepare($sql);
                            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                            $stmt->execute();

                            if (isset($_POST['availableRoom'])) {
                                $addPatientRoom = $_POST['roomNumber'];
                            }

                            while ($rooms = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <?php

                                if ($rooms['room_number'] == $addPatientRoom) {
                                ?>

                                    <option value="<?= $rooms['room_number'] ?>" selected><?= $rooms['room_number'] ?></option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?= $rooms['room_number'] ?>"><?= $rooms['room_number'] ?></option>
                                <?php
                                }
                                ?>


                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" name="addPatient" value="Add Patient" class="mt-5 btn btn-primary">
                </div>
            </form>


            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

            <!-- FOOTER -->
            <footer class="text-center">
                <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
            </footer>
        </div>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>