<?php 
session_start();
require "connection.php";

date_default_timezone_set("Asia/Manila");
$email = "";
$name = "";
$message = "";
$errors = array();
$removedImages = "";

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password does not match!";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format!";
    }
    // $name_check = "SELECT * FROM admin WHERE `name` = '$name'";
    // $res = mysqli_query($conn, $name_check);
    // if(mysqli_num_rows($res) > 0){
    //     $errors['name'] = "The username you have entered is already taken!";
    // }
    $email_check = "SELECT * FROM admin WHERE email = '$email'";
    $res = mysqli_query($conn, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "The email you have entered already exist!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $ran_id = rand(time(), 100000000);
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
        $status = "notverified";
        $insert_data = "INSERT INTO admin (admin_unique_id, name, email, password, code, code_expire, status)
                        values('$ran_id','$name', '$email', '$encpass', '$code', '$minutes5', '$status')";
        $data_check = mysqli_query($conn, $insert_data);
        if($data_check){
            $subject = "Email Verification Code"; 
            $message = "Your verification code is $code";
            $sender = "From: mchealthsafetyapp@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $_SESSION['statusMsg'] = "primary";
                $_SESSION['icon'] = "Info";
                $_SESSION['icon-type'] = "info";
				$_SESSION['success_message'] = "A verification code has been sent to $email.";
                $_SESSION['email2'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data to database!";
        }
    }

}
//if user click verification code submit button
if(isset($_POST['check'])){
    $info = "A verification code has been sent to .";
    $_SESSION['info'] = $info;
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM admin WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE admin SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($conn, $update_otp);
        if($update_res){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location:account-verified');
            exit();
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }else{
        $errors['otp-error'] = "Incorrect code! Please try again.";
    }
}


//if user click login button
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $ck = $_POST['ck'];

    $check_email = "SELECT * FROM admin WHERE email = '$email'";
    $res = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            $super = $fetch['super'];
            $admin_id = $fetch['admin_id'];
            $admin_unique_id = $fetch['admin_unique_id'];
            $name = $fetch['name'];
            $activity_status = "Online";
            if($status == 'verified'){
                $sql = "UPDATE admin SET activity_status = '$activity_status' WHERE admin_unique_id = $admin_unique_id";
                $result = $conn->query($sql);
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['super'] = $super;
                $_SESSION['name'] = $name;
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_unique_id'] = $admin_unique_id;
                $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                if($ck == 'ck'){
                    $in1Month = 60 * 60 * 24 * 30 + time();
                    
                    setcookie('ad', $email, $in1Month, '/', $domain, false);
                    setcookie('ai', $admin_id, $in1Month, '/', $domain, false);
                }else{
                    setcookie('ad', "", time(), '/', $domain, false);
                    setcookie('ai', "", time(), '/', $domain, false);
                }
                header('location: covid19-cases?barangay-id=');
            }else{
                $_SESSION['statusMsg'] = "warning";
                $_SESSION['icon'] = "Danger";
                $_SESSION['icon-type'] = "exclamation-triangle";
                $_SESSION['success_message'] = "Your account email has not yet been verified. A verification code has been sent to $email.";
                header('location: user-otp');
            }
        }else{
            $errors['email'] = "The email or password is incorrect!";
        }
    }else{
        $errors['email'] = "The email or password is incorrect!";
    }
}

//if user click continue button in forgot password form
if(isset($_POST['check-email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $check_email = "SELECT * FROM admin WHERE email='$email'";
    $run_sql = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
        $insert_code = "UPDATE admin SET code = $code, code_expire = '$minutes5' WHERE email = '$email'";
        $run_query =  mysqli_query($conn, $insert_code);
        if($run_query){
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: mchealthsafetyapp@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $_SESSION['statusMsg'] = "primary";
                $_SESSION['success_message'] = "A verification code has been sent to $email.";
                $_SESSION['email'] = $email;
                header('location: reset-code');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }else{
        $errors['email'] = "Your search did not return any result. Please try again.";
    }
}

//if user click check reset otp button
if(isset($_POST['check-reset-otp'])){
    $info = "A verification code has been sent to .";
    $_SESSION['info'] = $info;
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM admin WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $fetch_code = $fetch_data['code'];
        $fetch_code_expire = $fetch_data['code_expire'];
        $_SESSION['email'] = $email;
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        if($fetch_code_expire < $currentdate){
            $errors['code_expired'] = "OTP has expired. Click Resend to get a new OTP.";
        }else{
            $code = 0;
            $minutes5 = 0;
            $status = 'verified';
            $update_otp = "UPDATE admin SET code = '$code', code_expire = '$minutes5', status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($conn, $update_otp);
            if($update_res){
                $info = "Please enter a new password.";
                $_SESSION['info'] = $info;
                header('location: change-password');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }
    }else{
        $errors['otp-error'] = "Incorrect code! Please try again.";
    }
}

//if user click change password button
if(isset($_POST['change-password'])){
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password does not match!";
    }else{
        $code = 0;
        $minutes5 = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE admin SET code = $code, code_expire = '$minutes5', password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($conn, $update_pass);
        if($run_query){
            $statusMsg = "success";
            $_SESSION['statusMsg'] = $statusMsg;
            $info = "Change password successful! You can now login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed');
        }else{
            $errors['db-error'] = "Change password failed!";
        }
    }
}

if(isset($_GET['resend'])){
    $email = $_SESSION['email'];
    if(!empty($_SESSION['email2'])){
        $email = $_SESSION['email2'];
    }
    $page = $_GET['page'];

    $check_admin = "SELECT * FROM admin WHERE email = '$email'";
    $admin_res = mysqli_query($conn, $check_admin);
    if(mysqli_num_rows($admin_res) > 0){
        $resend = $_GET['resend'];
        if($resend == 1){
            $code = rand(999999, 111111);
            $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
            $update_code = "UPDATE admin SET code = '$code', code_expire = '$minutes5' WHERE email = '$email'";
            $update_res = mysqli_query($conn, $update_code);
            if($update_res){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "From: mchealthsafetyapp@gmail.com";
                if(mail($email, $subject, $message, $sender)){
                    $_SESSION['statusMsg'] = "primary";
                    $_SESSION['success_message'] = "A verification code has been sent to $email.";
                    $_SESSION['email'] = $email;
                    if($page == 'reset-code'){
                        header('location: reset-code');
                        exit();
                    }elseif($page == 'user-otp'){
                        header('location: user-otp');
                        exit();
                    }
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['otp-error'] = "Failed while resending code!";
            }
        }
    }else{
        $errors['otp-error'] = "User does not exist.";
    }
}


if(isset($_POST['save'])){
    $name =$_POST['name'];

    if(isset($_COOKIE['ad'])){
        $email = $_COOKIE['ad'];
        $admin_id = $_COOKIE['ai'];
    }else{
        $email = $_SESSION['email'];
        $admin_id = $_SESSION['admin_id'];
    }

    $sql = "UPDATE admin SET name = '$name' WHERE `admin_id` = '$admin_id'";
    $result = $conn->query($sql);
    if($result == TRUE){
        session_start();
        $_SESSION['statusMsg'] = "success";
        $_SESSION['name'] = $name;
        $_SESSION['icon'] = "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['alert-success'] = "Change name successful!";
        header('Location: profile');
        exit();
    }else{
        $errors['db-error'] = "Change name failed!";
    }
}


//if user click change password button when logged in
if(isset($_POST['change-password2'])){
    $_SESSION['alert-success'] = "";
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $cpassword = $_POST['cpassword'];
    
    if(isset($_COOKIE['ad'])){
        $email = $_COOKIE['ad'];
        $admin_id = $_COOKIE['ai'];
    }else{
        $email = $_SESSION['email'];
        $admin_id = $_SESSION['admin_id'];
    }

    $check_email = "SELECT * FROM admin WHERE email = '$email'";
    $res = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($old_password, $fetch_pass)){
            if($new_password !== $cpassword){
                $errors['password'] = "Confirm password does not match!";
            }else{
                $code = 0;
                $encpass = password_hash($new_password, PASSWORD_BCRYPT);
                $update_pass = "UPDATE admin SET code = $code, password = '$encpass' WHERE email = '$email'";
                $run_query = mysqli_query($conn, $update_pass);
                if($run_query){
                    session_start();
                    $statusMsg = "success";
                    $_SESSION['statusMsg'] = $statusMsg;
                    $_SESSION['icon'] = "Success";
                    $_SESSION['icon-type'] = "check-circle";
                    $_SESSION['alert-success'] = "Change password successful! You can now login with your new password.";
                    header('Location: profile');
                    exit();
                }else{
                    $errors['db-error'] = "Change password failed!";
                }
            }
        }else{
            $errors['password'] = "The old password is incorrect!";
        }
    }else{
        $errors['password'] = "The password is incorrect!";
    }
}

unset($removedFiles);  
if(isset($_POST['post'])){
    $allowTypes = array('JPG','jpg','PNG','png','JPEG','jpeg','GIF','gif','MP4','mp4', 'MOV', 'mov', 'MKV', 'mkv');
    $maxSize = 41943040; //40MB

    if(isset($_COOKIE['ad'])){
        $email = $_COOKIE['ad'];
    }else{
        $email = $_SESSION['email'];
    }

    $check_unique = "SELECT * FROM admin WHERE email = '$email'"; 
    $unique_res = mysqli_query($conn, $check_unique);
    if($unique_res){
        $fetch_row = mysqli_fetch_assoc($unique_res);
        $admin_unique_id = $fetch_row['admin_unique_id'];
    }

    $announcement = $_POST['announcement'];
    $datetime = strtotime(date("Y-m-d H:i:s"));
    $countfiles = count(array_filter($_FILES['uploadfile']['name']));
    $maxfiles = 4;
    $removedImages = (array) json_decode($_POST['removed_files'], true);
    $countArray = count(array_filter($removedImages));
    

    for($i=0;$i<$countfiles;$i++){
        $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
        $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
        $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
        if(!in_array($extension, $allowTypes)){
            $errors['filetype'] = "1 or more file type is not supported.";
        }

        if($filesize > $maxSize){
            $errors['filesize'] = "1 or more file exceeds file size limit.";
        }

        if($countfiles > 4){
            $errors['maxfiles'] = "Maximum of 4 media files only can be uploaded at a time.";
        }
    }

    if(empty($announcement) && $countfiles == NULL){
        $errors['empty'] = "Both text and file inputs cannot be empty.";
    }

    if($countfiles != NULL){
        if($countfiles <= $countArray){
            $errors['empty'] = "Both text and file inputs cannot be empty.";
        }
    }

    if(count($errors) == 0){
        $sql = "INSERT INTO announcements (admin_unique_id, message, datetime_created) VALUES ('$admin_unique_id','$announcement','$datetime')";
        $result = $conn->query($sql);
        if($result = TRUE){
            $insert_id = $conn->insert_id;
        }

        $folder = "../images/public/uploads/".$insert_id."/";

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        for($i=0;$i<$countfiles;$i++){
            $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
            $file_temp_location = $_FILES["uploadfile"]["tmp_name"][$i];  //temporary file location
            $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
            $filetype = $_FILES["uploadfile"]["type"][$i];  //file size
    
            $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
 
            
            $file_target_location = $folder.$file_name_complete;

            if(in_array($file_name_complete, $removedImages)){
                continue;
            }else{
                move_uploaded_file($file_temp_location, $file_target_location);
            }
        }

        $type = "new_announcement";

        $datetime = strtotime(date("Y-m-d H:i:s"));
        $notify = "INSERT INTO user_notification SET user_id = 'all', datetime = '$datetime', type = '$type'";
        $conn->query($notify);

        $_SESSION['statusMsg'] = "success";
        $_SESSION['icon'] = "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['alert-success'] = "New announcement posted!";
    }
}

if(isset($_POST['save-edit'])){
    $allowTypes = array('JPG','jpg','PNG','png','JPEG','jpeg','GIF','gif','MP4','mp4', 'MOV', 'mov', 'MKV', 'mkv');
    $maxSize = 41943040; //40MB
    $admin_unique_id = $_SESSION['admin_unique_id'];
    $announcement = $_POST['announcement'];
    $datetime = strtotime(date("Y-m-d H:i:s"));
    $countfiles = count(array_filter($_FILES['uploadfile']['name']));
    $maxfiles = 4;
    $removedImages = (array) json_decode($_POST['removed_files'], true);
    $counrArray = count(array_filter($removedImages));
    $removedImages2 = (array) json_decode($_POST['removed_files2'], true);
    $counrArray2 = count(array_filter($removedImages2));

    if(isset($_GET['id'])){
        $announcement_id = $_GET['id'];
        $insert_id = $announcement_id;
    }

    for($i=0;$i<$countfiles;$i++){
        $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
        $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
        $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
        if(!in_array($extension, $allowTypes)){
            $errors['filetype'] = "1 or more file type is not supported.";
        }

        if($filesize > $maxSize){
            $errors['filesize'] = "1 or more file exceeds file size limit.";
        }

        if($countfiles > $maxfiles){
            $errors['maxfiles'] = "Maximum of 4 media files only can be uploaded at a time.";
        }
    }

    $folder = "../images/public/uploads/".$insert_id."/";
    $files = array_slice(scandir($folder), 2);

    $uploaded_count = count($files);

    foreach($files as $file){
        if(in_array($file, $removedImages2)){
            $uploaded_count -= 1;
        }
    }

    $maxfiles2 = $uploaded_count + $countfiles;

    if($maxfiles2 > 4){
        $errors['maxfiles'] = "Maximum of 4 media files only can be uploaded at a time.";
    }

    if(empty($announcement) && $countfiles == NULL && $uploaded_count == 0){
        $errors['empty'] = "Both text and file inputs cannot be empty.";
    }

    // if($countfiles == $counrArray){
    //     $errors['empty'] = "Both text and file inputs cannot be empty.";        
    // }

    if(count($errors) == 0){
        $sql = "UPDATE announcements SET message = '$announcement', datetime_last_modified = '$datetime' WHERE id = '$announcement_id'";
        $result = $conn->query($sql);

        foreach($files as $file){
            if(in_array($file, $removedImages2)){
                unlink($folder.$file);
            }else{
                continue;
            }
        }

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        
        for($i=0;$i<$countfiles;$i++){
            $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
            $file_temp_location = $_FILES["uploadfile"]["tmp_name"][$i];  //temporary file location
            $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
            $filetype = $_FILES["uploadfile"]["type"][$i];  //file size
    
            $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
 
            $file_target_location = $folder.$file_name_complete;

            if(in_array($file_name_complete, $removedImages)){
                continue;
            }else{
                move_uploaded_file($file_temp_location, $file_target_location);
            }
        }
        session_start();
        $_SESSION['statusMsg'] = "success";
        $_SESSION['icon'] = "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['alert-success'] = "Announcement updated.";
        header('Location: announcements');
        exit();
    }
}

if(isset($_GET['delete-id'])){
    $delete_id = $_GET['delete-id'];
    $uploadDir = '../images/public/uploads/'.$delete_id;

    $sql = "DELETE FROM announcements WHERE id = '$delete_id'";
    $result = $conn->query($sql);

    array_map('unlink', glob("$uploadDir/*.*"));

    rmdir($uploadDir);
    

    if ($result == TRUE) {
        session_start();
        $_SESSION['statusMsg'] = "success";
        $_SESSION['icon'] = "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['alert-success'] = "Successfully deleted announcement.";
        header('Location: announcements');
        exit();
    }else{
		echo "Error:" . $sql . "<br>" . $conn->error;
	}
}

if(isset($_GET['report-id'])) {
    $assistance_id = $_GET['report-id'];

    if(isset($_GET['status'])){
        $status = $_GET['status'];
    }

    $type = "report_success";

    $datetime = strtotime(date("Y-m-d H:i:s"));
    $notify = "INSERT INTO user_notification SET user_id = '$assistance_id', datetime = '$datetime', type = '$type'";
    $conn->query($notify);
    
    if($status == "received"){
        $sql = "UPDATE user_assistance SET report_status = 'received' WHERE user_id = '$assistance_id'";
    }

    $result = $conn->query($sql);
    if($result == TRUE){
        $_SESSION['icon'] == "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['statusMsg'] = "success";
        $_SESSION['alert-success'] = "Report marked as ".ucfirst($status).".";
    }else{
        $errors['sql-error'] = "'Error:'. $sql . '<br>'. $conn->error";
    }
    session_start();

    header("location: user-assistance?status=unsettled");
    exit();
}

if(isset($_POST['report-invalid'])){
    $user_id = $_POST['user_id'];
    (isset($_POST['reason1'])) ? $reason1 = $_POST['reason1']."." : $reason1 = "";
    (isset($_POST['reason2'])) ? $reason2 = $_POST['reason2']."." : $reason2 = "";
    (isset($_POST['other-reasons'])) ? $other_reasons = $_POST['other-reasons'] : $other_reasons = "";

    $reasons = $reason1.$reason2.$other_reasons;

    $type = "report_invalid";

    $datetime = strtotime(date("Y-m-d H:i:s"));
    $notify = "INSERT INTO user_notification SET user_id = '$user_id', notification = '$reasons', datetime = '$datetime', type = '$type'";
    $conn->query($notify);

    $update_rstat = "UPDATE user_assistance SET report_status = 'invalid' WHERE user_id = '$user_id'";
    $conn->query($update_rstat);

    $update_cstat = "UPDATE user_profile SET covid_status = '' WHERE user_id = '$user_id'";
    $result = $conn->query($update_cstat);

    if($result == TRUE){
        $_SESSION['icon'] == "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['statusMsg'] = "success";
        $_SESSION['alert-success'] = "Report marked as Invalid.";
    }else{
        $errors['sql-error'] = "'Error:'. $sql . '<br>'. $conn->error";
    }
    session_start();

    header("location: user-assistance?status=unsettled");
    exit();
}

if(isset($_GET['download-id'])){
    $download_id = $_GET['download-id'];
    
    $sql = "SELECT a.user_id, a.user_unique_id, a.phone, b.lastname, b.firstname, b.midname, 
            b.email, b.age, b.sex, b.birthday, b.location, b.covid_status, 
            c.primary_contact, c.primary_relation, c.primary_phone,
            c.secondary_contact, c.secondary_relation, c.secondary_phone,
            d.id, d.datetime, d.report_status,
            e.allergies, e.medications, e.medical_conditions, e.others
            FROM users a
            INNER JOIN user_profile b ON a.user_id= b.user_id
            INNER JOIN user_emergency_contact c ON b.user_id = c.user_id
            INNER JOIN user_assistance d ON c.user_id = d.user_id
            INNER JOIN user_medical_info e ON d.user_id = e.user_id
            WHERE a.user_id = '$download_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $body = '<h3 style=\'text-align: center;\'>MEDICAL INFORMATION FORM</h3>'
            .'<style>
                table, td{
                    border-collapse: collapse;
                }

                table{
                    border: 2px solid black;
                }

                .table-header{
                    font-weight: bold;
                    width: 100px;
                }

                .table-header2{
                    font-weight: bold;
                    width: 200px;
                    text-align: center;
                }

                td{
                    padding: 10px;
                    border: 1px solid black; 
                }
                
            </style>'
            .'<table style=\'width: 100%\'>
                <tr>
                    <td class=\'table-header\'>Name</td>
                    <td class=\'table-data\'>'.$row['lastname'].'</td>
                    <td class=\'table-data\' colspan=\'3\'>'.$row['firstname'].'</td>
                    <td class=\'table-data\'>'.$row['midname'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header\'>Date of Birth</td>
                    <td class=\'table-data\'>'.$row['birthday'].'</td>
                    <td class=\'table-header\'>Age</td>
                    <td class=\'table-data\'>'.$row['age'].'</td>
                    <td class=\'table-header\'>Gender</td>
                    <td class=\'table-data\'>'.$row['sex'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header\'>COVID-19 Status</td>
                    <td class=\'table-data\'>'.ucfirst($row['covid_status']).'</td>
                    <td class=\'table-header\'>Phone</td>
                    <td class=\'table-data\' colspan=\'3\'>'.$row['phone'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header\'>Location</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['location'].'</td>
                </tr>
            </table>
            <br>
            <h4>EMERGENCY CONTACT</h4>
            <table style=\'width: 100%\'>
                <tr>
                    <td class=\'table-header\'>Name</td>
                    <td class=\'table-data\' colspan=\'2\'>'.$row['primary_contact'].'</td>
                    <td class=\'table-header\'>Relationship</td>
                    <td class=\'table-data\' colspan=\'2\'>'.$row['primary_relation'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header\'>Phone</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['primary_phone'].'</td>
                </tr>
            </table>

            <br>
            <h4>SECONDARY EMERGENCY CONTACT</h4>
            <table style=\'width: 100%\'>
                <tr>
                    <td class=\'table-header\'>Name</td>
                    <td class=\'table-data\' colspan=\'2\'>'.$row['secondary_contact'].'</td>
                    <td class=\'table-header\'>Relationship</td>
                    <td class=\'table-data\' colspan=\'2\'>'.$row['secondary_relation'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header\'>Phone</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['secondary_phone'].'</td>
                </tr>
            </table>

            <br>
            <h4>MEDICAL INFORMATION</h4>
            <table style=\'width: 100%\'>
                <tr>
                    <td class=\'table-header2\'>Drug Allergies</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['allergies'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header2\'>Prescribed Medications</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['medications'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header2\'>Health Conditions</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['medical_conditions'].'</td>
                </tr>
                <tr>
                    <td class=\'table-header2\'>Other health or medical information you want us to know about</td>
                    <td class=\'table-data\' colspan=\'5\'>'.$row['others'].'</td>
                </tr>
            </table>';

            require_once '../vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetTitle('User Assistance Report');
            $mpdf->WriteHTML($body);

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->list_indent_first_level = 0; 

            //call watermark content and image
            $mpdf->SetWatermarkText('Marikina City Health & Safety');
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;

            //output in browser
            $mpdf->Output($row['id'].'_'.$row['lastname'].'_'.$row['firstname'].'.pdf', 'I');
        }
    }else{
        $errors['sql-error'] = "'Error:'. $sql . '<br>'. $conn->error";
    }
    session_start();
    header("location: user-assistance?status=unsettled");
    exit();
}

if(isset($_POST['pin-location'])){
    $name = $_POST['location'];
    $address = $_POST['address'];
    $hours = $_POST['hours'];
    $phone = $_POST['phone'];
    $medical_receptionist = $_POST['medical-receptionist'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "INSERT INTO health_centers (`name`,`address`,`hours`,`phone`, `medical_receptionist`,`latitude`,`longitude`) VALUES ('$name', '$address','$hours','$phone', '$medical_receptionist', '$latitude','$longitude')";
    $result = $conn->query($sql);
    if($result == TRUE){
        $_SESSION['icon'] == "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['statusMsg'] = "success";
        $_SESSION['alert-success'] = "Location successfully pinned!";
    }else{
        $errors['sql-error'] = "'Error:'. $sql . '<br>'. $conn->error";
    }
    session_start();
    header("location: health-centers");
    exit();
}

if(isset($_POST['update-pin'])){
    $name = $_POST['location'];
    $address = $_POST['address'];
    $hours = $_POST['hours'];
    $phone = $_POST['phone'];
    $medical_receptionist = $_POST['medical-receptionist'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "UPDATE health_centers SET name = '$name', address = '$address', hours = '$hours', phone = '$phone', medical_receptionist = '$medical_receptionist', latitude = '$latitude', longitude = '$longitude'";

    $result = $conn->query($sql);
    if($result == TRUE){
        $_SESSION['icon'] == "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['statusMsg'] = "success";
        $_SESSION['alert-success'] = "Pinned location successfully updated!";
    }else{
        $errors['sql-error'] = "'Error:'. $sql . '<br>'. $conn->error";
    }
    session_start();
    header("location: health-centers");
    exit();
}

if(isset($_GET['delete-location-id'])){
    $center_id = $_GET['delete-location-id'];

    $sql = "DELETE FROM health_centers WHERE id='$center_id'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        session_start();
        $_SESSION['statusMsg'] = "success";
        $_SESSION['icon'] = "Success";
        $_SESSION['icon-type'] = "check-circle";
        $_SESSION['alert-success'] = "Successfully deleted pinned location.";
        header('Location: health-centers');
        exit();
    }else{
		echo "Error:" . $sql . "<br>" . $conn->error;
	}
}

if(isset($_POST['update-cases'])){

    if(isset($_GET['barangay-id'])){
        $barangay_id = $_GET['barangay-id'];
    }

    $sql2 = "SELECT * FROM barangay_cases WHERE barangay_id = '$barangay_id'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $db_active_cases = $row2['active_cases'];
            $db_recovered_cases = $row2['recovered_cases'];
            $db_death_cases = $row2['death_cases'];
        }
    }

    $active_cases = $_POST['active_cases'];
    $recovered_cases = $_POST['recovered_cases'];
    $death_cases = $_POST['death_cases'];

    if(!empty($barangay_id)){

        if($db_active_cases == $active_cases){
            if($db_recovered_cases == $recovered_cases){
                if($db_death_cases == $death_cases){
                    $_SESSION['statusMsg'] = "primary";
                    $_SESSION['icon'] = "Info";
                    $_SESSION['icon-type'] = "info";
                    $_SESSION['alert-success'] = "No changes made.";
                    header('Location: covid19-cases?barangay-id='.$barangay_id);
                    exit();
                }
            }
        }

        $sql = "UPDATE barangay_cases SET active_cases = '$active_cases', recovered_cases = '$recovered_cases', death_cases = '$death_cases' WHERE barangay_id = '$barangay_id'";
        $result = $conn->query($sql);
        if($result == TRUE){
            session_start();
            $_SESSION['statusMsg'] = "success";
            $_SESSION['icon'] = "Success";
            $_SESSION['icon-type'] = "check-circle";
            $_SESSION['alert-success'] = "Successfully updated barangay COVID-19 cases.";
            header('Location: covid19-cases?barangay-id='.$barangay_id);
            exit();
        }else{
            echo "Error:" . $sql . "<br>" . $conn->error;
        }

    }else{
        $_SESSION['statusMsg'] = "primary";
        $_SESSION['icon'] = "Info";
        $_SESSION['icon-type'] = "info";
        $_SESSION['alert-success'] = "No barangay selected.";
    }
}

//if login now button click
if(isset($_POST['login-now'])){
    header('Location: login');
}

//if logout button is clicked
if(isset($_GET['logout'])){
    if($_GET['logout'] == 1){
        $admin_unique_id = $_SESSION['admin_unique_id'];
        $activity_status = "Offline";
        $sql = "UPDATE admin SET activity_status = '$activity_status' WHERE admin_unique_id = $admin_unique_id";
        $result = $conn->query($sql);

        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('ad', "", time(), '/', $domain, false);
        setcookie('ai', "", time(), '/', $domain, false);
        
        session_unset();
        session_destroy();
        header('location: login');
    }
}
?>
