<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

if (isset($_POST['id'])) {

    $id = $_POST['id'];
    // $_SESSION['doctorUpdatePrescription'] = $_POST['id'];

    // $sql = "SELECT * FROM walkinpatient WHERE walkInId = :id";
    // $stmt = $con->prepare($sql);
    // $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    // $stmt->execute();

    $res = '';

    $res .= '
    <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded" id="table-data">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Patient Name</th>
            <th scope="col">Patient Address</th>
            <th scope="col">Patient Mobile</th>
            <th scope="col">Patient Disease</th>
            <th scope="col">Patient Doctor</th>
            <th scope="col">Doctor Prescription</th>
            <th scope="col">Add</th>
            <th scope="col">Generate</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    ';
    $limit = 5;
    $stmt = $con->prepare("SELECT * FROM walkinpatient");
    $stmt->execute();
    $countPatientAppointment = $stmt->rowCount();
    $pages = ceil($countPatientAppointment / $limit);

    $firstPageValue = $pages;

    if (ceil($countPatientAppointment / $limit) == 0) {
        $pages = 1;
    }

    // $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page = $_GET['page'] ?? 1;

    $prev = $page - 1;
    $next = $page + 1;
    $discharged = 0;
    $start = ($page - 1) * $limit;
    $sql = "SELECT * FROM walkinpatient WHERE walkInDischarged = :discharged LIMIT :start, :limit ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":discharged", $discharged, PDO::PARAM_INT);
    $stmt->bindParam(":start", $start, PDO::PARAM_INT);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->execute();

    while ($walkInPatient = $stmt->fetch(PDO::FETCH_ASSOC)) :
        $sql1 = "SELECT * FROM medicalinformation WHERE pId = :id";
        $stmt1 = $con->prepare($sql1);
        $stmt1->bindParam(":id", $walkInPatient['walkInId'], PDO::PARAM_INT);
        $stmt1->execute();

        $medInfoExst = $stmt1->fetch(PDO::FETCH_ASSOC);

        $res .= '
        <tr>
                            <td>' . $walkInPatient['walkInName'] . '</td>
                            <td>' . $walkInPatient['walkInAddress'] . '</td>
                            <td>' . $walkInPatient['walkInMobile'] . '</td>
                            <td>' . $walkInPatient['walkInDisease'] . '</td>
                            <td>' . $walkInPatient['walkInDoctor'] . '</td>
                            <td id="prescription">' . $walkInPatient['walkInPrescription'] . '</td>
                            <td>';
        $medInfoExst = $medInfoExst['pId'] ?? 0;
        if ($medInfoExst == $walkInPatient['walkInId']) {
            $res .= '
                                    <form action="updateMedicalInformation.php" method="get">
                                        <input type="hidden" name="id" value="' . $walkInPatient['walkInId'] . '">
                                        <input type="submit" value="UPDATE MEDICAL INFORMATION" class="btn btn-secondary" name="medicalInformation">
                                    </form>';
        } else {
            $res .= '
                                    <form action="addMedicalInformation.php" method="post">
                                        <input type="hidden" name="id" value="' . $walkInPatient['walkInId'] . '">
                                        <input type="submit" value="ADD MEDICAL INFORMATION" class="btn btn-info" name="medicalInformation">
                                    </form>';
        }
        $res .= '
                            </td>
                            <td>';
        if (empty($walkInPatient['walkInPrescription'])) {
            $res .= '
                                    <p class="btn btn-primary disabled" title="Can\'t generate bill without prescription">GENERATE BILL</p> ';
        } else {
            $res .= '
                                    <form action="generateBill.php" method="post">
                                        <input type="hidden" name="id" value="' . $walkInPatient['walkInId'] . '">
                                        <input type="submit" value="GENERATE BILL" class="btn btn-primary" name="generateBill">
                                    </form>';
        }
        $res .= '
                            </td>
                            <td>
                                <form action="patientWalkIn.php" method="post">
                                    <input type="hidden" name="walkInId" value="' . $walkInPatient['walkInId'] . '">
                                    <input type="submit" name="deleteWalkInPatientBtn" class="btn btn-danger" value="DELETE" onclick="return confirm(\'Are you sure to delete ?\')">
                                </form>
                            </td>
                        </tr>
                        ';

    endwhile;

    $res .= '
    </tbody>';

    echo $res;
}
