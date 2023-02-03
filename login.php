<?php require_once "controller.php"; ?>
<?php

$phone = "";
if(isset($_COOKIE['ph'])){
	$phone = $_COOKIE['ph'];
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

    <div class="login-form">
        <form method="POST">
            <div class="row" style="position: absolute; left: 0; top: 30px;">
                <div class="col-12 col-lg-12 order-lg-first">
                    <span class="login-text">Welcome</span>
                </div>

				<div class="col-12 col-lg-12 order-lg-first" style="text-align: center;">
					<span style="font-size: 12px;">Sign in to continue</span>
                </div>

                <div class="col-12 col-lg-12 mt-4"> 
					<label for="phone">Phone</label><br/>
					<div class="input-box">
						<span class="prefix">+63</span>
                    	<input id="phone" type="tel" class="login-credentials" name="phone" placeholder="Phone Number" style="width: 100% !important;" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" value="<?php echo substr($phone, 3);?>"/>  
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
			<div class="button-container">
				<div class="col-12 col-lg-12">
					<span class="forgot-password"><a style="text-decoration: none;" href="forgot-password">Forgot PIN?</a></span>
				</div>
				<div class="col-12 col-lg-12 my-4">
					<input class="login-button" type="submit" name="login1" value="Login">
				</div>
				<div class="col-12 col-lg-12">
					<span class="create-account">Don't have an account? <a style="text-decoration: none;" href="register">Create a new account</a></span>
				</div>
			</div>             
         </form>
    </div>
    <script src="javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
</body>

<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

</html>