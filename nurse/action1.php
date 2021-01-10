<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

// Health Library live search
if (isset($_REQUEST["patientBefore"])) {
    // Prepare a select statement
    $sql = "SELECT DISTINCT pName FROM returnee_patient WHERE pName LIKE :name";

    if ($stmt  = $con->prepare($sql)) {
        // Bind Parameters
        $stmt->bindParam(":name", $param_term, PDO::PARAM_STR);

        // Set Parameters
        $param_term = "%" . $_REQUEST["patientBefore"] . "%";

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $resultRow = $stmt->rowCount();

            if ($resultRow > 0) {
                // Fetch result rows as an associative array
                while ($returneePatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="list-group" id="data">
                        <a href="myRecord.php?pName=' . $returneePatient['pName'] . '" class="list-group-item list-group-item-action">' . $returneePatient['pName'] . '</a>
                    </div>
                    ';
                }
            } else {
                echo "<p>No matches found</p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql";
        }
    }
}
