<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

//Ajax Patient Appointment

$output = '';

if (isset($_POST['query'])) {
    $start = 0;
    $limit = 5;
    $search = trim(htmlspecialchars($_POST['query']));
    $name = "%" . $search . "%";
    $stmt = $con->prepare("SELECT * FROM patientappointment WHERE pName LIKE :name LIMIT :start,:limit");
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":start", $start, PDO::PARAM_INT);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);

    $stmt->execute();
    $userRow = $stmt->rowCount();

    // var_dump($userRow);
    // exit(0);

    if ($userRow > 0) {
        $output = '
    <thead class="thead-dark">
        <tr>
            <th scope="col">Patient ID</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Mobile</th>
            <th scope="col">Patient Disease</th>
            <th scope="col">Patient Doctor</th>
            <th scope="col">Doctor Prescription</th>
            <th scope="col">Add</th>
            <th scope="col">Generate</th>
        </tr>
    </thead>
    <tbody>
    ';

        while ($patientAppointment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= '
        <tr>
            <th scope="row">' . $patientAppointment['pId'] . '</th>
            <td>' . $patientAppointment['pName'] . '</td>
            <td>' . $patientAppointment['pAddress'] . '</td>
            <td>' . $patientAppointment['pMobile'] . '</td>
            <td>' . $patientAppointment['pDisease'] . '</td>
            <td>' . $patientAppointment['pDoctor'] . '</td>
            <td>' . $patientAppointment['pPrescription'] . '</td>
            <td>
                <input type="submit" value="ADD MEDICAL INFORMATION" class="btn btn-info" name="appointmentStatus">
            </td>
            <td>
                <input type="submit" value="GENERATE BILL" class="btn btn-primary" name="appointmentStatus">
            </td>
        </tr>
        ';
        }
        $output .= '</tbody>';

        echo $output;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}

// Ajax Walk in patient

$walkInOutput = '';

if (isset($_POST['walkInQuery'])) {
    $start = 0;
    $limit = 5;
    $discharged = 0;
    $search = trim(htmlspecialchars($_POST['walkInQuery']));
    $name = "%" . $search . "%";
    $stmt = $con->prepare("SELECT * FROM walkinpatient WHERE walkInName LIKE :name AND walkInDischarged = :discharged LIMIT :start,:limit");
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":discharged", $discharged, PDO::PARAM_INT);
    $stmt->bindParam(":start", $start, PDO::PARAM_INT);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);

    $stmt->execute();
    $walkInRow = $stmt->rowCount();

    if ($walkInRow > 0) {
        $walkInOutput = '
<thead class="thead-dark">
    <tr>
        <th scope="col">Patient Name</th>
        <th scope="col">Patient Address</th>
        <th scope="col">Patient Mobile</th>
        <th scope="col">Patient Disease</th>
        <th scope="col">Patient Doctor</th>
        <th scope="col">Doctor Prescription</th>
        <th scope="col">Room #</th>
        <th scope="col">Add</th>
        <th scope="col">Generate</th>
    </tr>
</thead>
<tbody>
';

        while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sql1 = "SELECT * FROM medicalinformation WHERE pId = :id";
            $stmt1 = $con->prepare($sql1);
            $stmt1->bindParam(":id", $walkInPatient['walkInId'], PDO::PARAM_INT);
            $stmt1->execute();

            $medInfoExst = $stmt1->fetch(PDO::FETCH_ASSOC);


            $walkInOutput .= '
    <tr>
        <td>' . $walkInPatient['walkInName'] . '</td>
        <td>' . $walkInPatient['walkInAddress'] . '</td>
        <td>' . $walkInPatient['walkInMobile'] . '</td>
        <td>' . $walkInPatient['walkInDisease'] . '</td>
        <td>' . $walkInPatient['walkInDoctor'] . '</td>
        <td>' . $walkInPatient['walkInPrescription'] . '</td>
        <td>' . $walkInPatient['walkInRoomNumber'] . '</td>
        <td>';

            $medInfoExst = $medInfoExst['pId'] ?? 0;
            if ($medInfoExst == $walkInPatient['walkInId']) {
                $walkInOutput .= '
        <form action="updateMedicalInformation.php" method="get">
            <input type="hidden" name="id" value=' . $walkInPatient['walkInId'] . '>
            <input type="submit" value="UPDATE MEDICAL INFORMATION" class="btn btn-secondary" name="medicalInformation">
        </form>
        ';
            } else {
                $walkInOutput .= '
        <form action="addMedicalInformation.php" method="post">
            <input type="hidden" name="id" value=' . $walkInPatient['walkInId'] . '>
            <input type="submit" value="ADD MEDICAL INFORMATION" class="btn btn-info" name="medicalInformation">
        </form>
        ';
            }
            $walkInOutput .= '
            </td>
            <td>
            <form action="generateBill.php" method="post">
                <input type="hidden" name="id" value=' . $walkInPatient['walkInId'] . '>
                <input type="submit" value="GENERATE BILL" class="btn btn-primary" name="generateBill">
            </form>
            </td>
        </tr>
        ';


            $walkInOutput .= '</tbody>';
        }
        echo $walkInOutput;
    } else {
        echo '<h3 style="color:red">No Patient Found</h3>';
    }
}

if (isset($_POST['updateMedInfo'])) {
    $id = trim(htmlspecialchars($_POST['id']));
    $height = trim(htmlspecialchars($_POST['height']));
    $weight = trim(htmlspecialchars($_POST['weight']));
    $bloodType = trim(htmlspecialchars($_POST['bloodType']));
    $allergy = trim(htmlspecialchars($_POST['allergy']));
    $medInfo  = $_POST['followingMed'];

    $medInfoStr = implode(",", $medInfo);

    $sql = "SELECT * FROM medicalinformation WHERE pId = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $updateMed = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($updateMed['pId'] == $id && $updateMed['pHeight'] == $height && $updateMed['pWeight'] == $weight && $updateMed['pBloodType'] == $bloodType && $updateMed['pAllergy'] == $allergy && $updateMed['pMedicalInfo'] == $medInfoStr) {
        header("location:patientWalkIn.php?errUp=Nothing_to_update");
        exit(0);
    }

    $sql = "UPDATE medicalinformation SET pHeight = :height, pWeight = :weight, pBloodType = :bloodType, pAllergy = :allergy, pMedicalInfo = :medinfo WHERE pId =:id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":height", $height, PDO::PARAM_INT);
    $stmt->bindParam(":weight", $weight, PDO::PARAM_INT);
    $stmt->bindParam(":bloodType", $bloodType, PDO::PARAM_STR);
    $stmt->bindParam(":allergy", $allergy, PDO::PARAM_STR);
    $stmt->bindParam(":medinfo", $medInfoStr, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    header("location:patientWalkIn.php?succUp=Updated_successfully");
    exit(0);
}

if (isset($_POST['medPrice'])) {
    $medPrice = $_POST['medPrice'];
    $doctorFee = $_POST['doctorFee'];
    $roomFee = $_POST['roomFee'];
    $id = $_POST['id'];

    $totalprice = 0;

    $totalprice = (int)$medPrice + (int)$doctorFee + (int)$roomFee;

    $sql = "UPDATE walkinpatient SET walkInTotalPay = :totalpay, medicineFee = :medFee WHERE walkInId =:id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":totalpay", $totalprice, PDO::PARAM_INT);
    $stmt->bindParam(":medFee", $medPrice, PDO::PARAM_INT);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}
