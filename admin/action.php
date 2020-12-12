<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

// PATIENT FROM APPOINTMENT

if (isset($_POST['searchPatientAppointment'])) {

    $search = trim(htmlspecialchars($_POST['searchPatientAppointment']));
    $searchName = "%" . $search . "%";
    $status1 = "accepted";
    $status2 = "done";

    $sql = "SELECT * FROM appointment WHERE pName LIKE :searchName AND aStatus IN(:status1,:status2)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":searchName", $searchName, PDO::PARAM_STR);
    $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
    $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $appointmentOutput = '';
    // var_dump($numRows);
    // die();

    if ($stmt->rowCount() > 0) {



        $appointmentOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Appointment ID</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Mobile</th>
            <th scope="col">Patient Disease</th>
            <th scope="col">Doctor Name</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($appointmentPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $appointmentOutput .= '
        <tr>
            <th scope="row">' . $appointmentPatient['aId'] . '</th>
            <td>' . $appointmentPatient['pName'] . '</td>
            <td>' . $appointmentPatient['pAddress'] . '</td>
            <td>' . $appointmentPatient['pMobile'] . '</td>
            <td>' . $appointmentPatient['aReason'] . '</td>
            <td>' . $appointmentPatient['pDoctor'] . '</td>
            <td>
                <div class="row">
                    <div class="col">
                        <form action="updatePatientAppointment.php" method="post">
                            <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus">
                        </form>
                    </div>
                    <div class="col">
                        <form action="" method="post">
                            <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus">
                        </form>
                    </div>
                </div>
            </td>
        ';
        }
        $appointmentOutput .= '
    </tr>
    <tbody>
    ';

        echo $appointmentOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}

// UPDATE PASSWORD FROM PATIENT APPOINTMENT
