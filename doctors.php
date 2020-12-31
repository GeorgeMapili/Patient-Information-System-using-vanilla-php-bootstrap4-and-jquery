<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['id'])) {
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
    <link rel="stylesheet" href="css/main.css" />
    <title>Patient | Doctors</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="main.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contactus.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="healthLibrary.php">Health Library</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="myappointment.php">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="acceptedAppointment.php">Accepted Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="finishedAppointment.php">Finished Appointments</a>
                    </li>
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

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Our Doctors</h1>
            </div>

            <div class="row">
                <?php
                $sql = "SELECT * FROM doctor";
                $stmt = $con->prepare($sql);
                $stmt->execute();

                while ($doctors = $stmt->fetch(PDO::FETCH_ASSOC)) :
                ?>
                    <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                        <img class="card-img-top" src="upload/doc_profile_img/<?= $doctors['dProfileImg']; ?>" alt="Card image cap">
                        <div class="card-body ">
                            <h5 class="card-title"><?= $doctors['dName']; ?></h5>
                            <p class="card-text lead"><?= $doctors['dSpecialization']; ?></p>
                            <p class="card-text"><?= $doctors['dSpecializationInfo']; ?></p>
                            <a href="appointment.php?docId=<?= $doctors['dId']; ?>" class="btn " id="docBtnApt">Set an Appointment</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc2.jpg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>

                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc3.jpg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>

                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc4.jpeg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>


                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc5.jpeg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>

                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc6.jpg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>

                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc7.jpg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div>

                <div class="card col-lg-3 col-md-4 col-sm-6 my-3 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="upload/doc_profile_img/doc8.jpg" alt="Card image cap">
                    <div class="card-body ">
                        <h5 class="card-title">Qwerty</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn " id="docBtnApt">Set an Appointment</a>
                    </div>
                </div> -->



            <hr class="featurette-divider">



            <!-- FOOTER -->
            <footer class="container text-center">
                <p>&copy; <?= date("Y") ?> Company, Inc. &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
            </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>