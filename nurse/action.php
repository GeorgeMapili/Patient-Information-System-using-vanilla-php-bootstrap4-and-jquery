<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

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
}

$stmt->execute();
$userRow = $stmt->rowCount();


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
