<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['adId'])) {
    header("location:index.php");
    exit(0);
}

require('../nurse/fpdf182/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');

$pdf->AddPage();

// STart here

if (isset($_POST['medicalCertBtn'])) {
    $aId = $_POST['aId'];
    $pId = $_POST['pId'];

    $sql = "SELECT * FROM appointment WHERE aId = :aid AND pId =:pid";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":aid", $aId, PDO::PARAM_INT);
    $stmt->bindParam(":pid", $pId, PDO::PARAM_INT);
    $stmt->execute();

    $medicalCertificate = $stmt->fetch(PDO::FETCH_ASSOC);


    $pdf->SetFont('Arial', 'B', 14);

    $pdf->Cell(190, 5, 'COMPANY NAME', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(130, 5, '[Address]', 0, 0);
    $pdf->Cell(59, 5, '', 0, 1);

    $pdf->Cell(130, 5, '[Zip Code]', 0, 0);
    $pdf->Cell(25, 5, 'Date:', 0, 0);
    $pdf->Cell(34, 5, date("M d, Y"), 0, 1);

    $pdf->Cell(130, 5, '[Contact Number]', 0, 0);
    $pdf->Cell(25, 5, 'Patient ID:', 0, 0);
    $pdf->Cell(34, 5, $medicalCertificate['pId'], 0, 1);

    $pdf->Cell(130, 5, '[Fax #]', 0, 0);
    $pdf->Cell(34, 5, 'Appointment ID:', 0, 0);
    $pdf->Cell(25, 5, $medicalCertificate['aId'], 0, 1);

    $pdf->Cell('189', 10, '', 0, 1);

    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(200, 30, 'MEDICAL CERTIFICATE', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(100, 10, 'To whom it may concern:', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    $name = $medicalCertificate['pName'];
    $address = $medicalCertificate['pAddress'];
    $date = $medicalCertificate['aDate'];
    $diagnosis = $medicalCertificate['aReason'];
    $doctor = $medicalCertificate['pDoctor'];

    $body = "This is to certify that $name of $address. Was examined and treated at the Company Name on $date with the following diagnosis $diagnosis and would medical attention for $doctor days barring complication.";

    $pdf->Cell(10, 5, '', 0, 0);

    $pdf->Write(7, $body);

    $pdf->Cell(100, 50, "Physician: $doctor");
    $pdf->Ln(10);

    $pdf->Cell(200, 30, "Patient: $name");
    $pdf->Ln(10);

    $pdf->Output();
}
