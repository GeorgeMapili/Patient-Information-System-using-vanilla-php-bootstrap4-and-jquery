<?php
ob_start();
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
    <title>Patient | Delete Account</title>
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
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['email'] ?></a>
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
                <h1 class="Display-4" id="primaryColor">Delete an account</h1>
            </div>

            <div class="mt-4 mb-4 text-center">
                <h5>Please verfiy with password</h5>
            </div>

            <?php

            if (isset($_POST['deleteAcc'])) {

                $password = trim(htmlspecialchars($_POST['password']));

                $sql = "SELECT * FROM patientappointment WHERE pId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();

                while ($acc = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (password_verify($password, $acc['pPassword'])) {
                        $sql = "DELETE FROM patientappointment WHERE pId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->execute();
                        // Delete the user profile img
                        $path = __DIR__ . "/upload/user_profile_img/" . $_SESSION['profile'];
                        unlink($path);

                        // Delete all existed in appointment
                        $sql = "DELETE FROM appointment WHERE pId = :id";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->execute();

                        unset($_SESSION['id']);
                        unset($_SESSION['name']);
                        unset($_SESSION['email']);
                        unset($_SESSION['address']);
                        unset($_SESSION['age']);
                        unset($_SESSION['gender']);
                        unset($_SESSION['mobile']);
                        unset($_SESSION['profile']);



                        header("location:index.php");
                        exit(0);
                    } else {
                        header("location:deleteAcc.php?errPass=Incorrect_password");
                        ob_end_flush();
                        exit(0);
                    }
                }
            }


            ?>
            <form action="deleteAcc.php" method="post">

                <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">
                <div class="col">
                    <label>Password</label>
                    <?= (isset($_GET['errPass']) && $_GET['errPass'] == "Incorrect_password") ? '<input type="password" name="password" class="form-control is-invalid">' : '<input type="password" name="password" class="form-control">'; ?>
                    <?= (isset($_GET['errPass']) && $_GET['errPass'] == "Incorrect_password") ? '<span class="text-danger">Incorrect Password</span>' : ''; ?>
                </div>

                <div class="text-center mt-5">
                    <input type="submit" value="Delete Account" name="deleteAcc" class="btn btn-danger">
                </div>
        </div>

        </form>
        </div>

        <!-- FOOTER -->
        <!-- <footer class="container">
            <p class="float-right"><a href="#">Back to top</a></p>
            <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
        </footer> -->


    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>