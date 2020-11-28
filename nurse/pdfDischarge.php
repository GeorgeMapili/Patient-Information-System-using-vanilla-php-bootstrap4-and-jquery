<?php

session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

require('fpdf182/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');

$pdf->AddPage();

// STart here

$pdf->SetFont('Arial', 'B', 14);

$pdf->Cell(190, 5, 'COMPANY NAME', 0, 1);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(130, 5, '[Address]', 0, 0);
$pdf->Cell(59, 5, '', 0, 1);

$pdf->Cell(130, 5, '[Zip Code]', 0, 0);
$pdf->Cell(25, 5, 'Date', 0, 0);
$pdf->Cell(34, 5, ': 08/24/2020', 0, 1);

$pdf->Cell(130, 5, '[Contact Number]', 0, 0);
$pdf->Cell(25, 5, 'Invoice #', 0, 0);
$pdf->Cell(34, 5, ': [12345]', 0, 1);

$pdf->Cell(130, 5, '[Fax #]', 0, 0);
$pdf->Cell(25, 5, 'Patient ID', 0, 0);
$pdf->Cell(34, 5, ': [123]', 0, 1);

// empty cell as a vertical spacer
$pdf->Cell('189', 10, '', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
// billing address
$pdf->Cell(100, 10, 'Patient', 0, 1);

$pdf->SetFont('Arial', '', 12);

// add cell at beginning of each line  for indentation
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(90, 5, '[Name]', 0, 1);

$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(90, 5, '[Company Name]', 0, 1);

$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(90, 5, '[Address]', 0, 1);

$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(90, 5, '[Phone]', 0, 1);

// empty cell as a vertical spacer
$pdf->Cell('189', 10, '', 0, 1);

// invoice contents
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(130, 5, 'Patient Billing', 1, 0);
$pdf->Cell(25, 5, 'Taxable', 1, 0);
$pdf->Cell(34, 5, 'Amount', 1, 1);

$pdf->SetFont('Arial', '', 12);
// Numbers are right-aligned so we give 'R' after new line 

$pdf->Cell(130, 5, 'Ultra Cool Fridge', 1, 0);
$pdf->Cell(25, 5, '-', 1, 0);
$pdf->Cell(34, 5, '3,204', 1, 1, 'R');

$pdf->Cell(130, 5, 'Something Else', 1, 0);
$pdf->Cell(25, 5, '-', 1, 0);
$pdf->Cell(34, 5, '1,204', 1, 1, 'R');

$pdf->Cell(130, 5, 'Something Else1', 1, 0);
$pdf->Cell(25, 5, '-', 1, 0);
$pdf->Cell(34, 5, '204', 1, 1, 'R');

// Summary
$pdf->Cell(130, 5, '', 1, 0);
$pdf->Cell(25, 5, 'Subtotal', 1, 0);
$pdf->Cell(4, 5, '$', 1, 0);
$pdf->Cell(30, 5, '4,612', 1, 1, 'R');

$pdf->Cell(130, 5, '', 1, 0);
$pdf->Cell(25, 5, 'Taxable', 1, 0);
$pdf->Cell(4, 5, '$', 1, 0);
$pdf->Cell(30, 5, '0', 1, 1, 'R');

$pdf->Cell(130, 5, '', 1, 0);
$pdf->Cell(25, 5, 'Total Due', 1, 0);
$pdf->Cell(4, 5, '$', 1, 0);
$pdf->Cell(30, 5, '4,612', 1, 1, 'R');



$pdf->Output();
