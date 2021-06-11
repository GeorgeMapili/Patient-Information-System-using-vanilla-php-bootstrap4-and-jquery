<?php

session_start();
require_once 'connect.php';

// Health Library live search
if (isset($_REQUEST["term"])) {
    // Prepare a select statement
    $sql = "SELECT * FROM `diseases_treatment` WHERE dtName LIKE :name";

    if ($stmt  = $con->prepare($sql)) {
        // Bind Parameters
        $stmt->bindParam(":name", $param_term, PDO::PARAM_STR);

        // Set Parameters
        $param_term = "%" . $_REQUEST["term"] . "%";

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $resultRow = $stmt->rowCount();

            if ($resultRow > 0) {
                // Fetch result rows as an associative array
                while ($diseaseTreatment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="list-group" id="data">
                        <form action="health-information.php" method="post">
                            <input type="hidden" name="id" value="'. $diseaseTreatment['dtId'] .'">
                            <input type="submit" class="list-group-item list-group-item-action" name="healthLibraryBtn" value="'. $diseaseTreatment['dtName'] .'">
                        </form>
                    </div>
                    ';
                }
            } else {
                echo "<p class='text-white'>No matches found</p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql";
        }
    }
}else{
    header("location:index.php");
    exit;
}
