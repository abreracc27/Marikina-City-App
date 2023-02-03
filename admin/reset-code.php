<?php require_once "controller.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login');
}
?>
<?php include "php/header1.php"; ?>
<title>Forgot Password — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<body class="body-margin marikina-color">

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
		<form action="reset-code" method="POST" autocomplete="off">
			<div class="row">
                <div class="col-12 col-lg-12 order-lg-first">
                    <span class="login-text">Retrieve Account</span>
                </div>
                <?php if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) { ?>
                    <div class="col-12 col-lg-12 mt-1">
                        <div class="alert alert-<?php echo $_SESSION['statusMsg']; ?> d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                            <?php echo $_SESSION['success_message']; ?>
                        </div>
                    </div>
                <?php } ?>
				<div class="col-12 col-lg-12 mt-2">
					<label for="otp">6-Digit Code</label>
                    <input class="login-credentials" id="otp" type="text" name="otp" placeholder="Enter 6-digit code" maxlength="6" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" required>
				</div>
				<?php if(count($errors) > 0){ ?>
					<div class="col-12 col-lg-12 mt-3">
                        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							<?php
							foreach ($errors as $showerror) {
								echo $showerror;
							}
							?>
						</div>
					</div>
				<?php } ?>
				<div class="col-12 col-lg-12 mt-3">
                    <input class="login-button" type="submit" name="check-reset-otp" value="Submit">
				</div>

				<div class="col-12 col-lg-12 mt-3">
					<span class="create-account">Didn't receive the verification OTP? <a style="text-decoration: none;" id="cd" href="controller?resend=1&page=reset-code">Resend<span id="timer"><span id="time"></span></span></a></span>
				</div>
            </div>
        </div>
    </div>
</body>

<script>
	if(sessionStorage.getItem("counter")){
		if(sessionStorage.getItem("counter") > 0){
			var counter = sessionStorage.getItem("counter");
		}else{
			var counter = 60;
		}
	}else{
		var counter = 60;
	}

	var interval = setInterval(function() {
		counter--;
		sessionStorage.setItem("counter", String(counter));
		// Display 'counter' wherever you want to display it.
		if (counter <= 0) {
			clearInterval(interval);
			$('#timer').html("");
			sessionStorage.removeItem("counter");  
			return;
		}else{
			$('#time').text("("+counter+")");
		console.log("Timer --> " + counter);
		}
	}, 1000);

	const button = document.getElementById("cd");
	button.href="javascript: void(0)";
	button.style="cursor: default; text-decoration: none; pointer-events: none;";   
	setTimeout(function() {
		button.href="controller?resend=1&page=reset-code"; 
		button.style="text-decoration: none;";      
	}, counter * 1000);

	$(document).on("click", "#cd", function(event){
		window.location.replace("controller?resend=1&page=reset-code");
		if($('#time').length > 0){
			if(sessionStorage.getItem("counter")){
				if(sessionStorage.getItem("counter") > 0){
					var counter = sessionStorage.getItem("counter");
				}else{
					var counter = 60;
				}
			}else{
				var counter = 60;
			}

			var interval = setInterval(function() {
				counter--;
				sessionStorage.setItem("counter", String(counter));
				// Display 'counter' wherever you want to display it.
				if (counter <= 0) {
					clearInterval(interval);
					$('#timer').html("");
					sessionStorage.removeItem("counter");  
					return;
				}else{
					$('#time').text("("+counter+")");
				console.log("Timer --> " + counter);
				}
			}, 1000);
		}else{
			var element = document.createElement("span");
			document.getElementById('timer').appendChild(element);
			element.setAttribute("id", "time");
			element.innerHTML = "(60)";

			if(sessionStorage.getItem("counter")){
				if(sessionStorage.getItem("counter") > 0){
					var counter = sessionStorage.getItem("counter");
				}else{
					var counter = 60;
				}
			}else{
				var counter = 60;
			}
			var interval = setInterval(function() {
				counter--;
				sessionStorage.setItem("counter", String(counter));
				// Display 'counter' wherever you want to display it.
				if (counter <= 0) {
					clearInterval(interval);
					$('#timer').html("");  
					sessionStorage.removeItem("counter");  
					return;
				}else{
					$('#time').text("("+counter+")");
				console.log("Timer --> " + counter);
				}
			}, 1000);
		}

		const button = document.getElementById("cd");
        button.href="javascript: void(0)";
		button.style="cursor: default; text-decoration: none; pointer-events: none;";   
        setTimeout(function() {
            button.href="controller?resend=1&page=reset-code"; 
			button.style="text-decoration: none;";      
        }, counter * 1000);
	});
</script>
</html>