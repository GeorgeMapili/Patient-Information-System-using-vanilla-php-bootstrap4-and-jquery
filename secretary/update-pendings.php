<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['nId'])) {
    header("location:index.php");
    exit(0);
}

if(isset($_POST['data_set'])){

    $status = "pending";
    $sql = "SELECT * FROM appointment WHERE aStatus = :status";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->execute();

    $res = '';

    if($stmt->rowCount() > 0){

        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            $res .= '
                    <tr>
                        <td>' . $result['pName'] . '</td>
                        <td>' . $result['pAddress'] . '</td>
                        <td>' . $result['pMobile'] . '</td>
                        <td>' . $result['pDoctor'] . '</td>
                        <td>' . $result['aReason'] . '</td>
                        <td>' . date("M d, Y", strtotime($result['aDate'])). " at ".$result['aTime'] . '</td>
                        <td>
                            <div class="row">
                                <div class="col">
                                    <form action="pendings.php" method="post">
                                        <input type="hidden" name="aId" value="'. $result['aId'] .'">
                                        <input type="hidden" name="pId" value="'. $result['pId'] .'">
                                        <input type="submit" value="Accept" class="btn btn-info" name="acceptStatus">
                                    </form>
                                </div>
                                <div class="col">
                                    <form action="pendings.php" method="post">
                                        <input type="hidden" name="aId" value="'. $result['aId'] .'">
                                        <input type="hidden" name="pId" value="'. $result['pId'] .'">
                                        <input type="submit" value="Cancel" class="btn btn-danger" name="cancelStatus" onclick="return confirm(\'Are you sure?\')">
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    ';
        }

    }else{

        $res .= '<p class="lead text-center text-white display-4">No request appointments yet</p>';

    }

    echo $res;

}else{
    header("location:dashboard.php");
    exit;
}

