<?php
session_start();
require_once '../connect.php';


if (isset($_POST['sortBy'])) {

    $sortBy = $_POST['sortBy'];

    $output = '';

    switch ($sortBy) {

            // DEFAULT
        case 'default':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE aStatus = :status";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($default = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $default['pName'] . '</td>
                    <td>' . $default['pAddress'] . '</td>
                    <td>' . $default['pMobile'] . '</td>
                    <td>' . $default['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($default['aDate'])) . "at" . $default['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $default['aId'] . '>
                            <input type="hidden" name="pId" value=' . $default['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $default['aId'] . '>
                        <input type="hidden" name="pId" value=' . $default['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // TODAY
        case 'today':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE aStatus = :status AND aDate = DATE(NOW()) ORDER BY aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($today = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $today['pName'] . '</td>
                    <td>' . $today['pAddress'] . '</td>
                    <td>' . $today['pMobile'] . '</td>
                    <td>' . $today['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($today['aDate'])) . " at " . $today['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $today['aId'] . '>
                            <input type="hidden" name="pId" value=' . $today['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $today['aId'] . '>
                        <input type="hidden" name="pId" value=' . $today['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // TOMORROW 
        case 'tomorrow':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE aStatus = :status AND aDate = curdate() + interval 1 day ORDER BY aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($tomorrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $tomorrow['pName'] . '</td>
                    <td>' . $tomorrow['pAddress'] . '</td>
                    <td>' . $tomorrow['pMobile'] . '</td>
                    <td>' . $tomorrow['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($tomorrow['aDate'])) . " at " . $tomorrow['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $tomorrow['aId'] . '>
                            <input type="hidden" name="pId" value=' . $tomorrow['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $tomorrow['aId'] . '>
                        <input type="hidden" name="pId" value=' . $tomorrow['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // THIS WEEK
        case 'this_week':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE aStatus = :status AND YEARWEEK(aDate) = YEARWEEK(NOW()) ORDER BY aDate,aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($thisWeek = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $thisWeek['pName'] . '</td>
                    <td>' . $thisWeek['pAddress'] . '</td>
                    <td>' . $thisWeek['pMobile'] . '</td>
                    <td>' . $thisWeek['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($thisWeek['aDate'])) . " at " . $thisWeek['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $thisWeek['aId'] . '>
                            <input type="hidden" name="pId" value=' . $thisWeek['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $thisWeek['aId'] . '>
                        <input type="hidden" name="pId" value=' . $thisWeek['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // NEXT WEEK
        case 'next_week':
            $status = "accepted";
            $sql = "SELECT * FROM appointment WHERE aStatus = :status AND YEARWEEK(aDate) = YEARWEEK(NOW() + INTERVAL 7 DAY) ORDER BY aDate, aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($nextWeek = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $nextWeek['pName'] . '</td>
                    <td>' . $nextWeek['pAddress'] . '</td>
                    <td>' . $nextWeek['pMobile'] . '</td>
                    <td>' . $nextWeek['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($nextWeek['aDate'])) . " at " . $nextWeek['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $nextWeek['aId'] . '>
                            <input type="hidden" name="pId" value=' . $nextWeek['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $nextWeek['aId'] . '>
                        <input type="hidden" name="pId" value=' . $nextWeek['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

            // THIS MONTH
        case 'this_month':
            $status = "accepted";
            $month = date("m");

            $sql = "SELECT * FROM appointment WHERE aStatus = :status AND EXTRACT(MONTH FROM aDate) = :months ORDER BY aDate, aTime ASC";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":months", $month, PDO::PARAM_INT);
            $stmt->execute();

            $output .= '
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient Address</th>
                    <th scope="col">Patient Mobile</th>
                    <th scope="col">Appointment Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Updated Disease</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            ';

            while ($thisMonth = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $output .= '
                <tr>
                    <td>' . $thisMonth['pName'] . '</td>
                    <td>' . $thisMonth['pAddress'] . '</td>
                    <td>' . $thisMonth['pMobile'] . '</td>
                    <td>' . $thisMonth['aReason'] . '</td>
                    <td>' . date("M d, Y", strtotime($thisMonth['aDate'])) . " at " . $thisMonth['aTime'] . '</td>
                    <td>
                        <form action="updateDisease.php" method="post">
                            <input type="hidden" name="aId" value=' . $thisMonth['aId'] . '>
                            <input type="hidden" name="pId" value=' . $thisMonth['pId'] . '>
                            <input type="submit" value="Update Disease" class="btn btn-info" name="updateDisease">
                        </form>
                    </td>
                    <td>
                    <form action="incomingAppointment.php" method="post">
                        <input type="hidden" name="aId" value=' . $thisMonth['aId'] . '>
                        <input type="hidden" name="pId" value=' . $thisMonth['pId'] . '>
                        <input type="submit" value="Done" class="btn btn-success" name="doneAppointment">
                    </form>
                    </td>
                </tr>
                </tbody>
                ';
            }
            echo $output;
            break;

        default:
            # code...
            break;
    }
} else {
    echo '<h3 style="color:red">No Patient Found</h3>';
}
