<?php
    session_start();
    include_once "../connection.php";
    date_default_timezone_set("Asia/Manila");
    
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

    $sql2 = "SELECT a.user_id, a.user_unique_id, a.phone, a.activity_status, b.lastname, b.firstname 
            FROM users a
            INNER JOIN user_profile b ON a.user_id = b.user_id";
    $query = mysqli_query($conn, $sql2);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['user_unique_id']}
                    OR outgoing_msg_id = {$row['user_unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                    OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
            $query2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($query2);
            if(mysqli_num_rows($query2) > 0){
                $result = $row2['msg'];

                $datetime = $row2['datetime'];
                $today = strtotime(date("Y-m-d"));
                $thisweek = strtotime('-1 week', $today);
                $thisyear = strtotime(date("Y-01-01"));

                if($thisweek < $datetime && $datetime < $today)
                    $date = date("D", $datetime); // WITHIN 7 DAYS FROM TODAY
                elseif($datetime < $thisweek && $datetime > $thisyear)
                    $date = date("M j", $datetime); // MORE THAN 7 DAYS FROM TODAY
                elseif($datetime < $thisyear)
                    $date = date("M j, Y", $datetime); //BEFORE THIS YEAR
                else
                    $date = date("g:i A", $datetime); //
            }
            else{
                $result ="No message available";
            }


            if(strlen($result) > 28){
                $msg =  substr($result, 0, 28) . '...';
            }
            else{
                $msg = $result;  
            }


            if(isset($row2['outgoing_msg_id'])){
                ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
            }else{
                $you = "";
            }
            ($row['activity_status'] == "Offline") ? $offline = "offline" : $offline = "";
            ($outgoing_id == $row['user_unique_id']) ? $hid_me = "hide" : $hid_me = "";
    
            if(!empty($row2['msg'])){
            $output .= '<a href="messages?user_unique_id='. $row['user_unique_id'] .'">
                        <div class="content">
                        <img src="../images/default-profile.png" alt="" class="rounded-circle">
                        <div class="details">
                            <span>'. $row['firstname'].' '. $row['lastname'].'</span>
                            <p>'. $you . $msg . " &middot; ".$date.'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
            }
        }
    }
    echo $output;
?>