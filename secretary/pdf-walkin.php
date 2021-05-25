<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

$_SESSION['log_secretary_pdf_walkin'] = true;

if (isset($_POST['walkInDischargeReceipt'])) {


    $sql = "SELECT * FROM discharged_patient WHERE pId = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['walkInId'], PDO::PARAM_INT);
    $stmt->execute();

    $dischargePatient = $stmt->fetch(PDO::FETCH_ASSOC);

    require('fpdf182/fpdf.php');

    $pdf = new FPDF('P', 'mm', 'A4');

    $pdf->SetTitle($dischargePatient['pName'] . " Receipt");

    $pdf->AddPage();

    // STart here

    $pdf->SetFont('Arial', 'B', 14);

    $pdf->Cell(190, 5, 'SUMC Doctors Clinic', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(130, 5, 'Dumaguete, Negros Oriental', 0, 0);
    $pdf->Cell(59, 5, '', 0, 1);

    $pdf->Cell(130, 5, 'Tel. 420 2000', 0, 0);
    $pdf->Cell(25, 5, 'Date', 0, 0);
    $pdf->Cell(34, 5, date("M d, Y", strtotime($dischargePatient['pMadeOn'])), 0, 1);

    $pdf->Cell(130, 5, 'Zip 6200', 0, 0);
    $pdf->Cell(25, 5, 'Patient ID:', 0, 0);
    $pdf->Cell(34, 5, $dischargePatient['pId'], 0, 1);

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(29, 5, 'Discharged ID:', 0, 0);
    $pdf->Cell(30, 5, $dischargePatient['dpId'], 0, 1);

    // empty cell as a vertical spacer
    $pdf->Cell('189', 10, '', 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    // billing address
    $pdf->Cell(100, 10, 'Patient', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    // add cell at beginning of each line  for indentation
    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Name: ' . $dischargePatient['pName'], 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Doctor: ' . $dischargePatient['pDoctor'], 0, 1);

    $pdf->Cell(10, 5, '', 0, 1);
    $pdf->Cell(10, 5, '', 0, 1);

    $pdf->Cell(90, 5, 'Disease: ', 0, 1);
    $pdf->Write(5, $dischargePatient['pDisease']);

    $pdf->Cell(10, 5, '', 0, 1);
    $pdf->Cell(10, 5, '', 0, 1);

    $pdf->Cell(90, 5, 'Prescription: ', 0, 1);
    $pdf->Write(5, $dischargePatient['pPrescription']);



    // empty cell as a vertical spacer
    $pdf->Cell('189', 10, '', 0, 1);

    // invoice contents
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(130, 5, 'Patient Billing', 1, 0);
    $pdf->Cell(25, 5, 'Taxable', 1, 0);
    $pdf->Cell(34, 5, 'Amount', 1, 1);

    $pdf->SetFont('Arial', '', 12);
    // Numbers are right-aligned so we give 'R' after new line 

    $sql = "SELECT * FROM doctor WHERE dName = :name";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $dischargePatient['pDoctor'], PDO::PARAM_STR);
    $stmt->execute();

    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdf->Cell(130, 5, 'Doctor Fee: ', 1, 0);
    $pdf->Cell(25, 5, $doctor['dFee'], 1, 0);
    $pdf->Cell(34, 5, $doctor['dFee'], 1, 1, 'R');

    $pdf->Cell(130, 5, 'Medicine Fee: ', 1, 0);
    $pdf->Cell(25, 5, (int)$dischargePatient['pTotalAmount'] - (int)$doctor['dFee'], 1, 0);
    $pdf->Cell(34, 5, (int)$dischargePatient['pTotalAmount'] - (int)$doctor['dFee'], 1, 1, 'R');

    // Summary
    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Total Amount:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatient['pTotalAmount'], 1, 1, 'R');

    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Amount Pay:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatient['pAmountPay'], 1, 1, 'R');

    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Change:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatient['pChange'], 1, 1, 'R');



    $pdf->Output();
} else {
    header("location:dashboard.php");
    exit(0);
}
