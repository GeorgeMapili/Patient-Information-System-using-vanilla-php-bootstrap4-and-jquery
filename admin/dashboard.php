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
                            <a class="nav-link " href="dashboard.php" id="primaryColor">
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
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="primaryColor">Dashboard</h1>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">All Patients</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">All Doctors</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">All Rooms</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">All Nurse Receptionist</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Done Appointment</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Cancelled Appointment</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Current Patient</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-lg-3 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Discharged Patients</h5>
                                    <h1 class="display-5 mt-1 mb-3">1</h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 my-3 border-bottom">
                    <h1 class="h2" id="primaryColor">Statistics</h1>
                </div>

                <div class="row my-3">

                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <canvas id="mycanvas"></canvas>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <canvas id="mycanvasgender"></canvas>
                    </div>
                </div>

            </main>
        </div>
    </div>




    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Age stats
            $.ajax({
                url: "stats.php",
                method: "GET",
                success: function(datas) {
                    var tenBelow = datas[29]
                    var elevToTwen = datas[42]
                    var twentyOneToFourty = datas[55]
                    var fourtyOneUp = datas[68]

                    var chartdata = {
                        labels: [
                            "10 below age",
                            "11 to 21 age",
                            "21 to 40 age",
                            "40 up age"
                        ],
                        datasets: [{
                            label: 'Number of Patient',
                            backgroundColor: 'rgb(77, 246, 144)',
                            borderColor: 'rgba(200,200,200, 6)',
                            hoverBackgroundColor: 'rgba(200,200,200, 1)',
                            hoverBorderColor: 'rgba(200,200,200, 1)',
                            data: [
                                tenBelow,
                                elevToTwen,
                                twentyOneToFourty,
                                fourtyOneUp
                            ]
                        }]
                    };

                    var ctx = $("#mycanvas");

                    var barGraph = new Chart(ctx, {
                        type: 'line',
                        data: chartdata,
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        precision: 0
                                    }
                                }]
                            }
                        }
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });

            // Gender Stats
            document.getElementById("mycanvasgender").getContext("2d");
            $.ajax({
                url: "statsGender.php",
                method: "GET",
                success: function(datas) {
                    var male = datas[29]
                    var female = datas[42]

                    var chartdata = {
                        labels: [
                            "Male",
                            "Female"
                        ],
                        datasets: [{
                            label: 'Gender of Patient',
                            backgroundColor: [
                                'rgb(95,168,240)',
                                'rgb(250, 153, 231)'
                            ],
                            borderColor: 'rgba(200,200,200, 6)',
                            hoverBackgroundColor: 'rgba(200,200,200, 1)',
                            hoverBorderColor: 'rgba(200,200,200, 1)',
                            data: [
                                male,
                                female
                            ]
                        }]
                    };

                    var ctx = $("#mycanvasgender");

                    var barGraph = new Chart(ctx, {
                        type: 'pie',
                        data: chartdata
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    </script>

</body>

</html>