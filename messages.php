<?php require_once "controller.php"; ?>
<?php
if(isset($_COOKIE['ph'])){
    $phone = $_COOKIE['ph'];
    $user_id = $_COOKIE['ui'];
}else{
    $phone = $_SESSION['phone'];
    $user_id = $_SESSION['user_id'];
}
if($phone != false){
    $sql = "SELECT * FROM users WHERE phone = '$phone'";
    $run_sql = mysqli_query($conn, $sql);
    if($run_sql){
        $fetch_info = mysqli_fetch_assoc($run_sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            $sql = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
            $run_sql = mysqli_query($conn, $sql);
            if(mysqli_num_rows($run_sql) == 0){
                header('location: profile-setup');
            }
        }else{
            header('Location: user-otp');
        }
    }
}else{
    header('Location: login');
}

if(!empty($_GET['admin_unique_id'])){
    $admin_unique_id = $_GET['admin_unique_id'];
    $sql = "SELECT * FROM admin WHERE admin_unique_id = '$admin_unique_id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        $fetch_info = mysqli_fetch_assoc($result);
        $admin_name = $fetch_info['name'];
        $activity_status = $fetch_info['activity_status'];
    }
}

$check_unique = "SELECT * FROM users WHERE user_id = '$user_id'"; 
$unique_res = mysqli_query($conn, $check_unique);
if($unique_res){
    $fetch_row = mysqli_fetch_assoc($unique_res);
    $user_unique_id = $fetch_row['user_unique_id'];
}
    


$sql2 = "SELECT a.user_id, a.user_unique_id, a.phone, a.activity_status, b.lastname, b.firstname
        FROM users a
        INNER JOIN user_profile b ON a.user_id = b.user_id
        WHERE a.user_unique_id = '$user_unique_id'";
$result2 = mysqli_query($conn, $sql2);

if($result2){
    $fetch_info = mysqli_fetch_assoc($result2);
    $user_name = $fetch_info['firstname']. ' '. $fetch_info['lastname'];
    $user_activity_status = $fetch_info['activity_status'];
}

$inTwoMonths = 60 * 60 * 24 * 60 + time();
setcookie('lastVisit_messages', date("j F Y h:i a"), $inTwoMonths);
?>
<?php include "php/header.php"; ?>
<body>

<div class="main-content2 container-fluid message-area2">
    <div class="row" style="height: 100%;">
        <div class="col-12 col-lg-4 order-lg-first line2">
            <section class="users">
                <header class="py-3">
                    <div class="content">
                        <img src="images/default-profile.png" alt="" class="rounded-circle">
                        <div class="details">
                            <span><?php echo $user_name?></span><br>
                            <?php echo $user_activity_status; ?>
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
            <script src="javascript/users.js?v=<?php echo time(); ?>"></script>
        </div>

        <div class="col-12 col-lg-8" style="position: relative">
            <?php if(!empty($_GET['admin_unique_id'])){?>
                <section class="chat-area">
                    <header class="py-3">
                        <?php 
                            $admin_unique_id = mysqli_real_escape_string($conn, $_GET['admin_unique_id']);
                            $sql = mysqli_query($conn, "SELECT * FROM admin WHERE admin_unique_id = {$admin_unique_id}");
                            if(mysqli_num_rows($sql) > 0){
                                $row = mysqli_fetch_assoc($sql);
                            }else{
                                header("location: messages");
                            }
                        ?>
                        <a href="messages" class="back-icon"><i class="font-size: 16px !important; fas fa-arrow-left" style="line-height: 50px; color: var(--button-color);"></i></a>
                        <img src="images/marikina-city-seal-big.jpg" alt="">
                        <div class="details">
                            <span><?php echo $row['name']?></span><br>
                            <?php echo $row['activity_status']; ?>
                        </div>
                    </header>
                    <div class="chat-box" id="chat-box"></div>
                    
                    <form action="#" class="typing-area">
                        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $admin_unique_id; ?>" hidden>
                        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                        <button><i class='bx bx-send'></i></button>
                    </form>
                </section>

                <script src="javascript/chat.js?v=<?php echo time(); ?>"></script>
            <?php }?>
        </div>
    </div>
</div>
<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->
  
</body>

<script>
$(document).ready(function(){
    var width = $(window).width(); 
    var height = $(window).height();
    if (width <= 992){
        if  ($('.chat-area').is(":visible")) {
            $(".users").hide();
            $(".line2").hide();
            // $(".nav").hide();
        }
    }else if (width > 992){
        $('.chat-area').show();
        $(".users").show();
        $(".line2").show();
        // $(".nav").show();
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
                // $(".nav").hide();
            }
        }else if (width > 992){
            $('.chat-area').show();
            $(".users").show();
            $(".line2").show();
            // $(".nav").show();  
        }
    });
});
</script>
</html>
