<?php
ob_start();
session_start();

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_contact'] = true;

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Contact Us</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
            <a class="navbar-brand " i id="primaryColor" href="home.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link " href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="library.php">Health Library</a>
                    </li>
                    <div class="btn-group dropbottom">
                        <a href="current-appointment.php" class="nav-link">
                            Appointment
                        </a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="accepted-appointment.php">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finished-appointment.php">Finished</a>
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
                            <a class="dropdown-item" href="account.php">My account</a>
                            <a class="dropdown-item" href="appointment-history.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="delete.php" onclick="return confirm('Are you sure?')">Delete Account</a>
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

        <div class="container">

            <?php if (isset($_GET['ContactSuccess']) && $_GET['ContactSuccess'] == "Successully_send_a_message") : ?>
                <div class="alert alert-success alert-dismissible my-4">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Successfully send a message!</strong>
                </div>
            <?php endif; ?>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Contact Us</h1>
            </div>

            <?php
            use core\patient\Contact;
            require_once("vendor/autoload.php");

            $contact =new Contact();

            if (isset($_POST['submitMessage'])) {
                
                $contact->id = $_POST['id'];
                $contact->name = $_POST['name'];
                $contact->email = $_POST['email'];
                $contact->address = $_POST['address'];
                $contact->mobileNumber = $_POST['mobileNumber'];
                $contact->message = trim(htmlspecialchars($_POST['message']));

                if($contact->insertMessage() == "success_message"){

                    header("location:contact.php?ContactSuccess=Successully_send_a_message");
                    $_SESSION['log_send_contact'] = true;
                    ob_end_flush();
                    exit(0);

                }

            }
            ?>

            <form action="contact.php" method="post" class="shadow p-3 mb-5 rounded bg-light text-dark">
                <div class="row">
                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">
                    <div class="col">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $_SESSION['name']; ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $_SESSION['email']; ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $_SESSION['address']; ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobileNumber" class="form-control" value="<?= $_SESSION['mobile']; ?>" readonly>
                    </div>
                </div>

                <label for="">Message or Report an Issue</label>
                <textarea name="message" class="form-control resize-0" cols="30" rows="10" required></textarea>
                <div class="text-center mt-3">
                    <input type="submit" class="btn btn-info mt-5" value="Submit" name="submitMessage">
                </div>
            </form>
        </div>


        <hr class="container">



        <!-- FOOTER -->
        <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacy-policy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="about.php" id="primaryColor">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>