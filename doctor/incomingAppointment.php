<?php
session_start();
require_once '../connect.php';
require __DIR__ . '/../vendor/autoload.php';

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
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">SUMC Doctors Clinic</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php">Patient Appointment</a>
                    </li>
                    <li class="nav-item active">
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

    <main role="main">

        <div class="container-fluid">

            <?php
            if (isset($_POST['doneAppointment'])) {

                $options = array(
                    'cluster' => 'ap1',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    '33e38cfddf441ae84e2d',
                    '9d6c92710887d31d41b4',
                    '1149333',
                    $options
                );

                $aId = $_POST['aId'];
                $pId = $_POST['pId'];

                $status = "done";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();
                $data['message'] = 'hello world';
                $pusher->trigger('my-channel', 'my-event', $data);
                header("location:incomingAppointment.php?succDone=Successfully_done_appointment");
                exit(0);
            }

            if (isset($_POST['cancelAppointment'])) {
                $aId = $_POST['aId'];
                $pId = $_POST['pId'];
                $status = "cancelled";

                $sql = "UPDATE appointment SET aStatus = :status WHERE aId = :aid AND pId = :pid";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
                $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
                $stmt->execute();
                header("location:incomingAppointment.php?succCanc=Successfully_cancelled_appointment");
                exit(0);
            }
            ?>

            <div class="mt-4 mb-4">
                <h1 class="Display-4 my-4" id="primaryColor">My Incoming Appointment</h1>
            </div>


            <div class="text-center">
                <?= (isset($_GET['succCanc']) && $_GET['succCanc'] == "Successfully_cancelled_appointment") ? '<span class="text-success">Successfully Cancelled Appointment!</span>' : ''; ?>
            </div>
            <div class="text-center">
                <?= (isset($_GET['succDone']) && $_GET['succDone'] == "Successfully_done_appointment") ? '<span class="text-success">Successfully Done Appointment!</span>' : ''; ?>
            </div>
            <!-- Sort -->
            <div class="form-group">
                <h5>Sort By:</h5>
                <select name="sortBy" class="sortBy">
                    <option value="default">default</option>
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="this_week">This week</option>
                    <option value="next_week">Next week</option>
                    <option value="this_month">This month</option>
                </select>
            </div>
            <table class="table table-hover" id="table-data">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Patient Address</th>
                        <th scope="col">Patient Mobile</th>
                        <th scope="col">Appointment Reason</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $status1 = "accepted";
                    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status1 ORDER BY aDate,aTime ASC";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
                    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($upcomingAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <tr>
                            <td><?= $upcomingAppointment['pName'] ?></td>
                            <td><?= $upcomingAppointment['pAddress'] ?></td>
                            <td><?= $upcomingAppointment['pMobile'] ?></td>
                            <td><?= $upcomingAppointment['aReason'] ?></td>
                            <td><?= date("M d, Y", strtotime($upcomingAppointment['aDate'])); ?> at <?= $upcomingAppointment['aTime']; ?></td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <?php
                                        if (date("M d, Y") === date("M d, Y", strtotime($upcomingAppointment['aDate']))) {
                                        ?>
                                            <form action="incomingAppointment.php" method="post">
                                                <input type="hidden" name="aId" value="<?= $upcomingAppointment['aId'] ?>">
                                                <input type="hidden" name="pId" value="<?= $upcomingAppointment['pId'] ?>">
                                                <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                            </form>
                                        <?php
                                        } else {
                                        ?>
                                            <p class="btn btn-success disabled">Done</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col">
                                        <form action="incomingAppointment.php" method="post">
                                            <input type="hidden" name="aId" value="<?= $upcomingAppointment['aId'] ?>">
                                            <input type="hidden" name="pId" value="<?= $upcomingAppointment['pId'] ?>">
                                            <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm('Are you sure to delete ?')">
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php">Privacy Policy</a> &middot; <a href="aboutUs.php">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sortBy').change(function() {
                // Get the sorted Value
                var sortBy = $('.sortBy').val();
                // console.log(sortBy);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        sortBy: sortBy
                    },
                    success: function(response) {
                        $('#table-data').html(response);
                    }
                });
            });
        });
    </script>

</body>

</html>