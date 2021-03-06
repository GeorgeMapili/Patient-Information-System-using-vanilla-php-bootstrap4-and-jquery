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
            <th scope="col">Patient Birthday</th>
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
                        <form action="updatePatient.php" method="get">
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
            <td>₱' . number_format($doctor['dFee'], 2) . '</td>
            <td>
                 <div class="row">
                            <div class="col">
                                <form action="updateDoctor.php" method="get">
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


// WALKIN PATIENT AJAX
if (isset($_POST['searchWalkInPatient'])) {

    $search = trim(htmlspecialchars($_POST['searchWalkInPatient']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM walkinpatient WHERE walkInName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $walkInOutput = '';

    if ($stmt->rowCount() > 0) {



        $walkInOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Patient ID</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Email</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Mobile</th>
            <th scope="col">Patient Disease</th>
            <th scope="col">Patient Birthday</th>
            <th scope="col">Patient Gender</th>
            <th scope="col">Patient Doctor</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $walkInOutput .= '
        <tr>
            <th scope="row">' . $walkInPatient['walkInId'] . '</th>
            <td>' . $walkInPatient['walkInName'] . '</td>
            <td>' . $walkInPatient['walkInEmail'] . '</td>
            <td>' . $walkInPatient['walkInAddress'] . '</td>
            <td>' . $walkInPatient['walkInMobile'] . '</td>
            <td>' . $walkInPatient['walkInDisease'] . '</td>
            <td>' . $walkInPatient['walkInAge'] . '</td>
            <td>' . ucwords($walkInPatient['walkInGender']) . '</td>
            <td>' . $walkInPatient['walkInDoctor'] . '</td>
            <td>
                <div class="row">
                    <div class="col">
                        <form action="updateWalkInPatient.php" method="get">
                            <input type="hidden" name="id" value="' . $walkInPatient['walkInId'] . '">
                            <input type="submit" value="Update" class="btn btn-secondary" name="updateWalkInBtn">
                        </form>
                    </div>
                    <div class="col">
                        <form action="walkInPatient.php" method="post">
                            <input type="hidden" name="walkInId" value="' . $walkInPatient['walkInId'] . '">
                            <input type="submit" value="Delete" class="btn btn-danger" name="deleteWalkInBtn" onclick="return confirm(\'Are you sure to delete ?\');"">
                        </form>
                    </div>
                </div>
            </td>
        ';
        }
        $walkInOutput .= '
    </tr>
    <tbody>
    ';

        echo $walkInOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}

// NURSE  AJAX
if (isset($_POST['searchNurse'])) {

    $search = trim(htmlspecialchars($_POST['searchNurse']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM nurse_receptionist WHERE nName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $nurseOutput = '';

    if ($stmt->rowCount() > 0) {



        $nurseOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Secretary ID</th>
            <th scope="col">Secretary Profile Img</th>
            <th scope="col">Secretary Name</th>
            <th scope="col">Secretary Email</th>
            <th scope="col">Secretary Address</th>
            <th scope="col">Secretary Mobile</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($nurse = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nurseOutput .= '
        <tr>
                <th scope="row">' . $nurse['nId'] . '</th>
                <td><img src="../upload/nurse_profile_img/' . $nurse['nProfileImg'] . '" width="50" height="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                <td>' . $nurse['nName'] . '</td>
                <td>' . $nurse['nEmail'] . '</td>
                <td>' . $nurse['nAddress'] . '</td>
                <td>' . $nurse['nMobile'] . '</td>
                <td>
                    <div class="row">
                        <div class="col">
                            <form action="updateNurse.php" method="get">
                                <input type="hidden" name="id" value="' . $nurse['nId'] . '">
                                <input type="submit" value="Update" class="btn btn-secondary" name="updateNurseBtn">
                            </form>
                        </div>
                        <div class="col">
                            <form action="nurse.php" method="post">
                                <input type="hidden" name="id" value="' . $nurse['nId'] . '">
                                <input type="submit" value="Delete" class="btn btn-danger" name="deleteNurse" onclick="return confirm(\'Are you sure to delete ?\');">
                            </form>
                        </div>
                    </div>
                </td>
    //     ';
        }
        $nurseOutput .= '
    </tr>
    <tbody>
    ';

        echo $nurseOutput;
    } else {
        echo '<h3 style="color:red">No Nurse Found</h3>';
    }
}


// SEARCH FINISHED APPOINTMENT  AJAX
if (isset($_POST['searchFinishedAppointment'])) {

    $search = trim(htmlspecialchars($_POST['searchFinishedAppointment']));
    $searchName = "%" . $search . "%";

    $status = "discharged";
    $sql = "SELECT * FROM appointment WHERE pName LIKE :name AND aStatus = :status";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $finishedAppointmentOutput = '';

    if ($stmt->rowCount() > 0) {



        $finishedAppointmentOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Appointment ID</th>
            <th scope="col">Patient ID</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Doctor</th>
            <th scope="col">Appointment Reason</th>
            <th scope="col">Appointment Fee</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Generate Medical Certificate</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($finishedAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $finishedAppointmentOutput .= '
            <tr>
                <th scope="row">' . $finishedAppointment['aId'] . '</th>
                <td>' . $finishedAppointment['pId'] . '</td>
                <td>' . $finishedAppointment['pName'] . '</td>
                <td>' . $finishedAppointment['pAddress'] . '</td>
                <td>' . $finishedAppointment['pDoctor'] . '</td>
                <td>' . $finishedAppointment['aReason'] . '</td>
                <td>' . $finishedAppointment['dFee'] . '</td>
                <td>' . date("M d, Y", strtotime($finishedAppointment['aMadeOn'])) . ' at ' . $finishedAppointment['aTime'] . '</td>
                <td>
                    <p class="btn btn-success disabled">Finished</p>
                </td>
                <td>
                    <form action="medicalCert.php" method="post" target="_blank">
                        <input type="hidden" name="aId" value=' . $finishedAppointment['aId'] . '>
                        <input type="hidden" name="pId" value=' . $finishedAppointment['pId'] . '>
                        <input type="submit" class="btn btn-info" name="medicalCertBtn" target="_blank" value="Medical Certificate">
                    </form>
                </td>
        ';
        }
        $finishedAppointmentOutput .= '
    </tr>
    <tbody>
    ';

        echo $finishedAppointmentOutput;
    } else {
        echo '<h3 style="color:red">No Finished Appointment</h3>';
    }
}


// CANCELLED APPOINTMENT  AJAX
if (isset($_POST['searchCancelledAppointment'])) {

    $search = trim(htmlspecialchars($_POST['searchCancelledAppointment']));
    $searchName = "%" . $search . "%";

    $status = "cancelled";
    $sql = "SELECT * FROM appointment WHERE pName LIKE :name AND aStatus = :status";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $cancelledAppointmentOutput = '';

    if ($stmt->rowCount() > 0) {



        $cancelledAppointmentOutput .= '
    <thead class="thead-dark">
    <tr>
        <th scope="col">Appointment ID</th>
        <th scope="col">Patient Name</th>
        <th scope="col">Patient Address</th>
        <th scope="col">Patient Doctor</th>
        <th scope="col">Appointment Reason</th>
        <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    ';

        while ($cancelledAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cancelledAppointmentOutput .= '
            <tr>
                <th scope="row">' . $cancelledAppointment['aId'] . '</th>
                <td>' . $cancelledAppointment['pName'] . '</td>
                <td>' . $cancelledAppointment['pAddress'] . '</td>
                <td>' . $cancelledAppointment['pDoctor'] . '</td>
                <td>' . $cancelledAppointment['aReason'] . '</td>
                <td>
                    <p class="btn btn-danger disabled">Cancelled</p>
                </td>
        ';
        }
        $cancelledAppointmentOutput .= '
    </tr>
    <tbody>
    ';

        echo $cancelledAppointmentOutput;
    } else {
        echo '<h3 style="color:red">No Cancelled Appointment</h3>';
    }
}

// MESSAGE AJAX
if (isset($_POST['searchMessage'])) {

    $search = trim(htmlspecialchars($_POST['searchMessage']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM message WHERE msgPatientName LIKE :name ORDER BY msgMadeOn DESC";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $messageOutput = '';

    if ($stmt->rowCount() > 0) {



        $messageOutput .= '
    <thead class="thead-dark">
    <tr>
        <th scope="col">Patient ID</th>
        <th scope="col">Patient Name</th>
        <th scope="col">Message Content</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    ';

        while ($messages = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messageOutput .= '
            <tr>
                <th scope="row">' . $messages['msgPatientId'] . '</th>
                <td>' . $messages['msgPatientName'] . '</td>
                ';
            if (strlen($messages['msgContent']) > 10) {
                $messageOutput .= '<td> ' . substr($messages['msgContent'], 0, 10) . '...' . ' </td>';
            } else {
                $messageOutput .= '<td> ' . $messages['msgContent'] . ' </td>';
            }
            $messageOutput .= '
                </td>
                <td>' . date("M d, Y", strtotime($messages['msgMadeOn'])) . '</td>
                <td>
                    <div class="row">
                        <div class="col">
                            <form action="viewMessage.php" method="post">
                                <input type="hidden" name="pId" value=' . $messages['msgPatientId'] . '>
                                <input type="hidden" name="mId" value=' . $messages['msgId'] . '>
                                <input type="submit" value="View Message" class="btn btn-info" name="viewMessageBtn">
                            </form>
                        </div>
                        <div class="col">
                            <form action="messages.php" method="post">
                                <input type="hidden" name="pId" value=' . $messages['msgPatientId'] . '>
                                <input type="hidden" name="mId" value=' . $messages['msgId'] . '>
                                <input type="submit" value="Delete Message" class="btn btn-danger" name="viewMessageDeleteBtn" onclick="return confirm(\'Are you sure to delete ?\')">
                            </form>
                        </div>
                    </div>
                </td>
        ';
        }
        $messageOutput .= '
    </tr>
    <tbody>
    ';

        echo $messageOutput;
    } else {
        echo '<h3 style="color:red">No Messages</h3>';
    }
}


// SEARCH DISCHARGED PATIENT  AJAX
if (isset($_POST['walkInDischarged'])) {

    $search = trim(htmlspecialchars($_POST['walkInDischarged']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM discharged_patient WHERE pName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $walkInDischargedOutput = '';

    if ($stmt->rowCount() > 0) {



        $walkInDischargedOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Discharged Patient ID</th>
            <th scope="col">Patient ID</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Doctor</th>
            <th scope="col">Patient Disease</th>
            <th scope="col">Doctor Prescription</th>
            <th scope="col">Discharged Date</th>
            <th scope="col">Status</th>
            <th scope="col">Generate Certificate</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($dischargedPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $walkInDischargedOutput .= '
            <tr>
            <th scope="row">' . $dischargedPatient['dpId'] . '</th>
            <td>' . $dischargedPatient['pId'] . '</td>
            <td>' . $dischargedPatient['pName'] . '</td>
            <td>' . $dischargedPatient['pAddress'] . '</td>
            <td>' . $dischargedPatient['pDoctor'] . '</td>
            <td>' . $dischargedPatient['pDisease'] . '</td>
            <td>' . $dischargedPatient['pPrescription'] . '</td>
            <td>' . date("M d, Y", strtotime($dischargedPatient['pMadeOn'])) . '</td>
            <td>
                <p class="btn btn-success disabled">Discharged</p>
            </td>
            <td>
                <form action="pdfWalkInDischarge.php?walkInDischargeReceipt=true" method="post" target="_blank">
                    <input type="hidden" name="dpId" value="' . $dischargedPatient['dpId'] . '">
                    <input type="hidden" name="pId" value="' . $dischargedPatient['pId'] . '">
                    <input type="submit" class="btn btn-info" name="receiptBtn" target="_blank" value="Medical Certificate">
                </form>
            </td>
        ';
        }
        $walkInDischargedOutput .= '
    </tr>
    <tbody>
    ';

        echo $walkInDischargedOutput;
    } else {
        echo '<h3 style="color:red">No Discharged Patient Found</h3>';
    }
}

// SEARCH DISEASE & TREATMENT
if (isset($_POST['diseaseTreatment'])) {

    $search = trim(htmlspecialchars($_POST['diseaseTreatment']));
    $searchName = "%" . $search . "%";

    $sql = "SELECT * FROM diseases_treatment WHERE dtName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $searchName, PDO::PARAM_STR);
    $stmt->execute();

    $numRows = $stmt->rowCount();

    $diseaseTreatmentOutput = '';

    if ($stmt->rowCount() > 0) {



        $diseaseTreatmentOutput .= '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Meaning</th>
            <th scope="col">Symptoms</th>
            <th scope="col">Prevention</th>
            <th scope="col">Treatment</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($diseases_treatment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $diseaseTreatmentOutput .= '
            <tr>
            <th scope="row">' . $diseases_treatment['dtName'] . '</th>
            <td>' . $diseases_treatment['dtMeaning'] . '</td>
            <td>' . $diseases_treatment['dtSymptoms'] . '</td>
            <td>' . $diseases_treatment['dtPrevention'] . '</td>
            <td>' . $diseases_treatment['dtTreatment'] . '</td>
            <td>
                <form action="updateDiseaseTreatment.php" method="post">
                    <input type="hidden" name="dtId" value="'. $diseases_treatment['dtId'] .'">
                    <input type="submit" class="btn btn-secondary" name="updateBtn" value="Update">
                </form>
                <form action="diseaseTreatment.php" method="post">
                    <input type="hidden" name="dtId" value="'. $diseases_treatment['dtId'] .'">
                    <input type="submit" class="btn btn-danger" name="deleteBtn" value="Delete">
                </form>
            </td>
        ';
        }
        $diseaseTreatmentOutput .= '
    </tr>
    <tbody>
    ';

        echo $diseaseTreatmentOutput;
    } else {
        echo '<h3 style="color:red">No Diseases & Treatment Found</h3>';
    }
}

// Audit Change

if(isset($_POST['user_role'])){

    $search = $_POST['user_role'];

    $sql = "SELECT * FROM audit_log WHERE log_user_role = :role ORDER BY log_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":role", $search, PDO::PARAM_STR);
    $stmt->execute();

    $res = '';

    $res .= '
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Login</th>
                        <th scope="col">Logout</th>
                        <th scope="col">IP address</th>
                        <th scope="col">User id</th>
                        <th scope="col">User name</th>
                        <th scope="col">User role</th>
                        <th scope="col">Actions & Viewed</th>
                    </tr>
                </thead>
                <tbody>
    ';
    while($roles = $stmt->fetch(PDO::FETCH_ASSOC)){
        $res .= '
            <tr>
                <td>'.$roles['log_login'].'</td>
                <td>'.$roles['log_logout'].'</td>
                <td>'.$roles['log_ip'].'</td>
                <td>'.$roles['log_user_id'].'</td>
                <td>'.$roles['log_user_name'].'</td>
                <td>'.ucwords($roles['log_user_role']).'</td>
                <td>'.$roles['log_action'].'</td>
            </tr>
        ';
    }

    $res .= '
            </tbody>
    ';

    echo $res;

}
