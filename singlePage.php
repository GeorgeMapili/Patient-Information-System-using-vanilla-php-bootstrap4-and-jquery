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
    <title>Patient | Diseases and Treatment</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set an Appointment</a>
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
                    <li class="nav-item">
                        <a class="nav-link " href="myappointment.php">My Appointments</a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="upload/user_profile_img/q.jpg" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Qwerty Asdf
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href="">qwerty@gmail.com</a>
                            <a class="dropdown-item" href="myaccount.php">My account</a>
                            <a class="dropdown-item" href="myAppointmentHistory.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">



        <div class="container marketing">

            <div class="mt-4 mb-4">
                <h1 class="display-4" id="primaryColor">Overview</h1>
            </div>

            <hr class="featurette-divider">
            <div>
                <h2 class="display-3 my-3">What is Fever ?</h2>
            </div>

            <div class="text-left">
                <p>A fever is a temporary increase in your body temperature, often due to an illness. Having a fever is a sign that something out of the ordinary is going on in your body.</p>

                <p> For an adult, a fever may be uncomfortable, but usually isn't a cause for concern unless it reaches 103 F (39.4 C) or higher. For infants and toddlers, a slightly elevated temperature may indicate a serious infection.</p>

                <p> Fevers generally go away within a few days. A number of over-the-counter medications lower a fever, but sometimes it's better left untreated. Fever seems to play a key role in helping your body fight off a number of infections.</p>
            </div>


            <hr class="featurette-divider">

            <div>
                <h2 class="display-4 my-3">Symptoms</h2>
            </div>

            <div class="text-left">
                <ul>
                    <li>Sweating</li>
                    <li>Chills and shivering</li>
                    <li>Headache</li>
                    <li>Muscle aches</li>
                    <li>Loss of appetite</li>
                    <li>Irritability</li>
                    <li>Dehydration</li>
                    <li>General weakness</li>
                </ul>
            </div>


            <hr class="featurette-divider">

            <div>
                <h2 class="display-4 my-3">Prevention</h2>
            </div>

            <div class="text-left">
                <ul>
                    <li>Wash your hands often and teach your children to do the same, especially before eating, after using the toilet, after spending time in a crowd or around someone who's sick, after petting animals, and during travel on public transportation.</li>
                    <li>Show your children how to wash their hands thoroughly, covering both the front and back of each hand with soap and rinsing completely under running water.</li>
                    <li>Carry hand sanitizer with you for times when you don't have access to soap and water.</li>
                    <li>Try to avoid touching your nose, mouth or eyes, as these are the main ways that viruses and bacteria can enter your body and cause infection.</li>
                    <li>Cover your mouth when you cough and your nose when you sneeze, and teach your children to do likewise. Whenever possible, turn away from others when coughing or sneezing to avoid passing germs along to them.</li>
                    <li>Avoid sharing cups, water bottles and utensils with your child or children.</li>
                </ul>
            </div>

            <hr class="featurette-divider">

            <div>
                <h2 class="display-4 my-3">Treatment</h2>
            </div>

            <div class="text-left">
                <p>In the case of a high fever, or a low fever that's causing discomfort, your doctor may recommend an over-the-counter medication, such as acetaminophen (Tylenol, others) or ibuprofen (Advil, Motrin IB, others).</p>
                <p>Use these medications according to the label instructions or as recommended by your doctor. Be careful to avoid taking too much. High doses or long-term use of acetaminophen or ibuprofen may cause liver or kidney damage, and acute overdoses can be fatal. If your child's fever remains high after a dose, don't give more medication; call your doctor instead.</p>
                <p>Don't give aspirin to children, because it may trigger a rare, but potentially fatal, disorder known as Reye's syndrome.</p>

                <p>Depending on the cause of your fever, your doctor may prescribe an antibiotic, especially if he or she suspects a bacterial infection, such as pneumonia or strep throat.</p>
            </div>

            <hr class="featurette-divider">

        </div>


        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>