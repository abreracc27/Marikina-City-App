<nav class="nav">
	<a href="home" class="nav__link <?php if(basename($_SERVER['PHP_SELF'])=='home.php'){echo 'nav__link--active';}else{echo '';} ?>">
		<i class="material-icons nav__icon">home</i>
	</a>

	<a href="announcements" class="nav__link <?php if(basename($_SERVER['PHP_SELF'])=='announcements.php'){echo 'nav__link--active';}else{echo '';} ?>">
		<i class="material-icons nav__icon">feed</i>
	</a>

	<a href="centers" class="nav__link <?php if(basename($_SERVER['PHP_SELF'])=='centers.php'){echo 'nav__link--active';}else{echo '';} ?>">
		<i class="material-icons nav__icon">location_on</i>
	</a>

	<a href="notifications" class="nav__link <?php if(basename($_SERVER['PHP_SELF'])=='notifcations.php'){echo 'nav__link--active';}else{echo '';} ?>">
		<i class="material-icons nav__icon">notifications</i>
	</a>

	<a href="messages" class="nav__link <?php if(basename($_SERVER['PHP_SELF'])=='messages.php'){echo 'nav__link--active';}else{echo '';} ?>">
		<i class="material-icons nav__icon">chat</i>
	</a>
</nav>