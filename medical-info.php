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
?>
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
        <form method="POST">
            <div class="row py-4">
                <div class="col-12 col-lg-12 order-lg-first">
                    <a href="emergency-contact"><i class="fas fa-arrow-left" style="font-size: 16px !important; color: var(--button-color);"></i></a>
                </div>

                <div class="col-12 col-lg-12">
                    <span class="login-text">Medical Information</span>
                </div>

                <div class="col-12 col-lg-12" style="text-align: center;">
                    <span style="font-size: 12px;">Edit your medical information</span>
                </div>

                <div class="col-12 col-lg-12 mt-4">
                    <span style="color: red;">* Required</span>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="allergies">Drug Allergies</label><br/>
                    <textarea name="allergies" class="login-credentials2" id="allergies" placeholder="e.g.&#10;Penicillin&#10;Amoxicillin&#10;Ibuprofen" cols="30" rows="10"></textarea>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    <label for="medications">Prescribed Medications</label><br/>
                    <textarea name="medications" class="login-credentials2" id="medications" placeholder="e.g.&#10;Amoxicillin&#10;Vitamin D&#10;Ibuprofen" cols="30" rows="10"></textarea>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="conditions">Medical Conditions</label><br/>
                    <textarea name="conditions" class="login-credentials2" id="conditions" placeholder="e.g.&#10;Ischemic heart disease&#10;Cancer&#10;Pneumonia" cols="30" rows="10"></textarea>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    <label for="other-conditions">Other Conditions</label><br/>
                    <textarea name="other-conditions" class="login-credentials2" id="other-conditions" placeholder="Other health or medical information you want us to know about." cols="30" rows="10"></textarea>
                </div>

                <div class="col-12 col-lg-12 mt-5" style="position: relative; bottom: 0;">
                    <div class="col-12 col-lg-12 mt-3"> 
                        <a href="home"><button type="button" class="cancel-button">Not Now</button></a>
                    </div>
                    <div class="col-12 col-lg-12 mt-3"> 
                        <input class="login-button" type="submit" name="medical-info" value="Create Medical Information">
				    </div>
                </div>
            </div>        
         </form>
    </div>
    <script src="javascript/togglePassword.js?v=<?php echo time(); ?>"></script>
</body>

<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

</html>