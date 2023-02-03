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

$form = "";
if(isset($_GET['form'])){
    $form = $_GET['form'];
}
?>
<?php include "php/header.php"; ?>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>

<header class="header">
    <div style="width: 45px; text-align: center;">
        <a href="manage-account" style="text-decoration: none;">
            <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
        </a>
    </div>  
</header>   

<?php include "php/sidenav.php"; ?>

<div class="main-content message-area">
    <div class="profile-center-area">
        <section class="section">
            <form method="POST">
                <div class="row py-4">
                    <div class="col-12 col-lg-12">
                        <span class="login-text">Verify Your PIN</span>
                    </div>

                    <div class="col-12 col-lg-12" style="text-align: center;">
                        <span style="font-size: 12px;">Re-enter your PIN to continue</span>
                    </div>

                    <?php if(count($errors)== 1){ ?>
                        <div class="col-12 col-lg-12">
                            <div class="mt-3 alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php
                                foreach($errors as $showerror){
                                    echo $showerror;
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(count($errors) > 1){ ?>
                        <div class="col-12 col-lg-12">
                            <div class="mt-3 alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php
                                foreach($errors as $showerror){
                                    ?>
                                    <li><?php echo $showerror; ?></li>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-12 col-lg-12 my-4" style="text-align: center; position: relative; height: calc(100vh - 505px);">
					<input type="password" id="password" class="display-otp" name="password"></br>
                    <input type="hidden" class="display-otp" name="form" value="<?php echo $form; ?>">
				</div>
				<div class="col-12 col-lg-12" style="text-align: center">
					<input type="button" value="1" id="1" class="pinButton calc"/>
					<input type="button" value="2" id="2" class="pinButton calc"/>
					<input type="button" value="3" id="3" class="pinButton calc"/><br>
					<input type="button" value="4" id="4" class="pinButton calc"/>
					<input type="button" value="5" id="5" class="pinButton calc"/>
					<input type="button" value="6" id="6" class="pinButton calc"/><br>
					<input type="button" value="7" id="7" class="pinButton calc"/>
					<input type="button" value="8" id="8" class="pinButton calc"/>
					<input type="button" value="9" id="9" class="pinButton calc"/><br>
					<input type="button" value="Clear" id="clear" class="pinButton clear"/>
					<input type="button" value="0" id="0 " class="pinButton calc"/>
					<input type="submit" value="Enter" id="enter" name="verify-password" class="pinButton enter"/>
				</div>

                    

                    <!-- <div class="col-12 col-lg-12 mt-5">
                        <input class="login-button" type="submit" name="verify-password" value="Next">
                    </div> -->
                </div>
            </form>
        </section>
    </div>        
</div>
<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->
<script src="javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

<script src="javascript/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>
