<?php require_once "controller.php"; ?>
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
        if($status == "verified"){
           
        }else{
            header('Location: user-otp');
        }
    }
}else{
    header('Location: login');
}


$check_unique = "SELECT * FROM admin WHERE email = '$email'"; 
$unique_res = mysqli_query($conn, $check_unique);
if($unique_res){
    $fetch_row = mysqli_fetch_assoc($unique_res);
    $admin_unique_id = $fetch_row['admin_unique_id'];
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
<?php include "php/header1.php"; ?>
<title>Announcements — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<?php include "php/alert-message.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="row profile-form p-3">
        <div class="col-12 order-lg-first">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <textarea class="announcement" name="announcement" id="announcement" placeholder="Type announcement here..."><?php echo $announcement; ?></textarea>
                        <input type="file" name="uploadfile[]" style="width: 100%; display:none;" id="chooseFile" multiple accept="image/*,video/*">
                        <Button type="button" class="btnBrowse" onclick="document.getElementById('chooseFile').click();"><i class='bx bx-upload'></i> Choose files...</button>
                    </div>
                </div>

                <div class="testing">
                    <div class="imgGallery"></div>

                    <?php
                        if(isset($_GET['id'])){
                            $id = $_GET['id'];
                            $dirname = "../images/public/uploads/".$id."/";
                            $images = str_replace(' ', '%20', glob($dirname."*.*"));
                            $filecount = count(glob($dirname."*.*"));
                            ?>
                            <div class="imgGallery2">
                                <?php
                            foreach($images as $key => $image) {
                                $fileExtension = substr($image, strrpos($image, '.')+1);

                                if($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png' || $fileExtension == 'gif'){
                                    echo "<div class='appendedImg'><img style='width: 100%; height: 150px; object-fit: cover;' src=".$image."><button class='close AClass btnRemove2' value=".$key."><i class='bx bxs-x-circle'></i></button></div>";
                                }elseif($fileExtension == 'mp4' || $fileExtension == 'mkv' || $fileExtension == 'mov'){
                                    echo "<div class='appendedImg'><video style='width: 100%; height: 150px; object-fit: cover;'><source src=".$image." type='video/mp4'></video><button class='close AClass btnRemove2' value=".$key."><i class='bx bxs-x-circle'></i></button></div>";
                                }
                            }
                            ?>
                            </div>
                            <?php
                            $images = array_slice(scandir($dirname), 2);      
                        }
                    ?>
                </div>

                <input type="hidden" id="removed_files" name="removed_files" value="" />
                <input type="hidden" id="removed_files2" name="removed_files2" value="" />

                 <div class="row">
                    <div class="col">
                        <?php 
                        $action = "";
                        if(isset($_GET['action_type'])){
                            $action = $_GET['action_type'];

                            if($action == "edit"){
                                echo "<a href='announcements'><button class='disregard-button' type='button'>Cancel</button></a>";
                                echo "<input class='post-button' value='Update' name='save-edit' type='submit'>";
                            }
                        }else{?>
                            <input class="post-button" value="<?php if($action == ''){echo 'Post';} if($action == 'edit'){echo 'Update';}?>" name="<?php if($action == ''){echo 'post';} if($action == 'edit'){echo 'save-edit';}?>" type="submit">
                        <?php }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>

            <div class="row card my-5">
                <span class="card-header" style="display: flex;">
                    <img src="../images/marikina-city-seal-big.jpg" alt="" width="50" height="50" class="rounded-circle" loading="lazy">
                    <div style="line-height: 25px; margin-left: 15px;">
                        <b><?php echo $row['name']?><br></b>
                        <span class="announcement-info"><?php if(empty($row['datetime_last_modified'])){echo "Posted - ".date('D, j M Y \a\t g:i A', $row['datetime_created']);}else{echo "Edited - ".date('D, j F Y \a\t g:i A', $row['datetime_last_modified']);}?></span>
                    </div>

                    <?php if($admin_unique_id == $row['admin_unique_id'] || $_SESSION['super'] == 1){?>
                    <div style="position: absolute; right: 16px; font-size: 25px; height: 50px;">
                        <a href="#" class="d-flex align-items-center three-dots" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-dots-horizontal-rounded' style="line-height: 50px;"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownUser3" style="">    
                            <a href="announcements?action_type=edit&id=<?php echo $row['id']; ?>" class="announcement_ddown">
                                <i class='bx bx-edit nav_icon'></i>
                                Edit
                            </a>
                            <a href="controller.php?delete-id=<?php echo $row['id']; ?>" class="announcement_ddown">
                                <i class='bx bx-trash nav_icon'></i>
                                Delete
                            </a>
                            
                        </div>
                    </div>
                    <?php } ?>
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
                    $check_dir = scandir('../images/public/uploads/'.$row['id']);
                    if(count($check_dir) > 2){ ?>
              			<div class="gallery mb-2">
              				<?php
								$gal = scandir('../images/public/uploads/'.$row['id']);
								unset($gal[0]);
								unset($gal[1]);
								$count =count($gal);
								$i = 0;

								

								foreach($gal as $k => $v){
									$mime = mime_content_type('../images/public/uploads/'.$row['id'].'/'.$v);
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
											<a href="../images/public/uploads/<?php echo $row['id'].'/'.$v ?>" data-fancybox="gallery<?php echo $row['id'];?>">
												<img src="../images/public/uploads/<?php echo $row['id'].'/'.$v ?>" class="gallery__img" alt="Image 1" loading="lazy">
											</a>
										<?php }
										
										else{ 
											if($count > 1){ ?>
												<a href="../images/public/uploads/<?php echo $row['id'].'/'.$v ?>">
											<?php } ?>

                                            
											<video <?php echo $count == 1 ? "controls" : '' ?> class="gallery__img">
												<source src="../images/public/uploads/<?php echo $row['id'].'/'.$v ?>" type="<?php echo $mime ?>">
											</video>
									
											<?php 
												if($count > 1){ ?>
													</a>

													<a href="../images/public/uploads/<?php echo $row['id'].'/'.$v ?>" class="text-white view_more" data-id="<?php echo $row['id'] ?>" data-fancybox="gallery<?php echo $row['id'];?>">
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
    }?>

</div>

<script src="../javascript/readMoreJS.min.js?v=<?php echo time(); ?>"></script>

<script>
    $readMoreJS.init({
        target: '.dummy span',           // Selector of the element the plugin applies to (any CSS selector, eg: '#', '.'). Default: ''
        numOfWords: 21,               // Number of words to initially display (any number). Default: 50
        toggle: true,                 // If true, user can toggle between 'read more' and 'read less'. Default: true
        moreLink: 'Read more',    // The text of 'Read more' link. Default: 'read more ...'
        lessLink: 'Read less'         // The text of 'Read less' link. Default: 'read less'
    });
</script>

<script>
window.newFileList = [];

$(document).on('click', '.btnRemove', function () {
  var remove_element = $(this);
  var id = remove_element.val();
  remove_element.closest('.appendedImg').remove();
  var input = document.getElementById('chooseFile');
  var files = input.files;
  if (files.length) {
     if (typeof files[id] !== 'undefined') {
       window.newFileList.push(files[id].name)
     }
  }
  document.getElementById('removed_files').value = JSON.stringify(window.newFileList);
});

$(document).on('change', '#chooseFile', function (event) {
    var total_file = document.getElementById("chooseFile").files.length;
    var selection = document.getElementById("chooseFile");
    $('.imgGallery').empty();
    document.getElementById('removed_files').value = '';
    for (var i = 0; i < total_file; i++) {
        var ext = selection.files[i].name.split('.').pop();
        if(ext == "mp4" || ext == "mkv" || ext == "mov"){
            $('.imgGallery').append("<div class='appendedImg'><video style='width: 100%; height: 150px; object-fit: cover;'><source src='" + URL.createObjectURL(event.target.files[i]) + "' type='video/mp4'></video><button class='close AClass btnRemove' value='" + i + "'><i class='bx bxs-x-circle'></i></button></div>");
        }
        else if(ext == "png" || ext == "jpg" || ext == "gif" || ext == "jpeg"){
            $('.imgGallery').append("<div class='appendedImg'><img style='width: 100%; height: 150px; object-fit: cover;' src='" + URL.createObjectURL(event.target.files[i]) + "'><button class='close AClass btnRemove' value='" + i + "'><i class='bx bxs-x-circle'></i></button></div>");
        }
    }
});
</script>

<script>
$(document).on('click', '.btnRemove2', function () {
    var remove_element = $(this);
    var id = remove_element.val();
    remove_element.closest('.appendedImg').remove();
    var files = <?php echo json_encode($images); ?>;
    window.newFileList.push(files[id]);
    document.getElementById('removed_files2').value = JSON.stringify(window.newFileList);
});
</script>

<script src="../javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<!--Container Main end-->
<?php include "php/navigation2.php"; ?>

