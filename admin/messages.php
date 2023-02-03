<?php require_once "controller.php"; ?>
<?php 
if(isset($_COOKIE['ad'])){
    $email = $_COOKIE['ad'];
    $admin_id = $_COOKIE['ai'];
}else{
    $email = $_SESSION['email'];
    $admin_id = $_SESSION['admin_id'];
}

if($email != false){
    $sql = "SELECT * FROM admin WHERE email = '$email'";
    $run_Sql = mysqli_query($conn, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
           
        }else{
            header('Location: user-otp');
        }
    }
}else{
    header('Location: login');
}

if(!empty($_GET['user_unique_id'])){
    $user_unique_id = $_GET['user_unique_id'];
    $sql = "SELECT a.user_id, a.user_unique_id, a.phone, a.activity_status, b.lastname, b.firstname 
            FROM users a
            INNER JOIN user_profile b ON a.user_id = b.user_id
            WHERE user_unique_id = '$user_unique_id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        $fetch_info = mysqli_fetch_assoc($result);
        $firstname = $fetch_info['firstname'];
        $lastname = $fetch_info['lastname'];
        $activity_status = $fetch_info['activity_status'];
    }
}

$check_unique = "SELECT * FROM admin WHERE email = '$email'"; 
$unique_res = mysqli_query($conn, $check_unique);
if($unique_res){
    $fetch_row = mysqli_fetch_assoc($unique_res);
    $admin_unique_id = $fetch_row['admin_unique_id'];
    $admin_name = $fetch_row['name'];
    $admin_activity_status = $fetch_row['activity_status'];
}

$outgoing_id = $admin_unique_id;


$inTwoMonths = 60 * 60 * 24 * 60 + time();
setcookie('lastVisit_messages', date("j F Y h:i a"), $inTwoMonths);
?>
<?php include "php/header1.php"; ?>
<title>Messages — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="container-fluid message-area">
        <div class="row" style="height: 100%;">
            <div class="col-12 col-lg-4 order-lg-first line2">
                <section class="users">
                    <header class="py-3">
                        <div class="content">
                            <img src="../images/marikina-city-seal-big.jpg" alt="" class="rounded-circle">
                            <div class="details">
                                <span><?php echo $admin_name?></span><br>
                                <?php echo $admin_activity_status; ?>
                            </div>
                        </div>
                    </header>
                    <div class="search py-3">
                        <span class="text">Select a user to start chat</span>
                        <input type="text" placeholder="Enter name to search...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="users-list"></div>
                </section>
                <script src="../javascript/users.js?v=<?php echo time(); ?>"></script>
            </div>

            <div class="col-12 col-lg-8" style="position: relative">
                <?php if(!empty($_GET['user_unique_id'])){?>
                    <section class="chat-area">
                        <header class="py-3">
                            <?php 
                                $user_unique_id = mysqli_real_escape_string($conn, $_GET['user_unique_id']);
                                $sql = mysqli_query($conn, "SELECT a.user_id, a.user_unique_id, a.phone, a.activity_status, b.lastname, b.firstname 
                                                            FROM users a
                                                            INNER JOIN user_profile b ON a.user_id = b.user_id 
                                                            WHERE user_unique_id = {$user_unique_id}");
                                if(mysqli_num_rows($sql) > 0){
                                    $row = mysqli_fetch_assoc($sql);
                                }else{
                                    header("location: messages");
                                }
                            ?>
                            <a href="messages" class="back-icon"><i class="fas fa-arrow-left" style="line-height: 50px; color: var(--button-color);"></i></a>
                            <img src="../images/marikina-city-seal-big.jpg" alt="">
                            <div class="details">
                                <span><?php echo $row['firstname'].' '.$row['lastname']?></span><br>
                                <?php echo $row['activity_status']; ?>
                            </div>
                        </header>
                        <div class="chat-box" id="chat-box"></div>
                    
                        <form action="#" class="typing-area">
                            <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_unique_id; ?>" hidden>
                            <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                            <button><i class='bx bx-send'></i></button>
                        </form>
                    </section>

                    <script src="../javascript/chat.js?v=<?php echo time(); ?>"></script>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    var width = $(window).width(); 
    var height = $(window).height();
    if (width <= 992){
        if  ($('.chat-area').is(":visible")) {
            $(".users").hide();
            $(".line2").hide();
        }
    }else if (width > 992){
        $('.chat-area').show();
        $(".users").show();
        $(".line2").show();
        
    }
});

 $(document).ready(function(){
    $(window).resize(function(){
        var width = $(window).width(); 
        var height = $(window).height();
        if (width <= 992){
            if  ($('.chat-area').is(":visible")) {
                $(".users").hide();
                $(".line2").hide();
            }
        }else if (width > 992){
            $('.chat-area').show();
            $(".users").show();
            $(".line2").show();
            
        }
    });
});
</script>

<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<!--Container Main end-->
<?php include "php/navigation2.php"; ?>
