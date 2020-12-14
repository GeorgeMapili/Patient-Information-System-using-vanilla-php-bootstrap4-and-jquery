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
                <div class="col">
                    <form action="patient.php" method="post">
                        <input type="hidden" name="aId" value=' . $appointmentPatient['aId'] . '>
                        <input type="hidden" name="pId" value=' . $appointmentPatient['pId'] . '>
                        <input type="submit" value="Delete" class="btn btn-danger" name="deleteAppointment" onclick="return confirm(\'Are you sure to delete ?\');">
                    </form>
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

//  Search Patient User

if (isset($_POST['searchPatientUser'])) {

    $search = trim(htmlspecialchars($_POST['searchPatientUser']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM patientappointment WHERE pName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $userOutput = '';

    if ($numRows > 0) {



        $userOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Patient ID</th>
            <th scope="col">Patient Profile</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Email</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Mobile</th>
            <th scope="col">Patient Age</th>
            <th scope="col">Patient Gender</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($userPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userOutput .= '
        <tr>
            <th scope="row">' . $userPatient['pId'] . '</th>
            <td><img src="../upload/user_profile_img/' . $userPatient['pProfile'] . '" width="50" height="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
            <td>' . $userPatient['pName'] . '</td>
            <td>' . $userPatient['pEmail'] . '</td>
            <td>' . $userPatient['pAddress'] . '</td>
            <td>' . $userPatient['pMobile'] . '</td>
            <td>' . $userPatient['pAge'] . '</td>
            <td>' . ucwords($userPatient['pGender']) . '</td>
            <td>
                <div class="row">
                    <div class="col">
                        <form action="updatePatient.php" method="post">
                            <input type="hidden" name="pId" value="' . $userPatient['pId'] . '">
                            <input type="submit" value="Update" class="btn btn-secondary" name="updatePatient">
                        </form>
                    </div>
                    <div class="col">
                        <form action="patientUser.php" method="post">
                            <input type="hidden" name="pId" value="' . $userPatient['pId'] . '">
                            <input type="hidden" name="pProfile" value="' . $userPatient['pProfile'] . '">
                            <input type="submit" value="Delete" class="btn btn-danger" name="deletePatient" onclick="return confirm(\'Are you sure to delete ?\');">
                        </form>
                    </div>
                </div>
            </td>
        ';
        }
        $userOutput .= '
    </tr>
    <tbody>
    ';

        echo $userOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}


// DOCTOR AJAX

if (isset($_POST['searchDoctor'])) {

    $search = trim(htmlspecialchars($_POST['searchDoctor']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM doctor WHERE dName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $doctorOutput = '';

    if ($stmt->rowCount() > 0) {



        $doctorOutput .= '
    <thead class="thead-dark">
        <tr>
        <th scope="col">Doctor ID</th>
        <th scope="col">Doctor Profile Img</th>
        <th scope="col">Doctor Name</th>
        <th scope="col">Doctor Email</th>
        <th scope="col">Doctor Address</th>
        <th scope="col">Doctor Mobile</th>
        <th scope="col">Doctor Specialization</th>
        <th scope="col">Doctor Fee</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    ';

        while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $doctorOutput .= '
        <tr>
            <th scope="row">' . $doctor['dId'] . '</th>
            <td><img src="../upload/doc_profile_img/' . $doctor['dProfileImg'] . '" width="50" height="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
            <td>' . $doctor['dName'] . '</td>
            <td>' . $doctor['dEmail'] . '</td>
            <td>' . $doctor['dAddress'] . '</td>
            <td>' . $doctor['dMobile'] . '</td>
            <td>' . $doctor['dSpecialization'] . '</td>
            <td>' . $doctor['dFee'] . '</td>
            <td>
                 <div class="row">
                            <div class="col">
                                <form action="updateDoctor.php" method="post">
                                    <input type="hidden" name="dId" value=' . $doctor['dId'] . '>
                                    <input type="submit" value="Update" class="btn btn-secondary" name="updateDoctorBtn">
                                </form>
                            </div>
                            <div class="col">
                                <form action="doctor.php" method="post">
                                    <input type="hidden" name="dId" value=' . $doctor['dId'] . '>
                                    <input type="submit" value="Delete" class="btn btn-danger" name="deleteDoctorBtn" onclick="return confirm(\'Are you sure to delete ?\');">
                                </form>
                            </div>
                    </div>
            </td>
        ';
        }
        $doctorOutput .= '
    </tr>
    <tbody>
    ';

        echo $doctorOutput;
    } else {
        echo '<h3 style="color:red">No Doctor Found</h3>';
    }
}
