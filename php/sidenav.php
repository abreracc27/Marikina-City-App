<?php
if(isset($_COOKIE['ph'])){
    $phone = $_COOKIE['ph'];
    $user_id = $_COOKIE['ui'];
}else{
    $phone = $_SESSION['phone'];
    $user_id = $_SESSION['user_id'];
}

$sql = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
$res = $conn->query($sql);
if(mysqli_num_rows($res) > 0){
    $fetch = mysqli_fetch_assoc($res);
    $lastname = $fetch['lastname'];
    $firstname = $fetch['firstname'];
}
?>
<nav class="sidenav">

    <div class="sidenav__links">
        <span class="sidenav__user"><?php echo $firstname." ".$lastname?></span>
        <span class="sidenav__user_phone"><?php echo $phone;?></span>

        <a href="./profile" class="sidenav__link <?php if(basename($_SERVER['PHP_SELF'])=='profile.php'){echo 'sidenav__link--active';}else{echo '';} ?>">
            <i class="material-icons nav__icon">person</i> 
            Profile
        </a>

        <a href="./manage-account" class="sidenav__link <?php if(basename($_SERVER['PHP_SELF'])=='manage-account.php'){echo 'sidenav__link--active';}else{echo '';} ?>">
            <i class="material-icons nav__icon">account_circle</i>
            Your Account
        </a>
    </div> 

    <div class="sidenav__overlay"></div>

    <script>
        $("#btnSidenav").click(function(){
            $(".sidenav").addClass("sidenav--open");
        });

        $(".sidenav__overlay").click(function(){
            $(".sidenav").removeClass("sidenav--open");
        });
    </script>
</nav>