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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
            $stmt->bindParam(":doctor", $_SESSION['dName'], PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":months", $month, PDO::PARAM_INT);
            $stmt->execute();

            $output .= '
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
                    $output .= '   <form action="incomingAppointment.php" method="post">
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
                                <form action="incomingAppointment.php" method="post">
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
        <thead class="thead-dark">
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
                <form action="addPrescription.php" method="post">
                    <input type="hidden" name="aid" value=' . $patientAppointment['aId'] . '>
                    <input type="hidden" name="pid" value=' . $patientAppointment['pId'] . '>
                    <input type="submit" value="Add Prescription" class="btn btn-info" name="addPrescriptionBtn">
                </form>
                ';
            } else {
                $patientOutput .= '
                <form action="editPrescription.php" method="post">
                    <input type="hidden" name="aid" value=' . $patientAppointment['aId'] . '>
                    <input type="hidden" name="pid" value=' . $patientAppointment['pId'] . '>
                    <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionBtn">
                </form>
                ';
            }

            $patientOutput .= '
            </td>
                <td>
                    <form action="updateDisease.php" method="post">
                        <input type="hidden" name="aId" value=' . $patientAppointment['aId'] . '>
                        <input type="hidden" name="pId" value=' . $patientAppointment['pId'] . '>
                        <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                    </form>
                </td>
                <td>
                    <form action="patientMedicalHistory.php" method="post">
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
        <thead class="thead-dark">
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
                $walkInOutput .= '    <form action="addPrescriptionWalkIn.php" method="post">
                                        <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                        <input type="submit" value="Add Prescription" class="btn btn-primary" name="addPrescriptionWalkIn">
                                    </form>';
            } else {
                $walkInOutput .= '    <form action="updatePrescriptionWalkIn.php" method="post">
                                        <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                        <input type="submit" value="Update Prescription" class="btn btn-secondary" name="updatePrescriptionWalkIn">
                                    </form>';
            }
            $walkInOutput .= '
                            </td>
                            <td>
                            <form action="updateDiseaseWalkIn.php" method="post">
                                <input type="hidden" name="id" value=' . $walkIn['walkInId'] . '>
                                <input type="submit" value="Update Disease" class="btn btn-primary" name="updateDiseaseWalkIn">
                            </form>
                        </td>
                        <td>
                            <form action="watchMedicalInfo.php" method="post">
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
