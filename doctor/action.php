<?php
session_start();
require_once '../connect.php';


if (isset($_POST['sortBy'])) {

    $sortBy = $_POST['sortBy'];

    $output = '';

    switch ($sortBy) {

            // DEFAULT
        case 'default':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status ORDER BY aDate,aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($default = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $default['pName'] . '</td>
                    <td>' . $default['pAddress'] . '</td>
                    <td>' . $default['pMobile'] . '</td>
                    <td>' . $default['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($default['aDate'])) . " at " . $default['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($default['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $default['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $default['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $default['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $default['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // TODAY
        case 'today':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND aDate = DATE(NOW()) ORDER BY aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($today = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $today['pName'] . '</td>
                    <td>' . $today['pAddress'] . '</td>
                    <td>' . $today['pMobile'] . '</td>
                    <td>' . $today['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($today['aDate'])) . " at " . $today['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($today['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $today['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $today['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $today['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $today['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // TOMORROW 
        case 'tomorrow':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND aDate = curdate() + interval 1 day ORDER BY aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($tomorrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $tomorrow['pName'] . '</td>
                    <td>' . $tomorrow['pAddress'] . '</td>
                    <td>' . $tomorrow['pMobile'] . '</td>
                    <td>' . $tomorrow['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($tomorrow['aDate'])) . " at " . $tomorrow['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($tomorrow['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $tomorrow['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $tomorrow['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $tomorrow['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $tomorrow['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // THIS WEEK
        case 'this_week':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND YEARWEEK(aDate) = YEARWEEK(NOW()) ORDER BY aDate,aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($thisWeek = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $thisWeek['pName'] . '</td>
                    <td>' . $thisWeek['pAddress'] . '</td>
                    <td>' . $thisWeek['pMobile'] . '</td>
                    <td>' . $thisWeek['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($thisWeek['aDate'])) . " at " . $thisWeek['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($thisWeek['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $thisWeek['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $thisWeek['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $thisWeek['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $thisWeek['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // NEXT WEEK
        case 'next_week':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND YEARWEEK(aDate) = YEARWEEK(NOW() + INTERVAL 7 DAY) ORDER BY aDate, aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($nextWeek = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $nextWeek['pName'] . '</td>
                    <td>' . $nextWeek['pAddress'] . '</td>
                    <td>' . $nextWeek['pMobile'] . '</td>
                    <td>' . $nextWeek['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($nextWeek['aDate'])) . " at " . $nextWeek['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($nextWeek['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $nextWeek['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $nextWeek['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $nextWeek['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $nextWeek['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // THIS MONTH
        case 'this_month':
            $status = "accepted";
            $month = date("m");

            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND EXTRACT(MONTH FROM aDate) = :months ORDER BY aDate, aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":doctor", $_SESSION['ddName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":months", $month, PDO::PARAM_INT);
            $stmt->execute();

            $output .= '
            <thead class="bg-info text-light">
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
            ';

            while ($thisMonth = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $thisMonth['pName'] . '</td>
                    <td>' . $thisMonth['pAddress'] . '</td>
                    <td>' . $thisMonth['pMobile'] . '</td>
                    <td>' . $thisMonth['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($thisMonth['aDate'])) . " at " . $thisMonth['aTime'] . '</td>
                    <td>
                        <div class="row">
                            <div class="col">';
                if (date("M d, Y") === date("M d, Y", strtotime($thisMonth['aDate']))) {
                    $output .= '   <form action="incoming-appointment.php" method="post">
                                        <input type="hidden" name="aId" value=' . $thisMonth['aId'] . '>
                                        <input type="hidden" name="pId" value=' . $thisMonth['pId'] . '>
                                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                                    </form>';
                } else {
                    $output .= ' <p class="btn btn-success disabled">Done</p>';
                }
                $output .= '
                            </div>
                            <div class="col">
                                <form action="incoming-appointment.php" method="post">
                                    <input type="hidden" name="aId" value=' . $thisMonth['aId'] . '>
                                    <input type="hidden" name="pId" value=' . $thisMonth['pId'] . '>
                                    <input type="submit" value="Cancel" class="btn btn-danger" name="cancelAppointment" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

        default:
            # code...
            break;
    }
    echo '<h3 style="color:red">No Patient Found!!</h3>';
}

// PATIENT APPOINTMENT AJAX

if (isset($_POST['patientQuery'])) {
    $patientOutput = '';
    $doctor = $_POST['doctorName'];
    $status = "accepted";
    $search = trim(htmlspecialchars($_POST['patientQuery']));
    $name = "%" . $search . "%";
    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND pName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->execute();

    $patientRow = $stmt->rowCount();

    if ($patientRow > 0) {
        $patientOutput .= '
        <thead class="bg-info text-light">
            <tr>
                <th scope="col">Patient Name</th>
                <th scope="col">Patient Address</th>
                <th scope="col">Date</th>
                <th scope="col">Patient Disease</th>
                <th scope="col">Prescription</th>
                <th scope="col">Updated Disease</th>
                <th scope="col">History</th>
            </tr>
        </thead>
        <tbody>
        ';

        while ($patientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $patientOutput .= '
            <tr>
            <td>' . $patientAppointment['pName'] . '</td>
            <td>' . $patientAppointment['pAddress'] . '</td>
            <td>' . date("M d, Y", strtotime($patientAppointment['aDate'])) . " at " . $patientAppointment['aTime'] . '</td>
            <td>' . $patientAppointment['aReason'] . '</td>
            <td>';

            if (empty($patientAppointment['pPrescription'])) {
                $patientOutput .= '
                <form action="add-prescription.php" method="post">
                    <input type="hidden" name="aid" value=' . $patientAppointment['aId'] . '>
                    <input type="hidden" name="pid" value=' . $patientAppointment['pId'] . '>
                    <input type="submit" value="Add Prescription" class="btn btn-info" name="addPrescriptionBtn">
                </form>
                ';
            } else {
                $patientOutput .= '
                <form action="update-prescription.php" method="post">
                    <input type="hidden" name="aid" value=' . $patientAppointment['aId'] . '>
                    <input type="hidden" name="pid" value=' . $patientAppointment['pId'] . '>
                    <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionBtn">
                </form>
                ';
            }

            $patientOutput .= '
            </td>
                <td>
                    <form action="update-disease.php" method="post">
                        <input type="hidden" name="aId" value=' . $patientAppointment['aId'] . '>
                        <input type="hidden" name="pId" value=' . $patientAppointment['pId'] . '>
                        <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                    </form>
                </td>
                <td>
                    <form action="patient-medicalhistory.php" method="post">
                        <input type="hidden" name="aId" value=' . $patientAppointment['aId'] . '>
                        <input type="hidden" name="pId" value=' . $patientAppointment['pId'] . '>
                        <input type="submit" value="Watch appointment history" class="btn btn-primary" name="watchHistory">
                    </form>
                </td>
            </tr>
            </tbody>
            ';
        }
        echo $patientOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}


//  WALK IN PATIENT AJAX
if (isset($_POST['walkInQuery'])) {

    $walkInOutput = '';
    $search = "%" . $_POST['walkInQuery'] . "%";
    $doctor = $_POST['doctorName'];
    $discharge = 0;
    $name = "%" . $search . "%";

    $sql = "SELECT * FROM walkinpatient WHERE walkInName LIKE :name AND walkInDoctor = :doctor AND walkInDischarged = :discharge";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":doctor", $doctor, PDO::PARAM_STR);
    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
    $stmt->execute();

    $walkInRow = $stmt->rowCount();

    if ($walkInRow > 0) {
        $walkInOutput .= '
        <thead class="bg-info text-light">
            <tr>
                <th scope="col">Patient Name</th>
                <th scope="col">Patient Address</th>
                <th scope="col">Patient Mobile</th>
                <th scope="col">Patient Disease</th>
                <th scope="col">Prescription</th>
                <th scope="col">Update</th>
                <th scope="col">Information</th>
                <th scope="col">History</th>
            </tr>
        </thead>
        <tbody>
        ';

        while ($walkIn = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $walkInOutput .= '
                <tr>
                    <td>' . $walkIn['walkInName'] . '</td>
                    <td>' . $walkIn['walkInAddress'] . '</td>
                    <td>' . $walkIn['walkInMobile'] . '</td>
                    <td>' . $walkIn['walkInDisease'] . '</td>
                    <td>';
            if (empty($walkIn['walkInPrescription'])) {
                $walkInOutput .= '    <form action="prescription-walkin.php" method="post">
                                        <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                        <input type="submit" value="Add Prescription" class="btn btn-primary" name="addPrescriptionWalkIn">
                                    </form>';
            } else {
                $walkInOutput .= '    <form action="update-prescriptionwalkin.php" method="post">
                                        <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                        <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionWalkIn">
                                    </form>';
            }
            $walkInOutput .= '
                            </td>
                            <td>
                            <form action="update-diseasewalkin.php" method="post">
                                <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                <input type="submit" value="Update Disease" class="btn btn-primary" name="updateDiseaseWalkIn">
                            </form>
                        </td>
                        <td>
                            <form action="medical-info.php" method="post">
                                <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                <input type="submit" value="Watch Medical Information" class="btn btn-primary" name="watchMedInfo">
                            </form>
                        </td>
                        <td>
                            <form action="watchMedicalHistory.php" method="post">
                                <input type="hidden" name="name" value="' . $walkIn['walkInName'] . '">
                                <input type="submit" value="Watch Medical History" class="btn btn-info" name="watchMedHistory">
                            </form>
                        </td>
                </tr>
            </tbody>
            ';
        }
        echo $walkInOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}

// LABORATORY PATIENT APPOINTMENT

if (isset($_POST['labPatientName'])) {
    $labAppointmentInput = '';
    $labPatientName =  '%'.trim(htmlspecialchars($_POST['labPatientName'])).'%';
    $doctorName = $_POST['doctorName'];
    $status = "accepted";

    $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor AND aStatus = :status AND pName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":doctor", $doctorName, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":name", $labPatientName, PDO::PARAM_STR);
    $stmt->execute();

    $labPatientAppointmentInRow = $stmt->rowCount();

    if ($labPatientAppointmentInRow > 0) {
        $labAppointmentInput .= '
        <thead class="bg-info text-light">
            <tr>
                <th scope="col">Patient Name</th>
                <th scope="col">Date</th>
                <th scope="col">Patient Disease</th>
                <th scope="col">Laboratory Test</th>
                <th scope="col">Result</th>
            </tr>
        </thead>
        <tbody>
        ';

        while ($labPatientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labAppointmentInput .= '
                <tr>
                    <td>' . $labPatientAppointment['pName'] . '</td>
                    <td>' . date("M d, Y", strtotime($labPatientAppointment['aDate'])). ' at ' .$labPatientAppointment['aTime'] . '</td>
                    <td>' . $labPatientAppointment['aReason'] . '</td>
                    <td>';
                if(empty($labPatientAppointment['labTest'])){
                $labAppointmentInput .= '    <form action="add-testlab-appointment.php" method="post">
                                            <input type="hidden" name="aId" value=' . $labPatientAppointment['aId'] . '>
                                            <input type="hidden" name="pId" value=' . $labPatientAppointment['pId'] . '>
                                            <input type="submit" value="Add Test" class="btn btn-primary" name="addTestLab">
                                        </form>
                                        </td>';
                } else {
                    $labAppointmentInput .= '
                                        <form action="update-testlab-appointment.php" method="post">
                                            <input type="hidden" name="aId" value=' . $labPatientAppointment['aId'] . '>
                                            <input type="hidden" name="pId" value=' . $labPatientAppointment['pId'] . '>
                                                <input type="submit" value="Update Test" class="btn btn-secondary" name="updateTestLab">
                                        </form>
                                        </td>';
                }

                $labAppointmentInput .= '<td>';

                if(empty($labPatientAppointment['labTest'])){

                    $labAppointmentInput .= '<p class="btn btn-primary disabled">Add Result</p>
                    </td>';

                } else {

                        if(empty($labPatientAppointment['labResult'])){

                            $labAppointmentInput .= '
                            <form action="add-result-appointment.php" method="post">
                                <input type="hidden" name="aId" value=' . $labPatientAppointment['aId'] . '>
                                <input type="hidden" name="pId" value=' . $labPatientAppointment['pId'] . '>
                                <input type="submit" value="Add Result" class="btn btn-primary" name="addResultLab">
                            </form>
                            </td>';

                        }else{

                            $labAppointmentInput .= '
                            <form action="update-result-appointment.php" method="post">
                                <input type="hidden" name="aId" value=' . $labPatientAppointment['aId'] . '>
                                <input type="hidden" name="pId" value=' . $labPatientAppointment['pId'] . '>
                                <input type="submit" value="Update Result" class="btn btn-secondary" name="updateResultLab">
                            </form>
                            </td>';
                        }
                        

                }

                $labAppointmentInput .= '</tr></tbody>';

        }
        echo $labAppointmentInput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }

}

// LABORATORY WALKIN PATIENT
if (isset($_POST['labWalkin'])) {
    $labWalkInInput = '';
    $labWalkin =  '%'.trim(htmlspecialchars($_POST['labWalkin'])).'%';
    $doctorName = $_POST['doctorName'];

    $sql = "SELECT * FROM walkinpatient WHERE walkInDoctor = :doctor AND walkInName LIKE :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":doctor", $doctorName, PDO::PARAM_STR);
    $stmt->bindParam(":name", $labWalkin, PDO::PARAM_STR);
    $stmt->execute();

    $labWalkInRow = $stmt->rowCount();

    if ($labWalkInRow > 0) {
        $labWalkInInput .= '
        <thead class="bg-info text-light">
            <tr>
                <th scope="col">Patient Name</th>
                <th scope="col">Patient Address</th>
                <th scope="col">Patient Disease</th>
                <th scope="col">Laboratory Test</th>
                <th scope="col">Result</th>
            </tr>
        </thead>
        <tbody>
        ';

        while ($labWalkIn = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labWalkInInput .= '
                <tr>
                    <td>' . $labWalkIn['walkInName'] . '</td>
                    <td>' . $labWalkIn['walkInAddress'] . '</td>
                    <td>' . $labWalkIn['walkInDisease'] . '</td>
                    <td>';
                if(empty($labWalkIn['labTest'])){
                $labWalkInInput .= '    <form action="add-testlab-walkin.php" method="post">
                                            <input type="hidden" name="walkInId" value=' . $labWalkIn['walkInId'] . '>
                                            <input type="submit" value="Add Test" class="btn btn-primary" name="addTestLab">
                                        </form>
                                        </td>';
                } else {
                    $labWalkInInput .= '
                                        <form action="update-testlab-walkin.php" method="post">
                                            <input type="hidden" name="walkInId" value=' . $labWalkIn['walkInId'] . '>
                                                <input type="submit" value="Update Test" class="btn btn-secondary" name="updateTestLab">
                                        </form>
                                        </td>';
                }

                $labWalkInInput .= '<td>';

                if(empty($labWalkIn['labTest'])){

                    $labWalkInInput .= '<p class="btn btn-primary disabled">Add Result</p>
                    </td>';

                } else {

                        if(empty($labWalkIn['labResult'])){

                            $labWalkInInput .= '
                            <form action="add-result-walkin.php" method="post">
                                <input type="hidden" name="walkInId" value=' . $labWalkIn['walkInId'] . '>
                                <input type="submit" value="Add Result" class="btn btn-primary" name="addResultLab">
                            </form>
                            </td>';

                        }else{

                            $labWalkInInput .= '
                            <form action="update-result-walkin.php" method="post">
                                <input type="hidden" name="walkInId" value=' . $labWalkIn['walkInId'] . '>
                                <input type="submit" value="Update Result" class="btn btn-secondary" name="updateResultLab">
                            </form>
                            </td>';
                        }
                        

                }

                $labWalkInInput .= '</tr></tbody>';

        }
        echo $labWalkInInput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }

}