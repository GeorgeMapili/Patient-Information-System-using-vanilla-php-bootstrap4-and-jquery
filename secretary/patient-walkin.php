<?php
ob_start();
session_start();
require_once '../connect.php';
require __DIR__ . '/../vendor/autoload.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_secretary_walkin_patient'] = true;

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
    <title>Secretary | Walk In Patient</title>
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
        <div class="container-fluid">

            <?php

            if (isset($_POST['deleteWalkInPatientBtn'])) {

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

                $walkInId = $_POST['walkInId'];

                // Delete the walkin patient
                $sql = "DELETE FROM walkinpatient WHERE walkInId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $walkInId, PDO::PARAM_INT);
                $stmt->execute();

                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:patient-walkin.php?succDeleteWalkInPatient=successfully_deleted_walk_in_patient");
                $_SESSION['log_secretary_delete_walkin_patient'] = true;
                ob_end_flush();
                exit(0);
            }
            ?>

            <h3 class="display-4 mt-5 my-4" id="primaryColor">All Walk in Patients</h3>

            <?php
            $limit = 5;
            $stmt = $con->prepare("SELECT * FROM walkinpatient");
            $stmt->execute();
            $countPatientAppointment = $stmt->rowCount();
            $pages = ceil($countPatientAppointment / $limit);

            $firstPageValue = $pages;

            if (ceil($countPatientAppointment / $limit) == 0) {
                $pages = 1;
            }

            // $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $page = $_GET['page'] ?? 1;
            $page = htmlspecialchars($page);
            ?>

            <?php
            if ($page <= 0) {
            ?>
                <div class="text-center">
                    <h3 class="lead text-danger">PAGE NOT FOUND!</h3>
                </div>
            <?php
                exit(0);
            } elseif ($page > $pages) {
            ?>
                <div class="text-center">
                    <h3 class="lead text-danger">PAGE NOT FOUND!</h3>
                </div>
            <?php
                exit(0);
            } elseif(!ctype_digit($page)){
            ?>
                <div class="text-center">
                    <h3 class="lead text-danger">PAGE NOT FOUND!</h3>
                </div>
            <?php         
            exit;   
            }
            ?>
            <div class="text-center">
                <?= (isset($_GET['succAdd']) && $_GET['succAdd'] == "Successfully_added_medical_information") ? '<span class="text-success">Successfully added medical information!</span>' : ''; ?>
                <?= (isset($_GET['errUp']) && $_GET['errUp'] == "Nothing_to_update") ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['succUp']) && $_GET['succUp'] == "Updated_successfully") ? '<span class="text-success">Successfully updated!</span>' : ''; ?>
                <?= (isset($_GET['succDeleteWalkInPatient']) && $_GET['succDeleteWalkInPatient'] == "successfully_deleted_walk_in_patient") ? '<span class="text-success">Successfully deleted walk in patient!</span>' : ''; ?>
            </div>

            <?php
            $prev = $page - 1;
            $next = $page + 1;
            $discharged = 0;
            $start = ($page - 1) * $limit;
            $sql = "SELECT * FROM walkinpatient WHERE walkInDischarged = :discharged LIMIT :start, :limit ";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":discharged", $discharged, PDO::PARAM_INT);
            $stmt->bindParam(":start", $start, PDO::PARAM_INT);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() > 0){
            ?>
                <div class="row">
                    <div class="col">
                        <form class="form-inline">
                            <input class="form-control mb-3" id="search" autocomplete="off" type="search" placeholder="Search Patient" aria-label="Search">
                        </form>
                    </div>
                </div>

                <div class="table-responsive-xl">
                    <div id="prescription">
                        <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded" id="table-data">
                            <thead class="bg-info text-light">
                                <tr>
                                    <th scope="col">Patient Name</th>
                                    <th scope="col">Patient Address</th>
                                    <th scope="col">Patient Mobile</th>
                                    <th scope="col">Patient Disease</th>
                                    <th scope="col">Patient Doctor</th>
                                    <th scope="col">Doctor Prescription</th>
                                    <th scope="col">Add</th>
                                    <th scope="col">Generate</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>




                                <?php

                                while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                    $sql1 = "SELECT * FROM medicalinformation WHERE pId = :id";
                                    $stmt1 = $con->prepare($sql1);
                                    $stmt1->bindParam(":id", $walkInPatient['walkInId'], PDO::PARAM_INT);
                                    $stmt1->execute();

                                    $medInfoExst = $stmt1->fetch(PDO::FETCH_ASSOC);
                                ?>
                                    <tr>
                                        <td><?= $walkInPatient['walkInName']; ?></td>
                                        <td><?= $walkInPatient['walkInAddress']; ?></td>
                                        <td><?= $walkInPatient['walkInMobile'] ?></td>
                                        <td><?= $walkInPatient['walkInDisease']; ?></td>
                                        <td><?= $walkInPatient['walkInDoctor']; ?></td>
                                        <td><?= $walkInPatient['walkInPrescription']; ?></td>
                                        <td>
                                            <?php
                                            $medInfoExst = $medInfoExst['pId'] ?? 0;
                                            ?>

                                            <?php
                                            if ($medInfoExst == $walkInPatient['walkInId']) {
                                            ?>
                                                <form action="update-information.php" method="get">
                                                    <input type="hidden" name="id" value="<?= $walkInPatient['walkInId']; ?>">
                                                    <input type="submit" value="UPDATE MEDICAL INFORMATION" class="btn btn-secondary" name="medicalInformation">
                                                </form>
                                            <?php
                                            } else {
                                            ?>
                                                <form action="medical-information.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $walkInPatient['walkInId']; ?>">
                                                    <input type="submit" value="ADD MEDICAL INFORMATION" class="btn btn-info" name="medicalInformation">
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (empty($walkInPatient['walkInPrescription'])) {
                                            ?>
                                                <p class="btn btn-primary disabled" title="Can't generate bill without prescription">GENERATE BILL</p>
                                            <?php
                                            } else {
                                            ?>
                                                <form action="bill-walkin.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $walkInPatient['walkInId']; ?>">
                                                    <input type="submit" value="GENERATE BILL" class="btn btn-primary" name="generateBill">
                                                </form>
                                            <?php
                                            }
                                            ?>

                                        </td>
                                        <td>
                                            <form action="patient-walkin.php" method="post">
                                                <input type="hidden" name="walkInId" value="<?= $walkInPatient['walkInId']; ?>">
                                                <input type="submit" name="deleteWalkInPatientBtn" class="btn btn-danger" value="DELETE" onclick="return confirm('Are you sure to delete ?')">
                                            </form>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3 <?= ($firstPageValue == 0) ? 'd-none' : '' ?>">
                    <nav aria-label="Page navigation example ">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($prev <= 0) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="patient-walkin.php?page=<?= $prev; ?>" tabindex="-1">Previous</a>
                            </li>
                            <?php $pageWalkIn = isset($_GET['page']) ? $_GET['page'] : 1; ?>
                            <?php for ($i = 1; $i <= $pages; $i++) : ?>
                                <li class="page-item <?= ($i == $pageWalkIn) ? 'active' : ''; ?>"><a class="page-link" href="patient-walkin.php?page=<?= $i; ?>"><?= $i; ?></a></li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($next > $pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="patient-walkin.php?page=<?= $next; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>

            <?php
            }else{
            ?>
                <p class="lead text-center text-white display-4">No walk in patient yet</p>
            <?php
            }
            ?>

            <h3 class=" mt-5 my-4" id="primaryColor">Add Patients</h3>

            <div>
                <a href="add-patient.php" class="btn btn-success mb-3 margin-right-auto">New Patient</a>
            </div>

            <div>
                <a href="previous-patient.php" class="btn btn-success mb-3 margin-right-auto">Patient Before</a>
            </div>



            <hr class="featurette-divider">

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

            console.log(data['message']);
            $.ajax({
                url: "updateDoctorPrescription.php",
                data: {
                    id: data['message']
                },
                method: "post",
                success: function(result) {
                    console.log(result);
                    $("#prescription").html(result);
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var search = $(this).val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        walkInQuery: search
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