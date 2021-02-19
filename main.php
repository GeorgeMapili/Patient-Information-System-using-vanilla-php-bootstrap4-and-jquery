<?php
session_start();
require_once 'connect.php';


if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit(0);
}

$sql = "DELETE FROM loginlog WHERE patient_id = :id";
$stmt = $con->prepare($sql);
$stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <title>Patient | Home</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " id="primaryColor" href="main.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contactus.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="healthLibrary.php">Health Library</a>
                    </li>
                    <div class="btn-group dropbottom">
                        <a href="myappointment.php" class="nav-link">
                            Appointment
                        </a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="acceptedAppointment.php">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finishedAppointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <img src="upload/user_profile_img/<?= $_SESSION['profile']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="myaccount.php">My account</a>
                            <a class="dropdown-item" href="myAppointmentHistory.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="deleteAcc.php" onclick="return confirm('Are you sure?')">Delete Account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="first-slide" src="img/carousel1.jpg" style="opacity: 0.7;" alt="First slide">
                    <div class="container">
                        <div class="carousel-caption text-left" id="carouselText">
                            <h1>Professional Doctors.</h1>
                            <p>A Great Place to Work. A Great Place to Receive Care.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="second-slide" src="img/carousel2.jpeg" style="opacity: 0.7;" alt="Second slide">
                    <div class="container">
                        <div class="carousel-caption" id="carouselText">
                            <h1>Care You Can Trust.</h1>
                            <p>Passionate About Medicine. Compassionate About People.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="third-slide" src="img/carousel3.jpg" style="opacity: 0.7;" alt="Third slide">
                    <div class="container">
                        <div class="carousel-caption text-right" id="carouselText">
                            <h1>Caring For You And Your Family.</h1>
                            <p>A Wealth of Experience To Heal and Help You.</p>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>


        <!-- Marketing messaging and featurettes
      ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->

        <div class="container marketing">

            <!-- START THE FEATURETTES -->

            <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading" id="primaryColor">Pleasant atmosphere.</h2>
                    <p class="lead">These beds have special features both for the comfort and well-being of the patient and for the convenience of health care workers.</p>
                </div>
                <div class="col-md-5">
                    <img class="featurette-image img-fluid mx-auto shadow-lg p-3 mb-5 bg-white rounded" src="img/clinic.jpg" alt="Generic placeholder image">
                </div>
            </div>

            <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7 order-md-2">
                    <h2 class="featurette-heading" id="primaryColor">Top of the line equipments.</h2>
                    <p class="lead">With the latest technology and medical equipment in patient care that helps the doctors diagnose diseases and treat patients effectively and efficiently.</p>
                </div>
                <div class="col-md-5 order-md-1">
                    <img class="featurette-image img-fluid mx-auto shadow-lg p-3 mb-5 bg-white rounded" src="img/feature2.jpeg" alt="Generic placeholder image">
                </div>
            </div>

            <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading" id="primaryColor">Skilled Doctors.</h2>
                    <p class="lead">Doctors take an oath to care for people to the best of their ability as their profession.</p>
                </div>
                <div class="col-md-5">
                    <img class="featurette-image img-fluid mx-auto shadow-lg p-3 mb-5 bg-white rounded" src="img/feature3.jpeg" alt="Generic placeholder image">
                </div>
            </div>

            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

        </div><!-- /.container -->


        <!-- FOOTER -->
        <footer class="container">
            <p class="float-right"><a href="#">Back to top</a></p>
            <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>