<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" charset="UTF-8">
	<?php

	date_default_timezone_set("Asia/Manila");

	?>
<!-- PWA -->
<link rel="manifest" href="./manifest.json">

<link rel = "icon" type = "image/png" href = "../images/.png">

<link rel = "shortcut icon" type="image/png" href="../images/favicon.png">

<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

<link rel="stylesheet" href="./css/style.css?v=<?php echo time(); ?>">

<!-- <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet"> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" />

<!-- TEL PHONE INPUT -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

<!-- BOOTSTRAP 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!-- BOXICONS CDN -->
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

<!-- DATATABLE SCROLL -->
<link href='https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css' rel='stylesheet'>

<!-- DATATABLE BUTTON CDN -->
<link href='https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css' rel='stylesheet'>

<!-- DATATABLES CDN -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' rel='stylesheet'>

<!-- <link href='https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css' rel='stylesheet'> -->

<!--BOOTSTRAP ICON -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<!-- JAVASCRIPT BUNDLE CDN WITH POPPER -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- JQUERY CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<!-- DATATABLES JS CDN -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script> -->

<!-- READMORE JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/1.0.0/readmore.min.js" integrity="sha512-e1U0cnhlGLlAoA0qeXTrjG46/oTa8YBRxAGKJwADkIeAGmSmD0PN08aj+9J7OBqAWpVAsE8oYjm6Yw3mZES7uQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js" integrity="sha512-j7/1CJweOskkQiS5RD9W8zhEG9D9vpgByNGxPIqkO5KrXrwyDAroM9aQ9w8J7oRqwxGyz429hPVk/zR6IOMtSA==" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>

<!-- TEL PHONE INPUT JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<!-- SERVICE WORKER PWA -->
<script src="./index.js" type="module"></script>

<script src="./javascript/geolocation-marker.js"></script>

<script src="./javascript/topbar.min.js"></script>

<script src="./javascript/prettify.min.js"></script>

<script>
	$(function() {
		prettyPrint()
		function resetToDefaults() {
			topbar.config({
				autoRun      : true,
				barThickness : 5,
				barColors    : {
					'0'      : 'rgba(26,  188, 156, .9)',
					'.25'    : 'rgba(52,  152, 219, .9)',
					'.50'    : 'rgba(241, 196, 15,  .9)',
					'.75'    : 'rgba(230, 126, 34,  .9)',
					'1.0'    : 'rgba(211, 84,  0,   .9)'
				},
				shadowBlur   : 10,
				shadowColor  : 'rgba(0,   0,   0,   .6)',
				className    : 'topbar'
			})
		}

		resetToDefaults()
		topbar.show()
		setTimeout(function() {
			$('.main-content').fadeIn('slow')
			topbar.hide()
		}, 1500)

		setTimeout(function() {
			$('.login-form').fadeIn('slow')
			topbar.hide()
		}, 1500)
	})
</script>
</head>
