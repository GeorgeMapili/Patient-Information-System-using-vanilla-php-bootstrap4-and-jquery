<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['dId'])) {
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
    <title>Doctor | Incoming Appointment</title>
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
                        <a class="nav-link" href="walkInPatient.php">Walk in Patient</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="incomingAppointment.php">Upcoming Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="cancelledAppointment.php">Cancelled Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doneAppointment.php">Finished Appointment</a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/doc_profile_img/<?= $_SESSION['dProfileImg'] ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['dName'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['dEmail'] ?></a>
                            <a class="dropdown-item" href="doctorProfile.php">My account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <?php
    if (isset($_POST['update'])) {
        $aId = $_POST['aId'];
        $pId = $_POST['pId'];
        $updateDisease = trim(htmlspecialchars($_POST['disease']));

        if ($updateDisease == $_SESSION['updateAppointmentDisease']) {
            header("location:patient.php?errUpdateDisease=nothing_to_update");
            exit(0);
        }

        $sql = "UPDATE appointment SET aReason = :reason WHERE aId = :aid AND pId = :pid";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":reason", $updateDisease, PDO::PARAM_STR);
        $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
        $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
        $stmt->execute();

        header("location:patient.php?succUpdate=disease_updated_successfully");
        exit(0);
    }
    ?>

    <main role="main">

        <?php
        if (isset($_POST['updateDisease'])) {
            $aId = $_POST['aId'];
            $pId = $_POST['pId'];

            $sql = "SELECT * FROM appointment WHERE aId = :aid AND pId = :pid";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
            $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
            $stmt->execute();

            $updateDisease = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['updateAppointmentDisease'] = $updateDisease['aReason'];
        } else {
            header("location:dashboard.php");
            exit(0);
        }
        ?>

        <div class="container-fluid">

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">Update Disease</h1>
            </div>

            <form action="updateDisease.php" method="post">
                <input type="hidden" name="aId" value="<?= $updateDisease['aId'] ?>">
                <input type="hidden" name="pId" value="<?= $updateDisease['pId'] ?>">
                <input type="text" name="disease" value="<?= $updateDisease['aReason'] ?>" class="form-control" autocomplete="off">
                <div class="text-center mt-3">
                    <input type="submit" value="Update Disease" name="update" class="btn btn-secondary">
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