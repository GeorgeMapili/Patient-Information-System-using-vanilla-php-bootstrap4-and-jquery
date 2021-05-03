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
    <link rel="icon" href="../img/sumc.png">
    <title>Secretary | Patient Bill</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
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
                    <li class="nav-item active">
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
                            &nbsp;<?= $_SESSION['nName']; ?>
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

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Patient Bill</h3>

            <?php

            if (isset($_POST['generateBill'])) {
                $id = $_POST['id'];

                $sql = "SELECT * FROM walkinpatient WHERE walkInId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $patientBill = $stmt->fetch(PDO::FETCH_ASSOC);
                // SESSION -------------------------------------------------------------
                $_SESSION['walkInId'] = $patientBill['walkInId'];
                $_SESSION['walkInName'] = $patientBill['walkInName'];
                $_SESSION['walkInEmail'] = $patientBill['walkInEmail'];
                $_SESSION['walkInAddress'] = $patientBill['walkInAddress'];
                $_SESSION['walkInMobile'] = $patientBill['walkInMobile'];
                $_SESSION['walkInDoctor'] = $patientBill['walkInDoctor'];
                $_SESSION['walkInPrescription'] = $patientBill['walkInPrescription'];
                $_SESSION['medicineFee'] = $patientBill['medicineFee'];
                $_SESSION['walkInTotalPay'] = $patientBill['walkInTotalPay'];
                $_SESSION['dFee'] = $patientBill['doctorFee'];
                $_SESSION['walkInDisease'] = $patientBill['walkInDisease'];
            } else {

                if (isset($_POST['discharge'])) {

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

                    // Data in field
                    $id = $_POST['id'];
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $address = $_POST['address'];
                    $mobilenumber = $_POST['mobilenumber'];
                    $patientStatus = $_POST['patientStatus'];
                    $doctorName = $_POST['doctorName'];
                    $doctorFee = $_POST['doctorFee'];
                    $prescribeMed = $_POST['prescribeMed'];
                    $medicineFee = $_POST['medicineFee'];
                    $amountInput = $_POST['amountInput'];
                    $totalAmount = $_POST['totalAmount'];

                    // Change of the bill
                    $changeBill = 0;

                    // SESSION ------------------------------------------------------
                    $_SESSION['amountInput'] = $amountInput;

                    if ($amountInput >= $totalAmount) {
                        $_SESSION['change'] = $amountInput -  $totalAmount;

                        $discharge = 1;
                        // CHANGE STATUS
                        $sql = "UPDATE walkinpatient SET walkInDischarged = :discharged WHERE walkInId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":discharged", $discharge, PDO::PARAM_INT);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->execute();

                        // INSERT INTO DISCHARGED PATIENT TABLE
                        $sql = "INSERT INTO discharged_patient(pId,pName,pEmail,pAddress, pMobile, pDoctor, pPrescription, pDisease, pTotalAmount,pStatus,pAmountPay,pChange)VALUES(:id,:name,:email,:address,:mobile,:doctor,:prescription,:disease,:totalAmount,:status,:amountPay,:change)";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                        $stmt->bindParam(":mobile", $mobilenumber, PDO::PARAM_STR);
                        $stmt->bindParam(":doctor", $doctorName, PDO::PARAM_STR);
                        $stmt->bindParam(":prescription", $prescribeMed, PDO::PARAM_STR);
                        $stmt->bindParam(":disease", $_SESSION['walkInDisease'], PDO::PARAM_STR);
                        $stmt->bindParam(":totalAmount", $totalAmount, PDO::PARAM_STR);
                        $stmt->bindParam(":status", $patientStatus, PDO::PARAM_STR);
                        $stmt->bindParam(":amountPay", $_SESSION['amountInput'], PDO::PARAM_INT);
                        $stmt->bindParam(":change", $_SESSION['change'], PDO::PARAM_INT);
                        $stmt->execute();

                        // DELETE IT FROM PATIENTWALKIN TABLE || JUST COMMENT IF SOMETHING MAKE WRONG
                        $sql = "DELETE FROM walkinpatient WHERE walkInId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->execute();

                        $data['message'] = 'hello world';
                        $pusher->trigger('my-channel', 'my-event', $data);

                        // INSERT INTO RETURNEE PATIENT TABLE FOR DOCTOR MEDICAL HISTORY
                        $sql = "INSERT INTO returnee_patient(pId,pName,pEmail,pAddress, pMobile, pDoctor, pPrescription, pDisease, pTotalAmount,pStatus,pAmountPay,pChange)VALUES(:id,:name,:email,:address,:mobile,:doctor,:prescription,:disease,:totalAmount,:status,:amountPay,:change)";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
                        $stmt->bindParam(":mobile", $mobilenumber, PDO::PARAM_STR);
                        $stmt->bindParam(":doctor", $doctorName, PDO::PARAM_STR);
                        $stmt->bindParam(":prescription", $prescribeMed, PDO::PARAM_STR);
                        $stmt->bindParam(":disease", $_SESSION['walkInDisease'], PDO::PARAM_STR);
                        $stmt->bindParam(":totalAmount", $totalAmount, PDO::PARAM_STR);
                        $stmt->bindParam(":status", $patientStatus, PDO::PARAM_STR);
                        $stmt->bindParam(":amountPay", $_SESSION['amountInput'], PDO::PARAM_INT);
                        $stmt->bindParam(":change", $_SESSION['change'], PDO::PARAM_INT);
                        $stmt->execute();

                        header("location:discharge.php?dischargeWalkInPatient=true");
                        exit(0);
                    } else {
                        header("location:generateBill.php?errAmount=too_low_amount");
                        exit(0);
                    }
                }
            }

            ?>

            <div class="container">
                <div class="row justify-content-center bg-light shadow-lg p-3 mb-5 bg-white rounded mt-5">
                    <div class="col-lg-6 px-4 pb-4" id="order">

                        <div class="text-center my-3">
                            <?= (isset($_GET['errAmount']) && $_GET['errAmount'] == "too_low_amount" ? '<span class="text-danger">Amount is too low!</span>' : '') ?>
                        </div>

                        <form action="generateBill.php" method="post" id="placeOrder">
                            <!-- <input type="hidden" name="orderedfood" value="123">
                            <input type="hidden" name="orderedtotalamount" value="123">
                            <input type="hidden" name="userId" value="123"> -->
                            <h1 class=" text-center mt-3">Patient information</h1>

                            <input type="hidden" name="id" class="id" value="<?= $_SESSION['walkInId'] ?>">

                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" value="<?= $_SESSION['walkInName'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" value="<?= $_SESSION['walkInEmail'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" value="<?= $_SESSION['walkInAddress'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="tel" name="mobilenumber" class="form-control" value="<?= $_SESSION['walkInMobile'] ?>" readonly>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Name</label>
                                    <input type="text" name="doctorName" value="<?= $_SESSION['walkInDoctor'] ?>" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Fee</label>
                                    <input type="text" name="doctorFee" class="form-control doctorFee" value="<?= $_SESSION['dFee'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Prescribe Medicine</label>
                                    <textarea name="prescribeMed" class="form-control" cols="30" rows="10" readonly><?= $_SESSION['walkInPrescription'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6" id="price">
                                    <label for="">Medicine Fee</label>
                                    <input type="number" name="medicineFee" autocomplete="off" class="form-control medPrice" min="0" value="<?= $_SESSION['medicineFee'] ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Amount Input</label>
                                    <!-- <input type="number" name="amountInput" min="1" value="AmountPay" class="form-control"> -->
                                    <?= (isset($_GET['errAmount']) && $_GET['errAmount'] == "too_low_amount") ? '<input type="number" name="amountInput" min="1" value="AmountPay" class="form-control is-invalid">' : '<input type="number" name="amountInput" min="1" value="AmountPay" class="form-control">'; ?>
                                    <?= (isset($_GET['errAmount']) && $_GET['errAmount'] == "too_low_amount") ? '<span class="text-danger">Amount is too low!</span>' : ''; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Total Amount</label>
                                    <input type="text" name="totalAmount" id="totalAmountBill" class="form-control" value="<?= $_SESSION['walkInTotalPay'] ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">Patient Status</label>
                                <select name="patientStatus" id="" class="form-control" required>
                                    <option value="">select status</option>
                                    <option value="expired">Expired</option>
                                    <option value="awful">Awful</option>
                                    <option value="good">Good</option>
                                    <option value="better">Better</option>
                                </select>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <input type="submit" name="discharge" class="btn btn-info" value="Discharge">
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>


        <hr class="featurette-divider">

        <!-- /END THE FEATURETTES -->

        <!-- FOOTER -->
        <footer class="text-center">
            <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
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

    <script>
        $(document).ready(function() {
            $(".medPrice").on('change', function() {
                var $el = $(this).closest("form");

                var medPrice = $el.find('.medPrice').val();
                var doctorFee = $el.find('.doctorFee').val();
                // var roomFee = $el.find('.roomFee').val();
                var id = $el.find('.id').val();

                // location.reload(true);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    cache: false,
                    data: {
                        medPrice: medPrice,
                        doctorFee: doctorFee,
                        // roomFee: roomFee,
                        id: id
                    },
                    success: function(response) {
                        // console.log(response);
                        $("#totalAmountBill").val(response);
                        // window.location.reload();
                    }
                });

            });
        });
    </script>

</body>

</html>