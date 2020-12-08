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
    <title>Nurse | Medical Information</title>
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
                    <li class="nav-item">
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

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Patient Bill</h3>

            <?php

            if (isset($_POST['generateBillAppointment'])) {
                $id = $_POST['id'];
                $aid = $_POST['aid'];

                $sql = "SELECT * FROM appointment WHERE pId = :id AND aId = :aid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->bindParam(":aid", $aid, PDO::PARAM_INT);
                $stmt->execute();

                $patientAppointmentBill = $stmt->fetch(PDO::FETCH_ASSOC);
                // SESSION -------------------------------------------------------------
                $_SESSION['Pa_aId'] = $patientAppointmentBill['aId'];
                $_SESSION['Pa_pId'] = $patientAppointmentBill['pId'];
                $_SESSION['Pa_name'] = $patientAppointmentBill['pName'];
                $_SESSION['Pa_email'] = $patientAppointmentBill['pEmail'];
                $_SESSION['Pa_address'] = $patientAppointmentBill['pAddress'];
                $_SESSION['Pa_mobile'] = $patientAppointmentBill['pMobile'];
                $_SESSION['Pa_doctor'] = $patientAppointmentBill['pDoctor'];
                $_SESSION['Pa_prescription'] = $patientAppointmentBill['pPrescription'];
                $_SESSION['Pa_mFee'] = $patientAppointmentBill['pMedicineFee'];
                $_SESSION['Pa_totalPay'] = $patientAppointmentBill['pTotalPay'];
                $_SESSION['Pa_dFee'] = $patientAppointmentBill['dFee'];
                $_SESSION['Pa_Disease'] = $patientAppointmentBill['aReason'];
            }

            ?>

            <div class="container">
                <div class="row justify-content-center bg-light">
                    <div class="col-lg-6 px-4 pb-4" id="order">

                        <?php

                        if (isset($_POST['dischargeAppointment'])) {
                            // Data in field
                            $aid = $_POST['aId'];
                            $pid = $_POST['pId'];
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
                            // $_SESSION['amountInput'] = $amountInput;

                            if ($amountInput >= $totalAmount) {
                                $changeBill = $amountInput -  $totalAmount;
                                $date = date("M d, Y");

                                $status = "discharged";
                                $discharge = 1;
                                // UPDATE THE STATUS OF THE PATIENT FROM DONE TO DISCHARGE
                                $sql = "UPDATE appointment SET aStatus = :status, pDischarge = :discharge, patientStatus =:statusPatient, pAmountPay = :amountPay, pChange = :change, dischargedOn = :dischargedOn  WHERE aId = :aid AND pId = :pid";
                                $stmt = $con->prepare($sql);
                                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                                $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
                                $stmt->bindParam(":statusPatient", $patientStatus, PDO::PARAM_STR);
                                $stmt->bindParam(":amountPay", $amountInput, PDO::PARAM_INT);
                                $stmt->bindParam(":change", $changeBill, PDO::PARAM_INT);
                                $stmt->bindParam(":dischargedOn", $date, PDO::PARAM_STR);
                                $stmt->bindParam(":aid", $aid, PDO::PARAM_INT);
                                $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
                                $stmt->execute();

                                header("location:dischargePa.php");
                                exit(0);
                            } else {
                                header("location:generateBillAppointment.php?errAmount=too_low_amount");
                                exit(0);
                            }
                        }
                        ?>
                        <form action="generateBillAppointment.php" method="post" id="placeOrder">
                            <h1 class=" text-center mt-3">Patient information</h1>

                            <input type="hidden" name="aId" class="aid" value="<?= $_SESSION['Pa_aId'] ?>">
                            <input type="hidden" name="pId" class="pid" value="<?= $_SESSION['Pa_pId'] ?>">

                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" value="<?= $_SESSION['Pa_name'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" value="<?= $_SESSION['Pa_email'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" value="<?= $_SESSION['Pa_address'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="tel" name="mobilenumber" class="form-control" value="<?= $_SESSION['Pa_mobile'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Patient Disease</label>
                                <input type="text" name="patientDisease" value="<?= $_SESSION['Pa_Disease'] ?>" class="form-control" readonly>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Name</label>
                                    <input type="text" name="doctorName" value="<?= $_SESSION['Pa_doctor'] ?>" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Fee</label>
                                    <input type="text" name="doctorFee" class="form-control doctorFee" value="<?= $_SESSION['Pa_dFee'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Prescribe Medicine</label>
                                    <textarea name="prescribeMed" class="form-control" cols="30" rows="10" readonly><?= $_SESSION['Pa_prescription'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6" id="price">
                                    <label for="">Medicine Fee</label>
                                    <input type="number" name="medicineFee" autocomplete="off" class="form-control medPrice" min="0" value="<?= $_SESSION['Pa_mFee'] ?>">
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
                                    <input type="text" name="totalAmount" class="form-control" value="<?= $_SESSION['Pa_totalPay'] ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">Patient Status</label>
                                <select name="patientStatus" id="" class="form-control" required>
                                    <option value="">select status</option>
                                    <option value="died">Died</option>
                                    <option value="awful">Awful</option>
                                    <option value="good">Good</option>
                                    <option value="better">Better</option>
                                </select>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <input type="submit" name="dischargeAppointment" class="btn btn-primary" value="Discharge">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


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

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script>
        $(document).ready(function() {
            $(".medPrice").on('change', function() {
                var $el = $(this).closest("form");

                var medPricePa = $el.find('.medPrice').val();
                var doctorFeePa = $el.find('.doctorFee').val();
                var aid = $el.find('.aid').val();
                var pid = $el.find('.pid').val();

                location.reload(true);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    cache: false,
                    data: {
                        medPricePa: medPricePa,
                        doctorFeePa: doctorFeePa,
                        aid: aid,
                        pid: pid
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });

            });
        });
    </script>

</body>

</html>