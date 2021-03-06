<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

if(isset($_GET['id'])){
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
        <link rel="icon" href="../img/sumc.png">
        <title>Admin | Update Patient</title>
    </head>

    <body>

        <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">SUMC Doctors Clinic</a>
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
                                <a class="nav-link" href="walkInPatient.php" id="primaryColor">
                                    <span data-feather="shopping-cart"></span>
                                    View All Walk in patient
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="nurse.php">
                                    <span data-feather="users"></span>
                                    View All Secretary
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
                                <a class="nav-link" href="walkInDischarged.php">
                                    <span data-feather="users"></span>
                                    View Walkin Patient Discharged
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="messages.php">
                                    <span data-feather="users"></span>
                                    View All Messages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="diseaseTreatment.php">
                                    <span data-feather="users"></span>
                                    View All Diseases & Treatment
                                </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="audit-log.php">
                                <span data-feather="users"></span>
                                View Audit Logs
                            </a>
                        </li>
                        </ul>

                    </div>
                </nav>

                <?php
                if (isset($_GET['updateWalkInBtn'])) {

                    $walkInId = $_GET['id'];

                    $sql = "SELECT * FROM walkinpatient WHERE walkInId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":id", $walkInId, PDO::PARAM_INT);
                    $stmt->execute();

                    $walkIn = $stmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['walkInPatientId'] = $walkIn['walkInId'];
                    $_SESSION['walkInPatientName'] = $walkIn['walkInName'];
                    $_SESSION['walkInPatientAddress'] = $walkIn['walkInAddress'];
                    $_SESSION['walkInPatientMobile'] = $walkIn['walkInMobile'];
                    $_SESSION['walkInPatientDisease'] = $walkIn['walkInDisease'];
                    $_SESSION['walkInPatientAge'] = $walkIn['walkInAge'];
                    $_SESSION['walkInPatientGender'] = $walkIn['walkInGender'];
                    $_SESSION['walkInPatientDoctor'] = $walkIn['walkInDoctor'];
                }
                ?>

                <?php

                if (isset($_POST['updateWalkIn'])) {

                    $id = trim(htmlspecialchars($_POST['id']));
                    $name = trim(htmlspecialchars($_POST['name']));
                    $address = trim(htmlspecialchars($_POST['address']));
                    $mobile = trim(htmlspecialchars($_POST['mobile']));
                    $disease = trim(htmlspecialchars($_POST['disease']));
                    $age = trim(htmlspecialchars($_POST['age']));
                    $gender = trim(htmlspecialchars($_POST['gender']));
                    $doctor = trim(htmlspecialchars($_POST['doctor']));

                    if ($name == $_SESSION['walkInPatientName'] && $address == $_SESSION['walkInPatientAddress'] && $mobile == $_SESSION['walkInPatientMobile'] && $disease == $_SESSION['walkInPatientDisease'] && $age == $_SESSION['walkInPatientAge'] && $gender == $_SESSION['walkInPatientGender'] && $doctor == $_SESSION['walkInPatientDoctor']) {
                        header("location:walkInPatient.php?id=$id&errUpdate=Nothing_to_update");
                        exit(0);
                    }

                    // check if name is valid
                    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                        header("location:updateWalkInPatient.php?id=$id&errName=name_is_not_valid");
                        ob_end_flush();
                        exit(0);
                    }

                    // check if the name is already taken
                    $sql = "SELECT * FROM walkinpatient WHERE walkInName = :name AND walkInId <> :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $nameCount = $stmt->rowCount();

                    if ($nameCount > 0) {
                        header("location:updateWalkInPatient.php?id=$id&errName1=Name_is_already_existed");
                        exit(0);
                    }

                    // check if the mobile number is already taken
                    $sql = "SELECT * FROM walkinpatient WHERE walkInMobile = :mobile AND walkInId <> :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":mobile", $mobile, PDO::PARAM_INT);
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $mobileCount = $stmt->rowCount();

                    if ($mobileCount > 0) {
                        header("location:updateWalkInPatient.php?id=$id&errMobile=Mobile_number_is_already_existed");
                        exit(0);
                    }

                    // Get the doctorfee
                    $sql = "SELECT * FROM doctor WHERE dName = :dname";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":dname", $doctor, PDO::PARAM_STR);
                    $stmt->execute();

                    $doctorFees = $stmt->fetch(PDO::FETCH_ASSOC);

                    $doctorFee = $doctorFees['dFee'];

                    // Update the walkInTotalPay
                    $walkInTotalPay = $doctorFee;

                    // Update THE WALK IN PATIENT INFO
                    $sql = "UPDATE walkinpatient SET walkInName = :name, walkInAddress = :address, walkInAge = :age, walkInGender = :gender, walkInMobile = :mobile, walkInDoctor = :doctor, walkInDisease = :disease, doctorFee = :doctorFee, walkInTotalPay = :walkTotalPay WHERE walkInId = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                    $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                    $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
                    $stmt->bindParam(":disease", $disease, PDO::PARAM_STR);
                    $stmt->bindParam(":age", $age, PDO::PARAM_STR);
                    $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
                    $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
                    $stmt->bindParam(":doctorFee", $doctorFee, PDO::PARAM_STR);
                    $stmt->bindParam(":walkTotalPay", $walkInTotalPay, PDO::PARAM_STR);
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();

                    header("location:walkInPatient.php?succUpdateWalkIn=Successfully_added_walk_in_patient");
                    exit(0);
                }
                ?>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                    <div class="container">

                        <div class="mt-4 mb-4">
                            <h1 class="Display-4" id="primaryColor">Update Walk in Patient</h1>
                        </div>

                        <form action="updateWalkInPatient.php?id=$id" method="post">
                            <div class="row my-4">
                                <input type="hidden" name="id" value="<?= $_SESSION['walkInPatientId'] ?>">
                                <div class="col">
                                    <label>Patient Name</label>
                                    <?= ((isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") || (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed")) ? '<input type="text" name="name" class="form-control is-invalid" required>' : '<input type="text" name="name" class="form-control" value="' . $_SESSION['walkInPatientName'] . '" required>' ?>
                                    <?= (isset($_GET['errName']) && $_GET['errName'] == "name_is_not_valid") ? '<small class="text-danger">Name is not valid!</small>' : '' ?>
                                    <?= (isset($_GET['errName1']) && $_GET['errName1'] == "Name_is_already_existed") ? '<small class="text-danger">Name is already existed!</small>' : '' ?>
                                </div>
                                <div class="col">
                                    <label>Patient Mobile Number</label>
                                    <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<input type="tel" name="mobile" class="form-control is-invalid" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" name="mobile" class="form-control" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" value="' . $_SESSION['walkInPatientMobile'] . '" required>' ?>
                                    <?= (isset($_GET['errMobile']) && $_GET['errMobile'] == "Mobile_number_is_already_existed") ? '<small class="text-danger">Mobile number is already existed!</small>' : '' ?>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col">
                                    <label>Patient Address</label>
                                    <input type="text" name="address" class="form-control" value="<?= $_SESSION['walkInPatientAddress'] ?>" required>
                                </div>
                                <div class="col">
                                    <label>Patient Age</label>
                                    <input type="date" name="age" class="form-control" value="<?= $_SESSION['walkInPatientAge'] ?>" required>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="col">
                                    <label>Patient Disease</label>
                                    <input type="text" name="disease" class="form-control" value="<?= $_SESSION['walkInPatientDisease'] ?>" required>
                                </div>
                                <div class="col">
                                    <label>Gender</label>
                                    <div class="d-flex justify-content-around">
                                        <?php
                                        if (isset($_SESSION['walkInPatientGender']) && $_SESSION['walkInPatientGender'] === "male") {
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
                                        if (isset($_SESSION['walkInPatientGender']) && $_SESSION['walkInPatientGender'] === "female") {
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

                            <div>
                                <label>Doctor</label>
                                <select name="doctor" class="form-control" required>
                                    <option value="">Select a doctor</option>
                                    <?php
                                    $sql = "SELECT * FROM doctor";
                                    $stmt = $con->prepare($sql);
                                    $stmt->execute();

                                    while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                    ?>
                                        <option value="<?= $doctor['dName'] ?>" <?php
                                                                                if ($_SESSION['walkInPatientDoctor'] == $doctor['dName']) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?= $doctor['dName'] ?> -> <?= $doctor['dSpecialization'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class=" text-center mt-3">
                                <input type="submit" class="btn btn-info" value="Update Walk in Patient" name="updateWalkIn">
                            </div>
                        </form>
                    </div>

                </main>
            </div>
        </div>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
}else{
    header("location:dashboard.php");
    exit;
}
?>