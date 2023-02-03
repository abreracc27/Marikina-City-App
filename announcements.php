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

$announcement = "";

if(isset($_GET['id'])){
    $announcement_id = $_GET['id'];

    $sql = "SELECT * FROM announcements WHERE id='$announcement_id'";
    $result2 = $conn->query($sql);
    while ($row2 = $result2->fetch_assoc()) {
        $announcement = $row2['message'];
    }
}



$sql2 = "SELECT announcements.id, admin.admin_unique_id, admin.name, announcements.message, announcements.datetime_created, announcements.datetime_last_modified FROM
announcements INNER JOIN admin ON announcements.admin_unique_id=admin.admin_unique_id ORDER BY id DESC";

//execute the query

$result = $conn->query($sql2);


function plain_url_to_link($announcement) {
    return preg_replace(
    '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', 
    '<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $announcement);
}

function nl2p($announcement) {
    return $string_with_paragraphs = "<p>".implode("</p><p>", explode("\n", $announcement))."</p>";
}
?>
<?php include "php/header.php"; ?>
<body>

<header class="header">
    <div style="width: 45px; text-align: center;">
        <button id="btnSidenav" style="width: 45px; border: none; background: transparent;">
            <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">manage_accounts</i>
        </button>
    </div>  
</header>     

<?php include "php/sidenav.php"; ?>

<div class="main-content message-area" style="position: relative;">
    <div class="center-area2" style="max-height:100%; overflow-x:hidden;">
        <div class="row pt-4">
            <div class="col-12 col-lg-12">
                <span class="login-text">Announcements</span>
            </div>
        </div>
    <?php if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="row card mt-2 mb-5">
                <span class="card-header" style="display: flex;">
                    <img src="images/marikina-city-seal-big.jpg" alt="" width="50" height="50" class="rounded-circle" loading="lazy">
                    <div style="line-height: 25px; margin-left: 15px;">
                        <b><?php echo $row['name']?><br></b>
                        <span class="announcement-info"><?php if(empty($row['datetime_last_modified'])){echo "Posted - ".date('D, j M Y \a\t g:i A', $row['datetime_created']);}else{echo "Edited - ".date('D, j F Y \a\t g:i A', $row['datetime_last_modified']);}?></span>
                    </div>
                </span>
                <?php if(!empty($row['message'])){?>
                    <div class="card-body dummy">
                        <?php
                        $announcement = $row['message'];
                        ?>
                        <span class="card-text"><?php echo nl2p(plain_url_to_link($announcement))?></span>
                    </div>
                <?php } ?>


                <?php 
                    $check_dir = scandir('images/public/uploads/'.$row['id']);
                    if(count($check_dir) > 2){ ?>
              			<div class="gallery mb-2">
              				<?php
								$gal = scandir('images/public/uploads/'.$row['id']);
								unset($gal[0]);
								unset($gal[1]);
								$count =count($gal);
								$i = 0;

								foreach($gal as $k => $v){
									$mime = mime_content_type('images/public/uploads/'.$row['id'].'/'.$v);
									$i++;
									if($i > 4)
										break;
										$style = '';

									if($count == 1){
										$style = "grid-column-start: 1;grid-column-end: 3;grid-row-start: 1;grid-row-end: 3;";
									}elseif($count == 2){
										$style = "grid-column-start: {$i};grid-column-end: ".($i + 1).";grid-row-start: 1;grid-row-end: 3;";
									}elseif ($count == 3) {
										if($i == 1)
										$style = "grid-column-start: {$i};grid-column-end: ".($i + 1).";grid-row-start: 1;grid-row-end: 3;";
									} ?>

									<figure class="gallery__item position-relative" style="<?php echo $style ?>">
										<?php 							
										if(strstr($mime,'image')){ ?>
											<a href="images/public/uploads/<?php echo $row['id'].'/'.$v ?>" data-fancybox="gallery<?php echo $row['id'];?>">
												<img src="images/public/uploads/<?php echo $row['id'].'/'.$v ?>" class="gallery__img" alt="Image 1" loading="lazy">
											</a>
										<?php }
										
										else{ 
											if($count > 1){ ?>
												<a href="images/public/uploads/<?php echo $row['id'].'/'.$v ?>">
											<?php } ?>

                                            
											<video <?php echo $count == 1 ? "controls" : '' ?> class="gallery__img">
												<source src="images/public/uploads/<?php echo $row['id'].'/'.$v ?>" type="<?php echo $mime ?>">
											</video>
									
											<?php 
												if($count > 1){ ?>
													</a>

													<a href="images/public/uploads/<?php echo $row['id'].'/'.$v ?>" class="text-white view_more" data-id="<?php echo $row['id'] ?>" data-fancybox="gallery<?php echo $row['id'];?>">
														<div class="position-absolute d-flex justify-content-center align-items-center h-100 w-100" style="top:0;left:0;z-index:1" >
															<h3 class="text-white text-center rounded-circle "><i class="fa fa-play-circle "></i></h3>
														</div>
													</a>
												<?php } ?>

										<?php } ?>
									</figure>
              					<?php } ?>
						</div>
              		<?php } ?>
            </div>
        <?php
        }
    }else{
        echo "<div style='line-height: calc(100vh - 154px); vertical-align: middle; text-align: center;'>No announcements yet to display.</div>";
    }?>
    </div>
</div>

<br/><br/>

<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->
  
</body>

<script src="javascript/readMoreJS.min.js?v=<?php echo time(); ?>"></script>

<script>
    $readMoreJS.init({
        target: '.dummy span',           // Selector of the element the plugin applies to (any CSS selector, eg: '#', '.'). Default: ''
        numOfWords: 21,               // Number of words to initially display (any number). Default: 50
        toggle: true,                 // If true, user can toggle between 'read more' and 'read less'. Default: true
        moreLink: 'Read more',    // The text of 'Read more' link. Default: 'read more ...'
        lessLink: 'Read less'         // The text of 'Read less' link. Default: 'read less'
    });
</script>
</html>
