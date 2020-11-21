<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <title>Main</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="appointment.php">Set an Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Health Library</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">My Appointments</a>
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
                            <a class="dropdown-item" href="#">My account</a>
                            <a class="dropdown-item" href="#">My Appointment History</a>
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
                <h1 class="Display-4">Set an Appointment</h1>
            </div>

            <form action="appointment.php" method="post">
                <div class="row">
                    <div class="col">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" value="Qwerty" readonly>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" value="qwerty@gmail.com" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="exampleInputEmail1">Address</label>
                        <input type="text" class="form-control" value="12345 St." readonly>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1">Mobile Number</label>
                        <input type="tel" class="form-control" value="09550192231" readonly>
                    </div>
                </div>
                <label for="">Choose a physician</label>
                <select class="form-control" name="selectDoctor">
                    <option value="">Select</option>
                    <option value="">Dr. Stone Arc -> Cardiologists</option>
                    <option value="">Dr. Stone Arc -> Dermatologists</option>
                    <option value="">Dr. Stone Arc</option>
                    <option value="">Dr. Stone Arc</option>
                    <option value="">Dr. Stone Arc</option>
                    <option value="">Dr. Stone Arc</option>
                    <option value="">Dr. Stone Arc</option>
                    <option value="">Dr. Stone Arc</option>
                </select>

                <label for="">Reason for Appointment</label>
                <textarea name="reasonAppointment" class="form-control resize-0" cols="30" rows="10"></textarea>
                <div class="text-center mt-3">
                    <input type="submit" class="btn btn-success" value="Submit" name="submitAppointment">
                </div>
            </form>
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
</body>

</html>