<?php require_once "controller.php"; ?>
<?php 
$phone = $_SESSION['phone'];
if($phone == false){
  header('Location: login');
}
?>
<?php include "php/header.php"; ?>
<body>
    <div class="login-form">
		<div class="row" style="position: absolute; left: 0; top: 30px;">
			<div class="col-12 col-lg-12 order-lg-first">
				<span class="login-text">Marikina City</span>
			</div>

			<div class="col-12 col-lg-12 order-lg-first" style="text-align: center;">
				<span style="font-size: 12px;">Health & Safety</span>
			</div>

			<div class="col-12 col-lg-12 my-3" style="text-align: center;">
				<div id="icon-container"></div>
			</div>

		</div>  
		<div style="position: absolute; bottom: 3%; width: 100%;">
			<div class="col-12 col-lg-12" style="text-align: center;">
				<span>Password Changed</span>
			</div>
			<div class="col-12 col-lg-12 mb-4 mt-5">
				<a href="login"><button class="login-button">Back to Login</button></a>
			</div>
		</div>             
    </div>
</body>
<script>
  var animation = bodymovin.loadAnimation({
  // animationData: { /* ... */ },
  container: document.getElementById('icon-container'), // required
  path: 'images/check.json', // required
  renderer: 'svg', // required
  loop: false, // optional
  autoplay: true, // optional
  name: "Demo Animation", // optional
});
</script>
</html>