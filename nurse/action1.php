<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}
// AJAX SEARCH ROOM QUERY

$roomOutput = '';

if (isset($_POST['roomQuery'])) {
    $search = trim(htmlspecialchars($_POST['roomQuery']));

    $searchRoom = "%" . $search . "%";

    $sql = "SELECT * FROM rooms WHERE room_number LIKE :search";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":search", $searchRoom, PDO::PARAM_STR);
    $stmt->execute();

    $roomCount = $stmt->rowCount();

    if ($roomCount > 0) {
        $roomOutput .= '
        <thead class="thead-dark">
            <tr>
                <th scope="col">Room Number</th>
                <th scope="col">Room Fee</th>
                <th scope="col">Room Status</th>
            </tr>
        </thead>
        <tbody>
            ';
        while ($roomPatient = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roomOutput .= '
            <tr>
            <th scope="row">' . $roomPatient['room_number'] . '</th>
            <td>₱' . number_format($roomPatient['room_fee'], 2) . '</td>
            <td>';

            if ($roomPatient['room_status'] == "available") { ?>
                <?php $roomOutput .= '
                <form action="addPatient.php" method="post">
                    <input type="hidden" name="roomNumber" value=' . $roomPatient['room_number'] . '>
                    <input type="submit" class="btn btn-success" value="Available" name="availableRoom">
                </form>
                ';
                ?>
            <?php
            } else {
            ?>
                <?php $roomOutput .= '
            <form action="occupiedBy.php" method="post">
                <input type="hidden" name="roomNumber" value=' . $roomPatient['room_number'] . '>
                <input type="submit" class="btn btn-danger" value="Occupied" name="occupiedRoom">
            </form>
            ';
                ?> 
            <?php
            }

            $roomOutput .= '</td>
        </tr>
            ';
        }
        $roomOutput .= '</tbody>';

        echo $roomOutput;
    } else {
        echo '<h3 style="color:red">No Room Found</h3>';
    }
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
