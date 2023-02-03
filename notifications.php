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


$sql = "SELECT * FROM user_notification WHERE user_id='$user_id' OR user_id = 'all' ORDER BY datetime DESC";
$result = $conn->query($sql);
?>
<?php include "php/header.php"; ?>
<body>

<header class="header">
    <div style="width: 45px; text-align: center;">
        <button id="btnSidenav" style="width: 45px; border: none; background: transparent;">
            <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">manage_accounts</i>
        </button>
    </div>  
</header>     

<?php include "php/sidenav.php"; ?>

<div class="main-content message-area" style="position: relative;">
    <div class="center-area2" style="max-height:100%; overflow-x:hidden;">
        <div class="row pt-4">
            <div class="col-12 col-lg-12">
                <span class="login-text">Notifications</span>
            </div>
        </div>
    <?php if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="row notification-container mt-3 mb-2">
                <span class="notification">
                    <div class="frame">
                        <img src="images/marikina-city-seal-big.jpg" alt="" width="50" height="100%" class="rounded-circle" loading="lazy">
                    </div>
                    <div style="line-height: 25px; margin-left: 15px;">
                        <?php 
                        
                        if($row['type'] == 'report_invalid'){
                            echo "<b>Marikina Health & Safety</b> marked your COVID-19 report as <b>INVALID</b> due to the following reason/s:<br>";
                            echo "<ul>";
                            $reasons = explode(".", $row['notification']);
                            foreach($reasons as $reason){
                                if(!empty($reason))
                                    echo "<li><b>".$reason . "</b></li>";
                            }
                            echo "</ul>";
                        }elseif($row['type'] == 'report_success'){
                            echo "Your COVID-19 report has been <b>RECEIVED</b>. Please expect to be contacted by local first responders."."<br>";
                        }elseif($row['type'] == 'new_announcement'){
                            echo "A new announcement has been posted."."<br>";
                        }elseif($row['type'] == 'report_deleted'){
                            echo "Your COVID-19 report has been <b>DISCONTINUED</b> due to the following reason/s:<br>";
                            echo "<ul>";
                            echo "<li><b>Incomplete details (Primary Emergency Contact)</b></li>";
                            echo "</ul>";
                        }elseif($row['type'] == 'proof_deleted'){
                            echo "Your COVID-19 report has been <b>DISCONTINUED</b> due to the following reason/s:<br>";
                            echo "<ul>";
                            echo "<li><b>Incomplete details (COVID-19 test result)</b></li>";
                            echo "</ul>";
                        }
                        ?>

                        <span class="announcement-info"><?php echo date('j M Y \a\t g:i A', $row['datetime']);?></span>
                    </div>
                </span>
            </div>
        <?php
        }
    }else{
        echo "<div style='line-height: calc(100vh - 154px); vertical-align: middle; text-align: center;'>You have no notifications to display.</div>";
    }?>
    </div>
</div>

<br/><br/>

<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->
  
</body>

<script src="javascript/readMoreJS.min.js?v=<?php echo time(); ?>"></script>

<script>
    $readMoreJS.init({
        target: '.dummy span',           // Selector of the element the plugin applies to (any CSS selector, eg: '#', '.'). Default: ''
        numOfWords: 21,               // Number of words to initially display (any number). Default: 50
        toggle: true,                 // If true, user can toggle between 'read more' and 'read less'. Default: true
        moreLink: 'Read more',    // The text of 'Read more' link. Default: 'read more ...'
        lessLink: 'Read less'         // The text of 'Read less' link. Default: 'read less'
    });
</script>
</html>
