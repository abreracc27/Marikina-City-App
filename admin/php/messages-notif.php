<?php require_once "../controller.php"; ?>
<?php

if(isset($_COOKIE['lastVisit_messages'])){
    $visit = $_COOKIE['lastVisit_messages'];
}

$visit_unix = strtotime($visit);
$admin_unique_id = $_SESSION['admin_unique_id'];

$sql = "SELECT COUNT(*) AS new_messages FROM messages WHERE incoming_msg_id = '$admin_unique_id' AND datetime > '$visit_unix'";
$result = $conn->query($sql);

$resultStr="[";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $resultStr.="{\"new_messages\":".$row["new_messages"]."},";
    }
    $resultStr=substr($resultStr,0,strlen($resultStr)-1)."]";
    echo $resultStr;
}
$conn->close();
?>
