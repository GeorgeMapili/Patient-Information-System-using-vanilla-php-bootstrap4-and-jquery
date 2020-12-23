<?php
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
    <title>Admin | Dashboard</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">Company name</a>
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
                            <a class="nav-link " href="patient.php" id="primaryColor">
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
                            <a class="nav-link" href="room.php">
                                <span data-feather="users"></span>
                                View All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php">
                                <span data-feather="users"></span>
                                View All Nurse Receptionist
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
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="container">

                    <h3 class="display-4 mt-5 my-4" id="primaryColor">Update Medical Information</h3>

                    <form action="viewMedicalInformation.php" method="post">
                        <div class="row">
                            <div class="col m-1">
                                <label>Height</label>
                                <input type="number" min="0" class="form-control">
                            </div>
                            <div class="col m-1">
                                <label>Weight</label>
                                <input type="number" min="0" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col m-1">
                                <label>Blood Type</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col m-1">
                                <label>Allergy</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>

                        <h6 class=" mt-5 my-4" id="primaryColor">Have you ever the following ?</h6>

                        <label for="">Diabetes</label>
                        <input type="checkbox" name="followingMed" value="diabetes"><br>
                        <label for="">Hypertension</label>
                        <input type="checkbox" name="followingMed" value="hypertension"><br>
                        <label for="">Cancer</label>
                        <input type="checkbox" name="followingMed" value="cancer"><br>
                        <label for="">Stroke</label>
                        <input type="checkbox" name="followingMed" value="stroke"><br>
                        <label for="">Heart Trouble</label>
                        <input type="checkbox" name="followingMed" value="heartTrouble"><br>
                        <label for="">Arthritis</label>
                        <input type="checkbox" name="followingMed" value="arthritis"><br>
                        <label for="">Convulsion</label>
                        <input type="checkbox" name="followingMed" value="convulsion"><br>
                        <label for="">Bleeding</label>
                        <input type="checkbox" name="followingMed" value="bleeding"><br>
                        <label for="">Acute Infections</label>
                        <input type="checkbox" name="followingMed" value="acuteInfections"><br>
                        <label for="">Venereal Disease</label>
                        <input type="checkbox" name="followingMed" value="venereal"><br>
                        <label for="">Hereditary Defects</label>
                        <input type="checkbox" name="followingMed" value="hereditary"><br>

                        <div class="text-center">
                            <input type="submit" value="Update Medical Information" class="btn btn-info mt-3">
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
        </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>