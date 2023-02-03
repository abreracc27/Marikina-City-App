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

$check_unique = "SELECT * FROM admin WHERE email = '$email'"; 
$unique_res = mysqli_query($conn, $check_unique);
if($unique_res){
    $fetch_row = mysqli_fetch_assoc($unique_res);
    $name = $fetch_row['name'];
    $admin_id = $fetch_row['admin_id'];
}

?>
<?php include "php/header1.php"; ?>
<title>Profile — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<?php include "php/alert-message.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="row profile-form p-3">
        <div class="col-12 col-lg-6 order-lg-first line">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label for="username" class="label-text">Username</label>
                        <input id="username" type="text" name="name" class="login-credentials" value="<?php echo $name?>" placeholder="Enter username" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                        <input class="login-button"  value="Change Username" name="save" type="submit">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-lg-6 order-lg-first form-small">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label for="password1" class="label-text">Current Password</label>
                        <input class="login-credentials" id="password1" type="password" name="old_password" minlength="8" placeholder="Enter current password" required>
                        <i class="bi bi-eye-slash eye-icon" id="togglePassword1"></i>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="password" class="label-text">New Password</label>
                        <input class="login-credentials" id="password" type="password" name="new_password" minlength="8" placeholder="Enter new password" required>
                        <i class="bi bi-eye-slash eye-icon" id="togglePassword"></i>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="password2" class="label-text">Confirm New Password</label>
                        <input class="login-credentials" id="password2" type="password" name="cpassword" minlength="8" placeholder="Confirm password" required>
                        <i class="bi bi-eye-slash eye-icon" id="togglePassword2"></i>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <span class="forgot-password"><a style="text-decoration: none;" href="forgot-password" target="_blank">Forgot Password?</a></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                        <button class="login-button" name="change-password2" type="submit">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../javascript/togglePassword.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<?php include "php/navigation2.php"; ?>
