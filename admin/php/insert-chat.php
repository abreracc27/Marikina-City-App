<?php 
    date_default_timezone_set("Asia/Manila");
    session_start();

    include_once "../connection.php";
    
    if(isset($_COOKIE['ad'])){
        $email = $_COOKIE['ad'];
        $admin_id = $_COOKIE['ai'];
    }else{
        $email = $_SESSION['email'];
        $admin_id = $_SESSION['admin_id'];
    }

    $check_unique = "SELECT * FROM admin WHERE email = '$email'"; 
    $unique_res = mysqli_query($conn, $check_unique);
    if($unique_res){
        $fetch_row = mysqli_fetch_assoc($unique_res);
        $admin_unique_id = $fetch_row['admin_unique_id'];
    }

    $outgoing_id = $admin_unique_id;
    $datetime = strtotime(date("Y-m-d H:i:s"));
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if(!empty($message)){
        $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, datetime)
                                    VALUES ('$incoming_id', '$outgoing_id', '$message', '$datetime')") or die();
    }



    
?>