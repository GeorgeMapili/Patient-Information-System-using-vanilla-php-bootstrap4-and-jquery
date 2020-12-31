<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}


if (isset($_GET['printBillings']) && $_GET['printBillings'] == "true") {

    $discharge = 1;
    $sql = "SELECT * FROM appointment WHERE aId = :aid AND pId = :pid AND pDischarge = :discharge";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":aid", $_SESSION['Pa_aId'], PDO::PARAM_INT);
    $stmt->bindParam(":pid", $_SESSION['Pa_pId'], PDO::PARAM_INT);
    $stmt->bindParam(":discharge", $discharge, PDO::PARAM_INT);
    $stmt->execute();

    $dischargePatientAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

    require('fpdf182/fpdf.php');

    $pdf = new FPDF('P', 'mm', 'A4');

    $pdf->SetTitle($dischargePatientAppointment['pName'] . " Receipt");

    $pdf->AddPage();

    // STart here

    $pdf->SetFont('Arial', 'B', 14);

    $pdf->Cell(190, 5, 'COMPANY NAME', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(130, 5, '[Address]', 0, 0);
    $pdf->Cell(59, 5, '', 0, 1);

    $pdf->Cell(130, 5, '[Zip Code]', 0, 0);
    $pdf->Cell(25, 5, 'Date', 0, 0);
    $pdf->Cell(34, 5, date("M d, Y"), 0, 1);

    $pdf->Cell(130, 5, '[Contact Number]', 0, 0);
    $pdf->Cell(25, 5, 'Patient ID', 0, 0);
    $pdf->Cell(34, 5, $dischargePatientAppointment['pId'], 0, 1);

    $pdf->Cell(130, 5, '[Fax #]', 0, 1);

    // empty cell as a vertical spacer
    $pdf->Cell('189', 10, '', 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    // billing address
    $pdf->Cell(100, 10, 'Patient', 0, 1);

    $pdf->SetFont('Arial', '', 12);

    // add cell at beginning of each line  for indentation
    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Name: ' . $dischargePatientAppointment['pName'], 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Disease: ' . $dischargePatientAppointment['aReason'], 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Prescription: ' . $dischargePatientAppointment['pPrescription'], 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, 'Doctor: ' . $dischargePatientAppointment['pDoctor'], 0, 1);

    // empty cell as a vertical spacer
    $pdf->Cell('189', 10, '', 0, 1);

    // invoice contents
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(130, 5, 'Patient Billing', 1, 0);
    $pdf->Cell(25, 5, 'Taxable', 1, 0);
    $pdf->Cell(34, 5, 'Amount', 1, 1);

    $pdf->SetFont('Arial', '', 12);
    // Numbers are right-aligned so we give 'R' after new line 

    $pdf->Cell(130, 5, 'Doctor Fee: ', 1, 0);
    $pdf->Cell(25, 5, $dischargePatientAppointment['dFee'], 1, 0);
    $pdf->Cell(34, 5, $dischargePatientAppointment['dFee'], 1, 1, 'R');

    $pdf->Cell(130, 5, 'Medicine Fee: ', 1, 0);
    $pdf->Cell(25, 5, $dischargePatientAppointment['pMedicineFee'], 1, 0);
    $pdf->Cell(34, 5, $dischargePatientAppointment['pMedicineFee'], 1, 1, 'R');

    // Summary
    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Total Amount:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatientAppointment['pTotalPay'], 1, 1, 'R');

    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Amount Pay:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatientAppointment['pAmountPay'], 1, 1, 'R');

    $pdf->Cell(130, 5, '', 1, 0);
    $pdf->Cell(34, 5, 'Change:', 1, 0);
    // $pdf->Cell(4, 5, '', 1, 0);
    $pdf->Cell(25, 5, $dischargePatientAppointment['pChange'], 1, 1, 'R');



    $pdf->Output();
} else {
    header("location:dashboard.php");
    exit(0);
}
