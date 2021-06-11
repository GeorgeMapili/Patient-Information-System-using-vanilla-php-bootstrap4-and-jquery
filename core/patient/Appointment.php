<?php

namespace core\patient;
require_once(dirname(dirname(__DIR__)). "/vendor/autoload.php");
use PDO;
use core\db\Database;

class Appointment extends Database
{

    public $pId;
    public $pName;
    public $pEmail;
    public $pAddress;
    public $pMobile;
    public $pDoctor;
    public $aDate;
    public $aTime;
    public $aReason;

    public function requireAllFields()
    {
        if(empty($this->pDoctor) || empty($this->aDate) || empty($this->aTime) || empty($this->aReason)){
            header("location:appointment.php?errRequired=require_all_fields");
            exit(0);
        }
    }

    public function datePassByCheck()
    {

        // Check if the Date is already pass by
        if (date("M d, Y", strtotime($this->aDate)) < date("M d, Y", strtotime('now'))) {
            header("location:appointment.php?errDate=date_already_pass_by");
            exit(0);
        }
        return "valid_date";
    
    }

    public function noWeekEndServiceCheck()
    {

        // Check if the date is a weekend
        $weekDay = date('w', strtotime($this->aDate));

        if($weekDay == 0){
            header("location:appointment.php?errDate1=no_sundays");
            exit(0);
        }
        return "valid_date";

    }

    public function alreadyHaveAnAppointDiffDoctor()
    {

        // Check if already have an appointment with different doctor but the same time ||You have already an appointment in that time with different doctor
        $status1 = "pending";
        $status2 = "accepted";
        $status3 = "done";
        $sql = "SELECT * FROM appointment WHERE pName = :name AND aDate=:date AND aTime = :time AND pDoctor <> :doctor AND aStatus IN(:status1,:status2,:status3)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":name", $_SESSION['name'], PDO::PARAM_STR);
        $stmt->bindParam(":date", $this->aDate, PDO::PARAM_STR);
        $stmt->bindParam(":time", $this->aTime, PDO::PARAM_STR);
        $stmt->bindParam(":doctor", $this->pDoctor, PDO::PARAM_STR);
        $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
        $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
        $stmt->bindParam(":status3", $status3, PDO::PARAM_STR);
        $stmt->execute();

        $sameTimeWithDiffentDoctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sameTimeWithDiffentDoctor > 0) {
            header("location:appointment.php?errDup=you_have_already_an_appointment_in_that_time_with_different_doctor");
            exit(0);
        }
        return "valid_appointment";

    }

    public function duplicateAppointmentCheck()
    {

        // Check if it is a dubplicate appointment with the same user patient
        $status1 = "pending";
        $status2 = "accepted";
        $status3 = "done";
        $sql = "SELECT * FROM appointment WHERE pId = :id AND pDoctor = :doctor AND aDate =:date AND aTime = :time AND aStatus IN(:status1,:status2,:status3)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $this->pId, PDO::PARAM_INT);
        $stmt->bindParam(":doctor", $this->pDoctor, PDO::PARAM_STR);
        $stmt->bindParam(":date", $this->aDate, PDO::PARAM_STR);
        $stmt->bindParam(":time", $this->aTime, PDO::PARAM_STR);
        $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
        $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
        $stmt->bindParam(":status3", $status3, PDO::PARAM_STR);
        $stmt->execute();

        $duplicateRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($duplicateRow > 0) {
            header("location:appointment.php?errDup1=you_already_made_an_appointment");
            exit(0);
        }
        return "valid_appointment";

    }

    public function sameDateAppointCheck()
    {

        // Check if it is already requested an appointment with the same date and time
        $status1 = "pending";
        $status2 = "accepted";
        $status3 = "done";
        $sql = "SELECT * FROM appointment WHERE pId <> :id AND pDoctor = :doctor AND aDate =:date AND aTime = :time AND aStatus IN(:status1,:status2,:status3)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $this->pId, PDO::PARAM_INT);
        $stmt->bindParam(":doctor", $this->pDoctor, PDO::PARAM_STR);
        $stmt->bindParam(":date", $this->aDate, PDO::PARAM_STR);
        $stmt->bindParam(":time", $this->aTime, PDO::PARAM_STR);
        $stmt->bindParam(":status1", $status1, PDO::PARAM_STR);
        $stmt->bindParam(":status2", $status2, PDO::PARAM_STR);
        $stmt->bindParam(":status3", $status3, PDO::PARAM_STR);
        $stmt->execute();

        $duplicateRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($duplicateRow > 0) {
            header("location:appointment.php?errTime=all_ready_taken_time");
            exit(0);
        }
        return "valid_appointment";

    }


}

