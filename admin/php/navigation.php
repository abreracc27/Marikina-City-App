

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
        $super = $fetch_info['super'];
        if($status == "verified"){
           
        }else{
            header('Location: user-otp');
        }
    }
}else{
    header('Location: login');
}
?>
<body class='snippet-body body-nav'>

<body id="body-pd">

<header class="header" id="header">
	<div class="header_toggle"> 
		<i class='bx bx-menu' id="header-toggle" style="color: var(--first-color);"></i> 
	</div>
	<span class="nav_title">Marikina City Health & Safety Web</span>
</header>

<div class="l-navbar" id="nav-bar">
	<nav class="nav">
		<div class="nav_logo"></div>
		<div class="nav_list">
			<a href="covid19-cases" class="nav_link <?php if(basename($_SERVER['PHP_SELF'])=='covid19-cases.php'){echo 'active';}else{echo '';} ?>">
				<i class='bx bx-map-alt nav_icon'></i>
				<span class="nav_name">COVID-19 Cases</span>
			</a>
			<a href="user-assistance?status=unsettled" class="nav_link <?= (basename($_SERVER['PHP_SELF'])=='user-assistance.php')?'active':''; ?>">
				<i class='bx bx-support nav_icon'></i>
				<span class="nav_name">User Assistance</span>
			</a>
			<a href="health-centers" class="nav_link <?= (basename($_SERVER['PHP_SELF'])=='health-centers.php')?'active':''; ?>">
				<i class='bx bx-clinic nav_icon'></i>
				<span class="nav_name">Health Centers</span>
			</a>
			<a href="statistic-reports" class="nav_link <?= (basename($_SERVER['PHP_SELF'])=='statistic-reports.php')?'active':''; ?>">
				<i class='bx bx-line-chart nav_icon'></i>
				<span class="nav_name">Statistic Reports</span>
			</a>
			<a href="announcements" class="nav_link <?= (basename($_SERVER['PHP_SELF'])=='announcements.php')?'active':''; ?>">
				<i class='bx bxs-megaphone nav_icon'></i>
				<span class="nav_name">Announcements</span>
			</a>
			<a href="messages" class="nav_link <?= (basename($_SERVER['PHP_SELF'])=='messages.php')?'active':''; ?>">
				<i class='bx bx-envelope nav_icon'></i>
				<span class="nav_name">Messages</span>
			</a> 
			
		</div>

		<a href="#" class="d-flex align-items-center nav_link" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
			<i class='bx bx-cog nav_icon'></i>
			<span class="nav_name">Settings</span>
		</a>
		<div class="dropdown-menu dropdown-menu-nav" aria-labelledby="dropdownUser3" style="">
			<a href="profile" class="dmenu_link <?= (basename($_SERVER['PHP_SELF'])=='messages.php')?'active':''; ?>">
				<i class='bx bx-user-circle nav_icon'></i>
				<span>Profile</span>
			</a>
			<?php
			if($super == 1){
				?>
				<a href="register" class="dmenu_link <?= (basename($_SERVER['PHP_SELF'])=='messages.php')?'active':''; ?>" target="_blank">
					<i class='bx bx-user-plus nav_icon'></i>
					<span>Add Admin</span>
				</a>
				<?php 
			} else{ echo ""; }?>
			<a href="controller.php?logout=1" class="dmenu_link <?= (basename($_SERVER['PHP_SELF'])=='messages.php')?'active':''; ?>">
				<i class='bx bx-log-out nav_icon'></i>
				<span>Sign Out</span>
			</a>
		</div>

	</nav>
</div>