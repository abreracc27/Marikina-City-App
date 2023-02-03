<?php require_once "controller.php"; ?>
<?php
$email = "";
if(isset($_COOKIE['ad'])){
	$email = $_COOKIE['ad'];
}
?>
<?php include "php/header1.php"; ?>
<title>Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<body class="body-margin marikina-color">
	
	<img class="city-seal" src="../images/marikina-city-seal-big.jpg"/>

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
	
	<div class="login-form container-fluid">
		<form action="login" method="POST" autocomplete="off">
			<div class="row">
                <div class="col-12 col-lg-12 order-lg-first">
                    <span class="login-text">Administrator Login</span>
                </div>
				<div class="col-12 col-lg-12 mt-1"> 
					<label for="email">Email</label>
					<input class="login-credentials" type="email" id="email" name="email" placeholder="Enter email" required value="<?php echo $email ?>">
				</div>
				<div class="col-12 col-lg-12 mt-3">
					<label for="password">Password</label>
					<input class="login-credentials" type="password" id="password" name="password" minlength="8" placeholder="Enter password" required>
					<i class="bi bi-eye-slash eye-icon" id="togglePassword"></i>
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
				

  				<div class="col-12 col-lg-12 mt-3">
					<input type="checkbox" id="ck" name="ck" value="ck">
 					<label for="ck">Keep me logged in</label><br>
				</div>

				<div class="col-12 col-lg-12 my-3">
					<span class="forgot-password"><a style="text-decoration: none;" href="forgot-password">Forgot Password?</a></span>
				</div>
				<div class="col-12 col-lg-12">
					<input class="login-button"  type="submit" name="login" value="Sign In">
				</div>
            </div>
		</form>
    </div>
	<script src="../javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
	<?php
		if(isset($_COOKIE['ad'])){
			echo "<script>
					$('#ck').attr('checked','checked');
				</script>";
		}
	?>
</body>
</html>