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

        <div class="container-fluid">
            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Patient record</h1>
            </div>

            <table class="table table-hover" id="table-data">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Patient Address</th>
                        <th scope="col">Patient Email</th>
                        <th scope="col">Patient Mobile</th>
                        <th scope="col">Room #</th>
                        <th scope="col">Patient Doctor</th>
                        <th scope="col">Doctor Prescription</th>
                        <th scope="col">Patient Disease</th>
                        <th scope="col">Patient Status</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Patient Amount Pay</th>
                        <th scope="col">Patient Change</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (isset($_GET['pName'])) {
                        $pName = $_GET['pName'];

                        $sql = "SELECT * FROM returnee_patient WHERE pName = :name";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":name", $pName, PDO::PARAM_STR);
                        $stmt->execute();

                        while ($returneePatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <tr>
                                <td><?= $returneePatient['pName'] ?></td>
                                <td><?= $returneePatient['pAddress'] ?></td>
                                <td><?= $returneePatient['pEmail'] ?></td>
                                <td><?= $returneePatient['pMobile'] ?></td>
                                <td><?= $returneePatient['pRoomNumber'] ?></td>
                                <td><?= $returneePatient['pDoctor'] ?></td>
                                <td><?= $returneePatient['pPrescription'] ?></td>
                                <td><?= $returneePatient['pDisease'] ?></td>
                                <td><?= $returneePatient['pStatus'] ?></td>
                                <td><?= $returneePatient['pTotalAmount'] ?></td>
                                <td><?= $returneePatient['pAmountPay'] ?></td>
                                <td><?= $returneePatient['pChange'] ?></td>
                                <td><?= date("M d, Y", strtotime($returneePatient['rpMadeOn'])) ?></td>
                            </tr>
                    <?php
                        }
                    } ?>

                </tbody>
            </table>

            <div>
                <a href="addNewRecord.php?pName=<?= $pName ?>" class="btn btn-success mb-3 margin-right-auto">Add New Record</a>
            </div>

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
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                // Get input value on change
                var patientBefore = $(this).val();
                var resultDropdown = $(this).siblings("#data");

                if (patientBefore.length) {
                    $.get("action1.php", {
                        patientBefore: patientBefore
                    }).done(function(data) {
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else {
                    resultDropdown.empty();
                }
            });
        });
    </script>
</body>

</html>