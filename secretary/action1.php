<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

// Health Library live search
if (isset($_REQUEST["patientBefore"])) {

    $_REQUEST["patientBefore"] = htmlspecialchars($_REQUEST["patientBefore"]);

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
                        <form action="myRecord.php" method="post">
                            <input type="hidden" name="pName" value="'. $returneePatient['pName'] .'">
                            <input type="submit" class="list-group-item list-group-item-action" value="'. $returneePatient['pName'] .'" name="healthLibraryBtn">
                        </form>
                    </div>
                    ';
                }
            } else {
                echo "<p class='text-danger'>No matches found</p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql";
        }
    }
}
