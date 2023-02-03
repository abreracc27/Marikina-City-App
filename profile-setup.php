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
           
        }else{
            header('Location: user-otp');
        }
    }
}else{
    header('Location: login');
}

(!empty($_SESSION['lastname'])) ? $lastname = $_SESSION['lastname'] : $lastname = "";
(!empty($_SESSION['firstname'])) ? $firstname = $_SESSION['firstname'] : $firstname = "";
(!empty($_SESSION['midname'])) ? $midname = $_SESSION['midname'] : $midname = "";
(!empty($_SESSION['email'])) ? $email = $_SESSION['email'] : $email = "";
(!empty($_SESSION['age'])) ? $age = $_SESSION['age'] : $age = "";
(!empty($_SESSION['sex'])) ? $sex = $_SESSION['sex'] : $sex = "";
(!empty($_SESSION['birthday'])) ? $birthday = $_SESSION['birthday'] : $birthday = "";
(!empty($_SESSION['location'])) ? $location = $_SESSION['location'] : $location = "";
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
            <div class="row py-4">
                <div class="col-12 col-lg-12 order-lg-first">
                    <a href="controller?logout=1"><i class="fas fa-arrow-left" style="font-size: 16px !important; color: var(--button-color);"></i></a>
                </div>
                <div class="col-12 col-lg-12">
                    <span class="login-text">Personal Information</span>
                </div>

				<div class="col-12 col-lg-12" style="text-align: center;">
                    <span style="font-size: 12px;">Setup your personal information to continue</span>
                </div>

                <div class="col-12 col-lg-12 mt-4">
                    <span style="color: red;">* Required</span>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="firstname">First Name</label> <span style="color: red;">*</span><br/>
                    <input type="text" id="firstname" class="login-credentials" placeholder="e.g. Juan" name="firstname" value="<?php echo $firstname; ?>" required>
				</div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="lastname">Last Name</label> <span style="color: red;">*</span><br/>
                    <input id="lastname" type="text" class="login-credentials" name="lastname" placeholder="e.g. Dela Cruz" value="<?php echo $lastname; ?>" required />  
				</div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="midname">Middle Name</label><br/>
                    <input type="text" id="midname" class="login-credentials" placeholder="e.g. Santos" name="midname" value="<?php echo $midname; ?>">
                </div>

                <div class="col-6 col-lg-6 mt-3">
                    <label for="sex">Gender</label> <span style="color: red;">*</span><br/>
                    <input type="text" id="sex" class="login-credentials" placeholder="e.g. Male" name="sex" value="<?php echo $sex; ?>" required>
                </div>

                <div class="col-6 col-lg-6 mt-3">
                    <label for="age">Age</label> <span style="color: red;">*</span><br/>
                    <input type="text" id="age" class="login-credentials" placeholder="e.g. 21" name="age" value="<?php echo $age; ?>" required>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="birthday">Date of Birth</label> <span style="color: red;">*</span><br/>
                    <!-- <input type="date" id="birthday" class="login-credentials" name="birthday"> -->
                    <input type="text" id="birthday" class="login-credentials" name="birthday" placeholder="e.g. 09/27/2000" value="<?php echo $birthday; ?>" required>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="email">Email</label><br/>
                    <input id="email" type="text" class="login-credentials" name="email" placeholder="e.g. juandelacruz@gmail.com" value="<?php echo $email; ?>">  
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="location">Home Address</label> <span style="color: red;">*</span><br/>
                    <input id="location" type="text" class="login-credentials" name="location" placeholder="e.g. 64 Marikina City" value="<?php echo $location; ?>" required>  
                </div>

                <div class="col-12 col-lg-12 mt-5" style="position: relative; bottom: 0;">
                    <input class="login-button" type="submit" name="submit-info" value="Create Profile">
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