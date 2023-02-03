<?php 
    session_start();
    date_default_timezone_set("Asia/Manila");

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
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";
    
    $sql = "SELECT * FROM messages LEFT JOIN admin ON admin.admin_unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
    $query = mysqli_query($conn, $sql);

    function plain_url_to_link($message) {
        return preg_replace(
        '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', 
        '<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $message);
    }

    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            if($row['outgoing_msg_id'] === $outgoing_id){
                $message = $row['msg'];
                $datetime = $row['datetime'];
                $today = strtotime(date("Y-m-d"));
                $thisweek = strtotime('-1 week', $today);
                $thisyear = strtotime(date("Y-01-01"));

                if($thisweek < $datetime && $datetime < $today)
                    $date = date("D \a\\t g:i A", $datetime); // WITHIN 7 DAYS FROM TODAY
                elseif($datetime < $thisweek && $datetime > $thisyear)
                    $date = date("M j \a\\t g:i A", $datetime); // MORE THAN 7 DAYS FROM TODAY
                elseif($datetime < $thisyear)
                    $date = date("M j, Y \a\\t g:i A", $datetime); //BEFORE THIS YEAR
                else
                    $date = date("g:i A", $datetime); //

                $output .= '<div style="text-align: center">
                                '.$date.'
                            </div>
                            <div class="chat outgoing">
                                <div class="details">
                                    <p>'. plain_url_to_link($message) .'</p>
                                </div>
                            </div>';
            }else{
                $message = $row['msg'];
                $datetime = $row['datetime'];
                $today = strtotime(date("Y-m-d"));
                $thisweek = strtotime('-1 week', $today);
                $thisyear = strtotime(date("Y-01-01"));

                if($thisweek < $datetime && $datetime < $today)
                    $date = date("D \a\\t g:i A", $datetime); // WITHIN 7 DAYS FROM TODAY
                elseif($datetime < $thisweek && $datetime > $thisyear)
                    $date = date("M j \a\\t g:i A", $datetime); // MORE THAN 7 DAYS FROM TODAY
                elseif($datetime < $thisyear)
                    $date = date("M j, Y \a\\t g:i A", $datetime); //BEFORE THIS YEAR
                else
                    $date = date("g:i A", $datetime); //
                    
                $output .= '<div style="text-align: center">
                                '.$date.'
                            </div>
                            <div class="chat incoming">
                                <img src="../images/default-profile.png" alt="" class="rounded-circle">
                                <div class="details">
                                    <p>'. plain_url_to_link($message) .'</p>
                                </div>
                            </div>';
            }
        }
    }else{
        $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
    }
    echo $output;

?>