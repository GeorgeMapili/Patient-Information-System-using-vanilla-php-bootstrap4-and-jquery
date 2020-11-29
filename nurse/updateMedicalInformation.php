<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
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
    <title>Nurse | Medical Information</title>
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
                        <a class="nav-link" href="patient.php">Patient from appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patientWalkIn.php">Patient Walk in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room.php">Room</a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/nurse_profile_img/<?= $_SESSION['nProfileImg']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Nurse&nbsp;<?= $_SESSION['nName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['nEmail']; ?></a>
                            <a class="dropdown-item" href="nurseProfile.php">My account</a>
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

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Update Medical Information</h3>


            <?php

            if (isset($_GET['id'])) {
                $id = trim(htmlspecialchars($_GET['id']));

                $sql = "SELECT * FROM medicalinformation WHERE pId = :id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $updateMedicalInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                $arrayInfo = explode(",", $updateMedicalInfo['pMedicalInfo']);
                $arrsLen = count($arrayInfo);
            }

            ?>


            <form action="action.php" method="post">
                <?php $id = $_GET['id']; ?>
                <input type="hidden" name="id" value="<?= $id; ?>">

                <div class="row">
                    <div class="col m-1">
                        <label>Height</label>
                        <input type="number" name="height" value="<?= $updateMedicalInfo['pHeight'] ?>" min="0" class="form-control" required>
                    </div>
                    <div class="col m-1">
                        <label>Weight</label>
                        <input type="number" name="weight" min="0" value="<?= $updateMedicalInfo['pWeight'] ?>" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col m-1">
                        <label>Blood Type</label>
                        <select name="bloodType" class="form-control" required>
                            <option value="">select a blood type</option>
                            <?php
                            $sql = "SELECT * FROM bloodtype";
                            $stmt = $con->prepare($sql);
                            $stmt->execute();

                            while ($bloodType = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <option value="<?= $bloodType['bloodType'] ?>" <?= ($bloodType['bloodType'] == $updateMedicalInfo['pBloodType']) ? 'selected' : ''; ?>><?= $bloodType['bloodType'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col m-1">
                        <label>Allergy</label>
                        <input type="text" name="allergy" class="form-control" value="<?= $updateMedicalInfo['pAllergy'] ?>" required>
                    </div>
                </div>

                <h6 class=" mt-5 my-4" id="primaryColor">Have you ever the following ?</h6>
                <?php

                $sql = "SELECT * FROM ffmedicaldisease";
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $inc = 0;
                while ($md = $stmt->fetch(PDO::FETCH_ASSOC)) :

                ?>
                    <label for=""><?= ucwords($md['md_name']); ?></label>
                    <input type="checkbox" name="followingMed[]" value="<?= $md['md_name'] ?>" <?php
                                                                                                for ($i = 0; $i < $arrsLen; $i++) {
                                                                                                    if ($md['md_name'] == $arrayInfo[$i]) {
                                                                                                        echo 'checked';
                                                                                                    }
                                                                                                }
                                                                                                ?>><br>
                <?php endwhile; ?>
                <div class="text-center">
                    <input type="submit" value="Update Medical Information" name="updateMedInfo" class="btn btn-primary mt-3">
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


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>