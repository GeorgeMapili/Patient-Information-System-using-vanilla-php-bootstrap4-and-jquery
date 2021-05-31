<?php

require_once '../connect.php';
require('../secretary/fpdf182/fpdf.php');

if(isset($_POST['generate_report'])){

    $starting_month = $_POST['starting_month'];
    $ending_month = $_POST['ending_month'];

    if($starting_month > $ending_month){
        header("location:dashboard.php?error=invalid-input");
        exit;
    }

    

}

class PDF extends FPDF{

    function header(){
        $this->Image('../img/sumc.png', 10, 6, 20);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(276, 5, 'SUMC Medical Report', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);
        $this->Cell(276, 10, 'Dumaguete, Negros Oriental', 0,0, 'C');
        $this->Ln(20);
    }

    function footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    function headerTable($starting_month, $ending_month){
        $this->Cell(276, 10, "$starting_month to $ending_month", 0,0, 'C');
        $this->Ln(20);
        $this->SetFont('Times', 'B', 12);
        $this->Cell(60, 10, 'Type of Patient', 1, 0, 'C');
        $this->Cell(30, 10, 'Total Patient', 1, 0, 'C');
        $this->Cell(35, 10, 'Expired Status', 1, 0, 'C');
        $this->Cell(35, 10, 'Awful Status', 1, 0, 'C');
        $this->Cell(36, 10, 'Good Status', 1, 0, 'C');
        $this->Cell(40, 10, 'Better Status', 1, 0, 'C');
        $this->Cell(40, 10, 'Total Revenue', 1, 0, 'C');
        $this->Ln();
    }

    function viewTable($con, $starting_month, $ending_month){
        $this->SetFont('Times', '', 12);
        $sql = "SELECT * FROM discharged_patient WHERE pMadeOn BETWEEN :starting_month AND :ending_month";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
        $stmt->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
        $stmt->execute();

        $totalPatient = $stmt->rowCount();
        $expired = 0;
        $awful = 0;
        $good = 0;
        $better = 0;
        $total_revenue = 0;

        while($walkin_discharged = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            if($walkin_discharged['pStatus'] == "expired"){
                $expired++;
            }elseif($walkin_discharged['pStatus'] == "awful"){
                $expired++;
            }elseif($walkin_discharged['pStatus'] == "good"){
                $good++;
            }elseif($walkin_discharged['pStatus'] == "better"){
                $better++;
            }

            $total_revenue += $walkin_discharged['pTotalAmount'];

        }

        $this->Cell(60, 10, 'Discharged Walkin Patient', 1, 0, 'C');
        $this->Cell(30, 10, $totalPatient, 1, 0, 'C');
        $this->Cell(35, 10, $expired, 1, 0, 'C');
        $this->Cell(35, 10, $awful, 1, 0, 'C');
        $this->Cell(36, 10, $good, 1, 0, 'C');
        $this->Cell(40, 10, $better, 1, 0, 'C');
        $this->Cell(40, 10, "Php " .number_format($total_revenue,2), 1, 0, 'C');
        $this->Ln();

        $status = "discharged";
        $sql = "SELECT * FROM appointment WHERE aStatus = :aStatus AND aDate BETWEEN :starting_month AND :ending_month";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":aStatus", $status, PDO::PARAM_STR);
        $stmt->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
        $stmt->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
        $stmt->execute();

        $totalPatient = $stmt->rowCount();
        $expired = 0;
        $awful = 0;
        $good = 0;
        $better = 0;
        $total_revenue = 0;

        while($patient_discharged = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            if($patient_discharged['patientStatus'] == "expired"){
                $expired++;
            }elseif($patient_discharged['patientStatus'] == "awful"){
                $expired++;
            }elseif($patient_discharged['patientStatus'] == "good"){
                $good++;
            }elseif($patient_discharged['patientStatus'] == "better"){
                $better++;
            }

            $total_revenue += $patient_discharged['pTotalPay'];

        }

        $this->Cell(60, 10, 'Discharge Appointment Patient', 1, 0, 'C');
        $this->Cell(30, 10, $totalPatient, 1, 0, 'C');
        $this->Cell(35, 10, $expired, 1, 0, 'C');
        $this->Cell(35, 10, $awful, 1, 0, 'C');
        $this->Cell(36, 10, $good, 1, 0, 'C');
        $this->Cell(40, 10, $better, 1, 0, 'C');
        $this->Cell(40, 10, "Php " .number_format($total_revenue,2), 1, 0, 'C');

    }

    function viewDoctor($con, $starting_month, $ending_month){

        $this->SetFont('Times', 'B', 12);
        $this->Cell(69, 10, 'Doctor Name', 1, 0, 'C');
        $this->Cell(69, 10, 'Discharged Walkin Patient', 1, 0, 'C');
        $this->Cell(69, 10, 'Discharged Patient Appointment', 1, 0, 'C');
        $this->Cell(69, 10, 'Cancelled Appointment', 1, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);

        $sql = "SELECT * FROM doctor";
        $stmt = $con->prepare($sql);
        $stmt->execute();

        while($doctor_data = $stmt->fetch(PDO::FETCH_ASSOC)){

            $doctor_name = $doctor_data['dName'];

            $sql = "SELECT * FROM discharged_patient WHERE pDoctor = :doctor_name AND pMadeOn BETWEEN :starting_month AND :ending_month";
            $stmt1 = $con->prepare($sql);
            $stmt1->bindParam(":doctor_name", $doctor_name, PDO::PARAM_STR);
            $stmt1->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
            $stmt1->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
            $stmt1->execute();

            $discharged_walkin = $stmt1->rowCount();

            $status1 = "discharged";
            $status2 = "cancelled";

            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor_name AND aStatus = :status1 AND aDate BETWEEN :starting_month AND :ending_month";
            $stmt2 = $con->prepare($sql);
            $stmt2->bindParam(":doctor_name", $doctor_name, PDO::PARAM_STR);
            $stmt2->bindParam(":status1", $status1, PDO::PARAM_STR);
            $stmt2->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
            $stmt2->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
            $stmt2->execute();

            $appointment_discharged = $stmt2->rowCount();

            $sql = "SELECT * FROM appointment WHERE pDoctor = :doctor_name AND aStatus = :status2 AND aDate BETWEEN :starting_month AND :ending_month";
            $stmt3 = $con->prepare($sql);
            $stmt3->bindParam(":doctor_name", $doctor_name, PDO::PARAM_STR);
            $stmt3->bindParam(":status2", $status2, PDO::PARAM_STR);
            $stmt3->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
            $stmt3->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
            $stmt3->execute();

            $appointment_cancelled = $stmt3->rowCount();

            $this->Cell(69, 10, $doctor_name, 1, 0, 'C');
            $this->Cell(69, 10, $discharged_walkin, 1, 0, 'C');
            $this->Cell(69, 10, $appointment_discharged, 1, 0, 'C');
            $this->Cell(69, 10, $appointment_cancelled, 1, 0, 'C');
            $this->Ln();

        }


    }

    function viewSecretary($con, $starting_month, $ending_month){

        $this->SetFont('Times', 'B', 12);
        $this->Cell(138, 10, 'Total Discharged Patient Appointment', 1, 0, 'C');
        $this->Cell(138, 10, 'Total Cancelled Patient Appointment', 1, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);

        $accepted = 0;
        $cancelled = 0;

        $sql = "SELECT * FROM appointment WHERE aDate BETWEEN :starting_month AND :ending_month";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":starting_month", $starting_month, PDO::PARAM_STR);
        $stmt->bindParam(":ending_month", $ending_month, PDO::PARAM_STR);
        $stmt->execute();

        while($data = $stmt->fetch(PDO::FETCH_ASSOC)){

            if($data['aStatus'] == "discharged"){
                $accepted++;
            }elseif($data['aStatus'] == "cancelled"){
                $cancelled++;
            }

        }

        $this->Cell(138, 10, $accepted, 1, 0, 'C');
        $this->Cell(138, 10, $cancelled, 1, 0, 'C');
        $this->Ln();


    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetTitle('SUMC Medical Report');
$pdf->AddPage('L', 'A4', 0);
$pdf->headerTable($starting_month, $ending_month);
$pdf->viewTable($con, $starting_month, $ending_month);
$pdf->Ln(20);
$pdf->viewDoctor($con, $starting_month, $ending_month);
$pdf->Ln(10);
$pdf->viewSecretary($con, $starting_month, $ending_month);
$pdf->Ln(10);
$pdf->Output();
