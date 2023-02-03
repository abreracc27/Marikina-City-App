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

(!empty($_SESSION['primary_contact'])) ? $primary_contact = $_SESSION['primary_contact'] : $primary_contact = "";
(!empty($_SESSION['primary_relation'])) ? $primary_relation = $_SESSION['primary_relation'] : $primary_relation = "";
(!empty($_SESSION['primary_phone'])) ? $primary_phone = $_SESSION['primary_phone'] : $primary_phone = "";
(!empty($_SESSION['secondary_contact'])) ? $secondary_contact = $_SESSION['secondary_contact'] : $secondary_contact = "";
(!empty($_SESSION['secondary_relation'])) ? $secondary_relation = $_SESSION['secondary_relation'] : $secondary_relation = "";
(!empty($_SESSION['secondary_phone'])) ? $secondary_phone = $_SESSION['secondary_phone'] : $secondary_phone = "";

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

    <div class="login-form">
        <form method="POST">
            <div class="row py-4" style="100%;">
                <div class="col-12 col-lg-12 order-lg-first">
                    <a href="profile-setup"><i class="fas fa-arrow-left" style="font-size: 16px !important; color: var(--button-color);"></i></a>
                </div>

                <div class="col-12 col-lg-12">
                    <span class="login-text">Emergency Contact</span>
                </div>

                <div class="col-12 col-lg-12" style="text-align: center;">
                    <span style="font-size: 12px;">Edit your emergency contact in case of emergency</span>
                </div>

                <div class="col-12 col-lg-12 mt-4">
                    <span style="color: red;">* Required</span>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="primary_contact">Primary Emergency Contact</label><br/>
                    <input type="text" id="primary_contact" class="login-credentials" placeholder="e.g. Juanita Dela Cruz" name="primary_contact" value="<?php echo $primary_contact;?>">
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="primary_relation">Relationship</label><br/>
                    <input id="primary_relation" type="text" class="login-credentials" name="primary_relation" placeholder="e.g. Mother" value="<?php echo $primary_relation;?>"/>  
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="primary_phone">Phone</label><br/>
                    <div class="input-box">
                        <span class="prefix">+63</span>
                        <input type="text" id="primary_phone" class="login-credentials" placeholder="e.g. 91234567890" name="primary_phone" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" value="<?php echo substr($primary_phone,3);?>">
                    </div>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <br/>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="secondary_contact">Secondary Emergency Contact</label><br/>
                    <input type="text" id="secondary_contact" class="login-credentials" placeholder="e.g. Maria Dela Cruz" name="secondary_contact" value="<?php echo $secondary_contact;?>">
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="secondary_relation">Relationship</label><br/>
                    <input type="text" id="secondary_relation" class="login-credentials" placeholder="e.g. Aunt" name="secondary_relation" value="<?php echo $secondary_relation;?>">
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="secondary_phone">Phone</label><br/>
                    <div class="input-box">
                        <span class="prefix">+63</span>
                        <input type="text" id="secondary_phone" class="login-credentials" name="secondary_phone" placeholder="e.g. 91234567890" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" value="<?php echo substr($secondary_phone, 3);?>">
                    </div>
                </div>


                <div class="col-12 col-lg-12 mt-5" style="position: relative; bottom: 0;">
                    <div class="col-12 col-lg-12 mt-3"> 
                        <a href="medical-info"><button type="button" class="cancel-button">Not Now</button></a>
                    </div>
                    <div class="col-12 col-lg-12 mt-3"> 
                        <input class="login-button" type="submit" name="emergency-info" value="Create Emergency Contact">
				    </div>
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
            </div>           
         </form>
    </div>
    <script src="javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
</body>

<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

</html>