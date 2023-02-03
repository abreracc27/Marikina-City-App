<?php require_once "../controller.php"; ?>
<?php

if(isset($_COOKIE['lastVisit_user_assistance'])){
    $visit = $_COOKIE['lastVisit_user_assistance'];
    $visit_unix = strtotime($visit);
}

$sql = "SELECT COUNT(*) AS new_reports FROM user_assistance WHERE datetime > '$visit_unix' AND report_status = 'unsettled'";
$result = $conn->query($sql);

$resultStr="[";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $resultStr.="{\"new_reports\":".$row["new_reports"]."},";
    }
    $resultStr=substr($resultStr,0,strlen($resultStr)-1)."]";
    echo $resultStr;
}
$conn->close();
?>
