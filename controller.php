<?php 
session_start();
require "connection.php";
include "phpqrcode/qrlib.php";

date_default_timezone_set("Asia/Manila");
$errors = array();
$removedImages = "";

if(isset($_POST['create-user'])){

    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = $_SESSION['phone'];
    $tel = substr($tel, 3);
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }

    $password = $_POST['pin1'];
    $cpassword = $_POST['pin2'];

    if($cpassword != $password){
        $errors['pass'] = "PIN does not match.";
    }

    $phone_check = "SELECT * FROM users WHERE phone = '$phone'";
    $res = mysqli_query($conn, $phone_check);
    if(mysqli_num_rows($res) > 0){
        $errors['phone'] = "The phone you have entered is already registered!";
    }

    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $ran_id = rand(time(), 100000000);
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
        $status = "notverified";

        $insert_data = "INSERT INTO users (user_unique_id, phone, password, code, code_expire, status)
                        values('$ran_id', '$phone', '$encpass', '$code', '$minutes5','$status')";
        $data_check = mysqli_query($conn, $insert_data);


        if($data_check){
            $insert_id = $conn->insert_id;
            $folder = "images/public/uploads/users/".$insert_id."/";
    
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            mkdir($folder."test_result/", 0777, true);
            mkdir($folder."qr_code/", 0777, true);

            require_once 'vendor/autoload.php';
            $messagebird = new MessageBird\Client('TiJQvuz2dB04S9bzFvkbhAiwf');
            $message = new MessageBird\Objects\Message;
            $message->originator = '+639563500824';
            $message->recipients = $phone;
            $message->body = 'Your Marikina Health and Safety App OTP-code is '.$code.'. Thank you!';
            $response = $messagebird->messages->create($message);
            $_SESSION['phone'] = $phone; 
            header('location: user-otp');
            exit();
        }else{
            $errors['db-error'] = "Failed while inserting data to database!";
        }
    }
}

if(isset($_POST['login1'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = mysqli_real_escape_string($conn, $_POST['phone']);
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }
    
    $pin_check = "SELECT * FROM users WHERE phone = '$phone'";
    $res = mysqli_query($conn, $pin_check);
    if(mysqli_num_rows($res) > 0){
        $_SESSION['phone'] = $phone;
        header("Location: login2");
    } else {
        $errors['phone'] = "The phone you have entered does not exist!";
    }   
}

if(isset($_POST['user-pin'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = mysqli_real_escape_string($conn, $_POST['phone']);
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }
    
    $_SESSION['phone'] = $phone;
    $tc_pp = "";
    if(!empty($_POST['accept'])) {
        $tc_pp = $_POST['accept'];
    }
    if($tc_pp != "accept"){
        $errors['accept'] = "You have not agreed with our Terms & Conditions and Privacy Policy.";
    } else {
        $pin_check = "SELECT * FROM users WHERE phone = '$phone'";
        $res = mysqli_query($conn, $pin_check);
        if(mysqli_num_rows($res) > 0){
            $errors['phone'] = "The phone you have entered is already registered!";
        } 
        header("Location: user-pin");
    }
}

if(isset($_POST['pin'])){
    $pin = $_POST['pin1'];
    $_SESSION['pin'] = $pin;
    header("Location: confirm-user-pin");
} 


if(isset($_POST['login-user'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = $_SESSION['phone'];
    $tel = substr($tel, 3);
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }

    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $check_phone = "SELECT * FROM users WHERE phone = '$phone'";
    $res = mysqli_query($conn, $check_phone);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['phone'] = $phone;
            $status = $fetch['status'];
            $user_id = $fetch['user_id'];
            $user_unique_id = $fetch['user_unique_id'];
            $activity_status = "Online";
            if($status == 'verified'){
                $sql = "UPDATE users SET activity_status = '$activity_status' WHERE user_unique_id = $user_unique_id";
                $result = $conn->query($sql);
                $_SESSION['phone'] = $phone;
                $_SESSION['password'] = $password;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_unique_id'] = $user_unique_id;

                $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                setcookie("ph", $phone, time()+3600, '/', $domain, false);
                setcookie("ui", $user_id, time()+3600, '/', $domain, false);

                $check_profile = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
                $profile_result = mysqli_query($conn, $check_profile);
                if(mysqli_num_rows($profile_result) > 0){
                    $fetch = mysqli_fetch_assoc($profile_result);
                    $_SESSION['lastname'] = $fetch['lastname'];
                    $_SESSION['firstname'] = $fetch['firstname'];
                    $_SESSION['midname'] = $fetch['midname'];

                    header('location: home');
                }else{
                    header('location: profile-setup');
                }
            }else{
                header('location: user-otp');
            }
        }else{
            $errors['email'] = "The PIN is incorrect!";
        }
    }else{
        $errors['email'] = "The number does not exist.";
    }
}

if(isset($_POST['recover-user'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = mysqli_real_escape_string($conn, $_POST['phone']);
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }

    $check_phone = "SELECT * FROM users WHERE phone = '$phone'";
    $run_sql = mysqli_query($conn, $check_phone);
    if(mysqli_num_rows($run_sql) > 0){
        $_SESSION['phone'] = $phone;
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
        $insert_code = "UPDATE users SET code = $code, code_expire = '$minutes5' WHERE phone = '$phone'";
        $run_query =  mysqli_query($conn, $insert_code);
        if($run_query){
            require_once 'vendor/autoload.php';
            $messagebird = new MessageBird\Client('TiJQvuz2dB04S9bzFvkbhAiwf');
            $message = new MessageBird\Objects\Message;
            $message->originator = '+639563500824';
            $message->recipients = $phone;
            $message->body = 'Your Marikina Health and Safety App OTP-code is '.$code.'. Thank you!';
            $response = $messagebird->messages->create($message);
            header('location: reset-code');
            exit();
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }else{
        $errors['email'] = "Your search did not return any result. Please try again.";
    }
}

if(isset($_POST['submit-info'])){
    //Create user id folder name
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $path = "images/public/uploads/users/".$user_id."/";
    $path = $path."qr_code/";
    $files = array_slice(scandir($path), 2);

    $uploaded_count = count($files);

    foreach($files as $file){
        unlink($path.$file);
    }

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    //filename of QR Code Image
    $qrImgName = "MarikinaHealthandSafety".rand();
    $qrImg = $path.$qrImgName.".png";

    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $birthday = $_POST['birthday'];
    $location = $_POST['location'];
    
    $content = $lastname;
    $content .= $firstname;
    $content .= $midname;
    $content .= $age;
    $content .= $sex;
    $content .= $birthday;
    $content .= $location;
    QRcode::png($content, $qrImg, 'L', 4, 4);

    $action = "";
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }

    $check_user = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
    $run_sql = mysqli_query($conn, $check_user);
    if(mysqli_num_rows($run_sql) > 0){
        $sql = "UPDATE user_profile set lastname = '$lastname', firstname = '$firstname', midname = '$midname', email = '$email', age = '$age', sex = '$sex', birthday = '$birthday', location = '$location' WHERE user_id = '$user_id'";
    }else{
        $sql = "INSERT INTO user_profile (user_id, lastname, firstname, midname, email, age, sex, birthday, location) VALUES ('$user_id', '$lastname', '$firstname', '$midname', '$email', '$age', '$sex', '$birthday', '$location')";
    }
    $result = $conn->query($sql);
    
    if($result){
        if($action == "edit"){
            $_SESSION['lastname'] = $lastname;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['midname'] = $midname;
            $_SESSION['email'] = $email;
            $_SESSION['age'] = $age;
            $_SESSION['sex'] = $sex;
            $_SESSION['birthday'] = $birthday;
            $_SESSION['location'] = $location;
            header("Location: profile?form=pinfo");
        }else{
            $_SESSION['lastname'] = $lastname;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['midname'] = $midname;
            $_SESSION['email'] = $email;
            $_SESSION['age'] = $age;
            $_SESSION['sex'] = $sex;
            $_SESSION['birthday'] = $birthday;
            $_SESSION['location'] = $location;
            header("Location: emergency-contact");
        }
    }
}

if(isset($_POST['download'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $dirname = "images/public/uploads/users/".$user_id."/";
    $dirname = $dirname."qr_code/";
    $scanned_directory = array_diff(scandir($dirname), array('..', '.'));
    foreach($scanned_directory as $key => $image) {
        $file_name = $image;
        header("Cache-Control: public");
        header("Content-Disposition: attachment; filename = $file_name");
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: binary");
        readfile($dirname.$image);
        exit;  
    }
}      


if(isset($_POST['emergency-info'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }

    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }

    $primary_contact = $_POST['primary_contact'];
    $primary_relation = $_POST['primary_relation'];
    $tel = $_POST['primary_phone'];
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $primary_phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }
    $secondary_contact = $_POST['secondary_contact'];
    $secondary_relation = $_POST['secondary_relation'];
    $tel = $_POST['secondary_phone'];
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $secondary_phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }

    $action = "";
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }
    
    $sql = "SELECT * FROM user_emergency_contact WHERE user_id = '$user_id'";
    $run_sql = mysqli_query($conn, $sql);
    if(mysqli_num_rows($run_sql) > 0){
        $sql = "UPDATE user_emergency_contact SET primary_contact = '$primary_contact', primary_relation = '$primary_relation', primary_phone = '$primary_phone', secondary_contact = '$secondary_contact', secondary_relation = '$secondary_relation', secondary_phone = '$secondary_phone' WHERE user_id = '$user_id'";
    }else{
        $sql = "INSERT INTO user_emergency_contact (user_id, primary_contact, primary_relation, primary_phone, secondary_contact, secondary_relation, secondary_phone) VALUES ('$user_id', '$primary_contact', '$primary_relation', '$primary_phone', '$secondary_contact', '$secondary_relation', '$secondary_phone')";
    }

    $result = $conn->query($sql);

    if($result){
        if($action == "edit"){
            header("Location: profile?form=econ");
        }else{
            $_SESSION['primary_contact'] = $primary_contact;
            $_SESSION['primary_relation'] = $primary_relation; 
            $_SESSION['primary_phone'] = $primary_phone;
            $_SESSION['secondary_contact'] = $secondary_contact;
            $_SESSION['secondary_relation'] = $secondary_relation;
            $_SESSION['secondary_phone'] = $secondary_phone;
            header("Location: medical-info");
        }
    }
}

if(isset($_POST['medical-info'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $allergies = $_POST['allergies'];
    $medications = $_POST['medications'];
    $conditions = $_POST['conditions'];
    $other_conditions = $_POST['other-conditions'];

    $action = "";
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }

    $sql = "SELECT * FROM user_medical_info";
    $run_sql = mysqli_query($conn, $sql);
    if(mysqli_num_rows($run_sql) > 0){
        if(empty($allergies) && empty($medications) && empty($conditions) && empty($other_conditions)){
            $sql = "DELETE FROM user_medical_info WHERE user_id = '$user_id'";
        }else{
            $sql = "UPDATE user_medical_info SET allergies = '$allergies', medications = '$medications', medical_conditions = '$conditions', others = '$other_conditions' WHERE user_id = '$user_id'";
        }
    }else{
        $sql = "INSERT INTO user_medical_info (user_id, allergies, medications, medical_conditions, others) VALUES ('$user_id', '$allergies', '$medications', '$conditions', '$other_conditions')";
    }
    $result = $conn->query($sql);
    
    if($result){
        if($action == "edit"){
            header("Location: profile?form=minfo");
        }else{
            header("Location: home");
        }
    }

}

if(isset($_POST['save-log'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $id_visit = $_POST['id-visit'];
    $loc_visit = $_POST['loc_visit'];
    $date_visit = $_POST['date_visit'];
    $temp = $_POST['temp'];
    $date_visit = $_POST['date_visit'];
    $companions = $_POST['companion'];

    if(!empty($id_visit)){
        $sql = "UPDATE user_contact_tracing_log SET loc_visit = '$loc_visit', temperature = '$temp', date_visit = '$date_visit', companions = '$companions' WHERE id = '$id_visit' AND user_id = '$user_id'";
    }else{
        $sql = "INSERT INTO user_contact_tracing_log (user_id, loc_visit, temperature, date_visit, companions) VALUES ('$user_id', '$loc_visit', '$temp', '$date_visit', '$companions')";
    }

    $result = $conn->query($sql);
    if($result){
        header("Location: profile?form=vlog");
    }
}

if(isset($_GET['del-vlog'])){
    $id_visit = $_GET['del-vlog'];
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }

    $sql = "DELETE FROM user_contact_tracing_log WHERE id = '$id_visit' AND user_id = '$user_id'";
    $result = $conn->query($sql);

    if($result){
        header('Location: profile?form=vlog');
    }
}

if(isset($_POST['update-acc'])){
    function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }
    
    function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }
        
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 
    
        //are we left with digits only?
        return isDigits($telephone, $minDigits, $maxDigits); 
    }
    
    function normalizeTelephoneNumber(string $telephone): string {
        //remove white space, dots, hyphens and brackets
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
    
    $tel = $_POST['phone'];
    if (isValidTelephoneNumber($tel)) {
        //normalize telephone number if needed
        $phone = "+63".normalizeTelephoneNumber($tel); //+91123456789
    }

    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }

    $phone_check = "SELECT * FROM users WHERE phone = '$phone'";
    $res = mysqli_query($conn, $phone_check);
    if(mysqli_num_rows($res) > 0){
        $errors['phone'] = "The phone you have entered is already registered!";
    }

    if(count($errors) === 0){
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;

        $insert_code = "UPDATE users SET code = $code, code_expire = '$minutes5' WHERE user_id = '$user_id'";
        $run_query =  mysqli_query($conn, $insert_code);
        if($run_query){
            require_once 'vendor/autoload.php';
            $messagebird = new MessageBird\Client('TiJQvuz2dB04S9bzFvkbhAiwf');
            $message = new MessageBird\Objects\Message;
            $message->originator = '+639563500824';
            $message->recipients = $phone;
            $message->body = 'Your Marikina Health and Safety App OTP-code is '.$code.'. Thank you!';
            $response = $messagebird->messages->create($message);
            $_SESSION['phone2'] = $phone; 
            header('location: update-phone-otp');
            exit();
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }
}

//if logout button is clicked
if(isset($_GET['logout'])){
    if($_GET['logout'] == 1){
        $user_unique_id = $_SESSION['user_unique_id'];
        $activity_status = "Offline";
        $sql = "UPDATE users SET activity_status = '$activity_status' WHERE user_unique_id = $user_unique_id";
        $result = $conn->query($sql);
        session_unset();
        session_destroy();

        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie("ph", "", time()-3600, '/', $domain, false);
        setcookie("ui", "", time()-3600, '/', $domain, false);
        header('location: login');
    }
}

if(isset($_POST['submit-message'])){
    $recipient = $_POST['recipient'];
    $message = $_POST['message'];
    $sender = $_SESSION['user_unique_id'];
    $datetime = strtotime(date('Y-m-d H:i:s'));
    $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, datetime) VALUES ('$recipient', '$sender', '$message', '$datetime')";
    $result = $conn->query($sql);
    header('location: messages');
}

if(isset($_POST['verify-otp'])){
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp-code']);
    $check_code = "SELECT * FROM users WHERE code = '$otp_code'";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $fetch_code_expire = $fetch_data['code_expire'];
        $phone = $fetch_data['phone'];
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        if($fetch_code_expire < $currentdate){
            $errors['code_expired'] = "OTP has expired. Press Resend to get a new OTP.";
        }else{
            $code = 0;
            $minutes5 = 0;
            $status = 'verified';
            $update_otp = "UPDATE users SET code = '$code', code_expire = '$minutes5', status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($conn, $update_otp);
            if($update_res){
                $_SESSION['phone'] = $phone;
                header('location:account-verified');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }
    }else{
        $errors['otp-error'] = "Incorrect code! Please try again.";
    }
}

if(isset($_POST['verify-otp2'])){
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp-code']);
    $check_code = "SELECT * FROM users WHERE code = '$otp_code'";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $fetch_code_expire = $fetch_data['code_expire'];
        $phone = $fetch_data['phone'];
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        if($fetch_code_expire < $currentdate){
            $errors['code_expired'] = "OTP has expired. Press Resend to get a new OTP.";
        }else{
            $code = 0;
            $minutes5 = 0;
            $status = 'verified';
            $update_otp = "UPDATE users SET code = $code, code_expire = '$minutes5', status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($conn, $update_otp);
            if($update_res){
                $_SESSION['phone'] = $phone;
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

if(isset($_POST['verify-otp3'])){
    $phone = $_SESSION['phone2'];
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp-code']);
    $check_code = "SELECT * FROM users WHERE code = '$otp_code'";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $fetch_code_expire = $fetch_data['code_expire'];
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        if($fetch_code_expire < $currentdate){
            $errors['code_expired'] = "OTP has expired. Press Resend to get a new OTP.";
        }else{
            $code = 0;
            $minutes5 = 0;
            $status = 'verified';
            $update_otp = "UPDATE users SET phone = '$phone', code = '$code', code_expire = '$minutes5', status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($conn, $update_otp);
            if($update_res){
                $_SESSION['phone'] = $phone;
                $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                setcookie("ph", $phone, time()+3600, '/', $domain, false);
                header('location: manage-account');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }
    }else{
        $errors['otp-error'] = "Incorrect code! Please try again.";
    }
}

if(isset($_POST['verify-password'])){
    if(isset($_COOKIE['ph'])){
        $phone = $_COOKIE['ph'];
    }else{
        $phone = $_SESSION['phone'];
    }
    $form = $_POST['form'];

    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $check_phone = "SELECT * FROM users WHERE phone = '$phone'";
    $res = mysqli_query($conn, $check_phone);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            if($form == 'number'){
                header("Location: manage-account?action=edit&form=".$form);
            }elseif($form == 'password'){
                header("Location: manage-account?action=edit&form=".$form);
            }
        }else{
            $errors['email'] = "The PIN is incorrect!";
        }
    }
}

if(isset($_POST['update-acc-pass'])){
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($confirm_password != $new_password){
        $errors['pass'] = "PIN does not match.";
    }

    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }

    if(count($errors) == 0){
        $check_acc = "SELECT * FROM users WHERE user_id = '$user_id'";
        $res = mysqli_query($conn, $check_acc);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            $code = 0;
            $encpass = password_hash($new_password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE users SET code = $code, password = '$encpass' WHERE user_id = '$user_id'";
            $run_query = mysqli_query($conn, $update_pass);

            if($run_query){
                $_SESSION['password'] = $new_password;
                header('Location: manage-account');
                exit();
            }else{
                $errors['db-error'] = "Change PIN failed!";
            }
        }
    }  
}

if(isset($_GET['resend'])){
    $phone = $_SESSION['phone'];

    $check_user = "SELECT * FROM users WHERE phone = '$phone'";
    $user_res = mysqli_query($conn, $check_user);
    if(mysqli_num_rows($user_res) > 0){
        $resend = $_GET['resend'];
        if($resend == 1){
            $code = rand(999999, 111111);
            $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
            $update_code = "UPDATE users SET code = '$code', code_expire = '$minutes5' WHERE phone = '$phone'";
            $update_res = mysqli_query($conn, $update_code);
            if($update_res){
                require_once 'vendor/autoload.php';
                $messagebird = new MessageBird\Client('TiJQvuz2dB04S9bzFvkbhAiwf');
                $message = new MessageBird\Objects\Message;
                $message->originator = '+639563500824';
                $message->recipients = $phone;
                $message->body = 'Your Marikina Health and Safety App OTP-code is '.$code.'. Thank you!';
                $response = $messagebird->messages->create($message);
                header('location: user-otp');
                exit();
            }else{
                $errors['otp-error'] = "Failed while resending code!";
            }
        }
    }else{
        $errors['otp-error'] = "User does not exist.";
    }
}

if(isset($_GET['resend2'])){
    $phone = $_SESSION['phone2'];
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }

    $resend = $_GET['resend2'];
    if($resend == 1){
        $code = rand(999999, 111111);
        $minutes5 = strtotime(date("Y-m-d H:i:s")) + 300;
        $update_code = "UPDATE users SET code = '$code', code_expire = '$minutes5' WHERE user_id = '$user_id'";
        $update_res = mysqli_query($conn, $update_code);
        if($update_res){
            require_once 'vendor/autoload.php';
            $messagebird = new MessageBird\Client('TiJQvuz2dB04S9bzFvkbhAiwf');
            $message = new MessageBird\Objects\Message;
            $message->originator = '+639563500824';
            $message->recipients = $phone;
            $message->body = 'Your Marikina Health and Safety App OTP-code is '.$code.'. Thank you!';
            $response = $messagebird->messages->create($message);
            header('location: update-phone-otp');
            exit();
        }else{
            $errors['otp-error'] = "Failed while resending code!";
        }
    }
}

if(isset($_POST['change-password'])){
    $phone = $_SESSION['phone'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "PIN does not match!";
    }else{
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE users SET password = '$encpass' WHERE phone = '$phone'";
        $run_query = mysqli_query($conn, $update_pass);
        if($run_query){
            header('Location: password-changed');
        }else{
            $errors['db-error'] = "Change PIN failed!";
        }
    }
}

if(isset($_POST['change-pin1'])){
    $_SESSION['changepin1'] = $_POST['password'];
    header("Location: change-password2");
}

//manage password
if(isset($_POST['cnew_password'])){
    $_SESSION['new_password'] = $_POST['new_password'];
    header("Location: manage-account?action=edit&form=password2");
}



unset($removedFiles); 
if(isset($_POST['update_stat'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $status = $_POST['status'];
    $notify = $_POST['notify'];
    $datetime = strtotime(date("Y-m-d H:i:s"));

    $folder = "images/public/uploads/users/".$user_id."/";
    $folder = $folder."test_result/";
    $files = array_slice(scandir($folder), 2);

    $uploaded_count = count($files);

    if($uploaded_count == 0){
        $errors['proof'] = "You need to upload your COVID-19 test result first to use this function.";
    }else{
        if($status == "positive"){
            if($notify == "notify"){
                $sql = "SELECT * FROM user_emergency_contact WHERE user_id = '$user_id'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    $fetch = mysqli_fetch_assoc($result);
                    $primary_contact = $fetch['primary_contact'];
                    $primary_relation = $fetch['primary_relation'];
                    $primary_phone = $fetch['primary_phone'];

                    if($primary_contact != "" && $primary_relation != "" && $primary_phone != ""){
                        $sql = "UPDATE user_profile SET covid_status = '$status' WHERE user_id = '$user_id'";
                        $result = $conn->query($sql);
                        if($result){
                            $sql = "INSERT INTO user_assistance (user_id, datetime, report_status) VALUES ('$user_id', '$datetime', 'unsettled')";
                            $result = $conn->query($sql);
                            if($result){
                                header("Location: home");
                            }
                        }
                    }else{
                        $errors['error-msg'] = "Primary Emergency Contact must be filled out first before using this function.";
                    }
                }else{     
                    $errors['error-msg'] = "Primary Emergency Contact must be filled out first before using this function.";
                }
            }elseif($notify == "no"){
                $sql = "UPDATE user_profile SET covid_status = '$status' WHERE user_id = '$user_id'";
                $result = $conn->query($sql);
                if($result){
                    header("Location: home");
                }
            }
        }elseif($status == "negative"){
            $sql = "UPDATE user_profile SET covid_status = '$status' WHERE user_id = '$user_id'";
            $result = $conn->query($sql);
            if($result){
                header("Location: home");
            }
        }  
    }
}

if(isset($_POST['save-result'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $allowTypes = array('jpg','jpeg','png','doc','docx','pdf','mp3');
    $maxSize = 41943040; //40MB
    $countfiles = count(array_filter($_FILES['uploadfile']['name']));
    $maxfiles = 1;
    $removedImages = (array) json_decode($_POST['removed_files'], true);
    $countArray = count(array_filter($removedImages));

    for($i=0;$i<$countfiles;$i++){
        $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
        $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
        $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
        if(!in_array($extension, $allowTypes)){
            $errors['filetype'] = "File type is not supported.";
        }

        if($filesize > $maxSize){
            $errors['filesize'] = "File exceeds file size limit.";
        }

        if($countfiles > 4){
            $errors['maxfiles'] = "Maximum of 1 file only can be uploaded at a time.";
        }
    }

    if($countfiles == NULL){
        $errors['empty'] = "File input cannot be empty.";
    }

    if($countfiles != NULL){
        if($countfiles <= $countArray){
            $errors['empty'] = "File input cannot be empty.";
        }
    }

    if(count($errors) == 0){
        $folder = "images/public/uploads/users/".$user_id."/";
        $folder = $folder."test_result/";

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        for($i=0;$i<$countfiles;$i++){
            $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
            $file_temp_location = $_FILES["uploadfile"]["tmp_name"][$i];  //temporary file location
            $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
            $filetype = $_FILES["uploadfile"]["type"][$i];  //file size
    
            $extension = strtolower(pathinfo($file_name_complete, PATHINFO_EXTENSION));
 
            
            $file_target_location = $folder.$file_name_complete;

            if(in_array($file_name_complete, $removedImages)){
                continue;
            }else{
                move_uploaded_file($file_temp_location, $file_target_location);
            }
        }
    }
}

if(isset($_POST['change-result'])){
    if(isset($_COOKIE['ui'])){
        $user_id = $_COOKIE['ui'];
    }else{
        $user_id = $_SESSION['user_id'];
    }
    $allowTypes = array('jpg','jpeg','png','doc','docx','pdf','mp3');
    $maxSize = 41943040; //40MB
    $countfiles = count(array_filter($_FILES['uploadfile']['name']));
    $maxfiles = 1;
    $removedImages = (array) json_decode($_POST['removed_files'], true);
    $counrArray = count(array_filter($removedImages));
    $removedImages2 = (array) json_decode($_POST['removed_files2'], true);
    $counrArray2 = count(array_filter($removedImages2));

    for($i=0;$i<$countfiles;$i++){
        $file_name_complete = $_FILES["uploadfile"]["name"][$i]; //filename with extension
        $filesize = $_FILES["uploadfile"]["size"][$i];  //file size
        $extension = pathinfo($file_name_complete, PATHINFO_EXTENSION);
        if(!in_array($extension, $allowTypes)){
            $errors['filetype'] = "File type is not supported.";
        }

        if($filesize > $maxSize){
            $errors['filesize'] = "File exceeds file size limit.";
        }

        if($countfiles > $maxfiles){
            $errors['maxfiles'] = "Maximum of 1 media file only can be uploaded at a time.";
        }
    }

    $folder = "images/public/uploads/users/".$user_id."/";
    $folder = $folder."test_result/";
    $files = array_slice(scandir($folder), 2);

    $uploaded_count = count($files);

    foreach($files as $file){
        if(in_array($file, $removedImages2)){
            $uploaded_count -= 1;
        }
    }

    $maxfiles2 = $uploaded_count + $countfiles;

    if($maxfiles2 > 1){
        $errors['maxfiles'] = "Maximum of 1 media file only can be uploaded at a time.";
    }

    // if($countfiles == NULL && $uploaded_count == 0){
    //     $errors['empty'] = "File input cannot be empty.";
    // }

    if(count($errors) == 0){
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
    
            $extension = strtolower(pathinfo($file_name_complete, PATHINFO_EXTENSION));
 
            $file_target_location = $folder.$file_name_complete;

            if(in_array($file_name_complete, $removedImages)){
                continue;
            }else{
                move_uploaded_file($file_temp_location, $file_target_location);
            }
        }

        $files = array_slice(scandir($folder), 2);

        $uploaded_count = count($files);

        if($uploaded_count == 0){
            $sql = "UPDATE user_profile SET covid_status = '' WHERE user_id = '$user_id'";
            $conn->query($sql);

            $sql = "DELETE FROM user_assistance WHERE user_id = '$user_id'";
            $conn->query($sql);

            $datetime = strtotime(date("Y-m-d H:i:s"));
            $not = "INSERT INTO user_notification (user_id, datetime, type) VALUES ('$user_id', '$datetime', 'proof_deleted')";
            $conn->query($not);
        }
        header('Location: home');
    }
}
?>