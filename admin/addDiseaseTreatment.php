<?php
ob_start();
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="icon" href="../img/sumc.png">
    <title>Admin | Diseases & Treatment</title>
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
                            <a class="nav-link" href="walkInPatient.php">
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
                            <a class="nav-link" href="diseaseTreatment.php" id="primaryColor">
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

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">Add Disease & Treatment</h1>
                </div>

                <form action="" method="post" class="form-group">
                    <div class="row">
                        <input type="hidden" name="dtId" value="<?= $dt['dtId']; ?>">
                        <div class="col">
                            <label for="dtName">Title</label>
                            <input type="text" name="dtName" id="dtName" class="form-control">
                        </div>
                        <div class="col">
                            <label for="dtMeaning">Meaning</label>
                            <textarea name="dtMeaning" id="dtMeaning" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="dtSymptoms">Symptoms</label>
                            <textarea name="dtSymptoms" id="dtSymptoms" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col">
                            <label for="dtPrevention">Prevention</label>
                            <textarea name="dtPrevention" id="dtPrevention" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                        <div class="col">
                            <label for="dtTreatment">Treatment</label>
                            <textarea name="dtTreatment" id="dtTreatment" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="text-center mt-3">
                            <input type="submit" value="Add" name="add" class="btn btn-secondary">
                        </div>
                </form>

                <?php

                if(isset($_POST['add'])){

                    $dtName = trim(htmlspecialchars($_POST['dtName']));
                    $dtMeaning = trim(htmlspecialchars($_POST['dtMeaning']));
                    $dtSymptoms = trim(htmlspecialchars($_POST['dtSymptoms']));
                    $dtPrevention = trim(htmlspecialchars($_POST['dtPrevention']));
                    $dtTreatment = trim(htmlspecialchars($_POST['dtTreatment']));

                    $sql = "INSERT INTO diseases_treatment (dtName,dtMeaning,dtSymptoms,dtPrevention,dtTreatment)VALUES(:name,:meaning,:symptoms,:prevention,:treatment)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":name",$dtName,PDO::PARAM_STR);
                    $stmt->bindParam(":meaning",$dtMeaning,PDO::PARAM_STR);
                    $stmt->bindParam(":symptoms",$dtSymptoms,PDO::PARAM_STR);
                    $stmt->bindParam(":prevention",$dtPrevention,PDO::PARAM_STR);
                    $stmt->bindParam(":treatment",$dtTreatment,PDO::PARAM_STR);
                    
                    if($stmt->execute()){
                        header("location:diseaseTreatment.php?addSucc=successfully_added!");
                        ob_end_flush();
                        exit;
                    }

                }

                ?>

        </div>


        </main>
    </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>