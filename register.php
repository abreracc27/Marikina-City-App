<?php require_once "controller.php"; ?>
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
		<!-- Modal -->
		<div class="modal fade" id="termsconditions" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

					<div class="modal-body" style="overflow-x:hidden;">
						<?php include "php/terms-conditions.php"; ?>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" style="background-color: var(--button-color);" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="privacypolicy" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Privacy Policy</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

					<div class="modal-body" style="overflow-x:hidden;">
						<?php include "php/privacy-policy.php"; ?>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" style="background-color: var(--button-color);" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <form method="POST" id="reg-form" name="frm">
			<div style="height: calc(100vh - 111px);">
				<div class="row py-4" style="max-height:100%; overflow-x:hidden;">
					<div class="col-12 col-lg-12 order-lg-first">
						<a href="login"><i class="fas fa-arrow-left" style="font-size: 16px !important; color: var(--button-color);"></i></a>
					</div>

					<div class="col-12 col-lg-12">
						<span class="login-text">Create Account</span>
					</div>

					<div class="col-12 col-lg-12" style="text-align: center;">
						<span style="font-size: 12px;">Create a new account</span>
					</div>

					<div class="col-12 col-lg-12 mt-4 mb-2" style="text-align: center;">
						<span style="font-size: 12px;">An SMS will be sent to your phone.<br>Message & data rates may apply.</span>
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

					<div class="col-12 col-lg-12"> 
						<label for="phone">Phone</label><br/>
						<div class="input-box">
							<span class="prefix">+63</span>
							<input id="phone" type="tel" class="login-credentials" name="phone" placeholder="Phone Number" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" style="width: 100% !important;" /> 
						</div>
					</div>

					<div class="col-12 col-lg-12 mt-4">
						<!-- Button trigger modal -->
						<span class="tc-pp"><input type="checkbox" id="accept" style="background-color: var(--button-color);" name="accept" value="accept" required> I have read and agree with the <a class="tc">Terms & Conditions</a> and <a class="pp">Privacy Policy</a></span>
						<script>
							$(".tc").click(function(){
								$("#termsconditions").modal("show");
							})

							$(".pp").click(function(){
								$("#privacypolicy").modal("show");
							})
						</script>
					</div>
				</div>  
				<div class="button-container">
					<div class="col-12 col-lg-12 my-4">
						<input class="login-button" type="submit" name="user-pin" id="create-user"  value="Create Account">
					</div>
					<div class="col-12 col-lg-12">
						<span class="create-account">Already have an account? <a style="text-decoration: none;" href="login">Login</a></span>
					</div>
				</div> 
			</div>            
		</form>
	</div>
</div>
<script src="javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
</body>

<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>
<script>
	$(document).ready(function(){
		$('#create-user').attr('disabled', true);
	});
	const checkBx = document.getElementById("accept");
	$(document).on('change', '#accept', function (event) {
		if(checkBx.checked){
			$('#create-user').attr('disabled', false);
		}else{
			$('#create-user').attr('disabled', true);
		}
	});


</script>	
</html>
