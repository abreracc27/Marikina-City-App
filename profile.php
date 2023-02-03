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

$sql2 = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
$result2 = mysqli_query($conn, $sql2);
if(mysqli_num_rows($result2) > 0){
    $row = mysqli_fetch_assoc($result2);
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $midname = $row['midname'];
    $sex = $row['sex'];
    $age = $row['age'];
    $birthday = $row['birthday'];
    $email = $row['email'];
    $location = $row['location'];
}

$primary_contact = "";
$primary_relation = "";
$primary_phone = "";
$secondary_contact = "";
$secondary_relation = "";
$secondary_phone = "";
$sql3 = "SELECT * FROM user_emergency_contact WHERE user_id = '$user_id'";
$result3 = mysqli_query($conn, $sql3);
if(mysqli_num_rows($result3) > 0){
    $row3 = mysqli_fetch_assoc($result3);
    $primary_contact = $row3['primary_contact'];
    $primary_relation = $row3['primary_relation'];
    $primary_phone = $row3['primary_phone'];
    $secondary_contact = $row3['secondary_contact'];
    $secondary_relation = $row3['secondary_relation'];
    $secondary_phone = $row3['secondary_phone'];
}


$allergies = "";
$medications = "";
$conditions = "";
$other_conditions = "";
$sql4 = "SELECT * FROM user_medical_info WHERE user_id = '$user_id'";
$result4 = mysqli_query($conn, $sql4);
if(mysqli_num_rows($result4) > 0){
    $row4 = mysqli_fetch_assoc($result4);
    $allergies = $row4['allergies'];
    $medications = $row4['medications'];
    $conditions = $row4['medical_conditions'];
    $other_conditions = $row4['others'];
}

$sql5 = "SELECT * FROM user_contact_tracing_log WHERE user_id = '$user_id'";
$result5 = $conn->query($sql5);

$form = "";
$action = "";
if(isset($_GET['form'])){
    $form = $_GET['form'];
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
}

$phone = $_SESSION['phone'];

$id_visit = "";
$loc_visit = "";
$date_visit = "";
$temp = "";
$companions = "";
if(isset($_GET['edit-vlog'])){
    $vlog_id = $_GET['edit-vlog'];
    $user_id = $_SESSION['user_id'];

    $vlog = "SELECT * FROM user_contact_tracing_log WHERE id = '$vlog_id' AND user_id = '$user_id'";
    $vlog_res = mysqli_query($conn, $vlog);
    if(mysqli_num_rows($vlog_res) > 0){
        $row_vlog = mysqli_fetch_assoc($vlog_res);
        $id_visit = $row_vlog['id'];
        $loc_visit = $row_vlog['loc_visit'];
        $temp = $row_vlog['temperature'];
        $date_visit = $row_vlog['date_visit'];
        $companions = $row_vlog['companions'];
    }
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

<header class="header">
    <?php 
    if($action == "edit" && $form == "pinfo"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile?form=pinfo" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 
    <?php }elseif($action == "edit" && $form == "econ"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile?form=econ" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 
    <?php }elseif($action == "edit" && $form == "minfo"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile?form=minfo" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div>
    <?php }elseif($action == "edit" && $form == "vlog"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile?form=vlog" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div>
    <?php } ?>
        
    <?php if($action == "" && $form == "pinfo"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 

        <div style="position: absolute; width: 45px; text-align: center; right: 15px;">
            <a href="profile?action=edit&form=pinfo" style="text-decoration: none;">
                <span style="line-height: 44px; color: var(--button-color); font-size: 16px !important;">Edit</span>
            </a>
        </div>
    <?php }elseif($action == "" && $form == "econ"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 

        <div style="position: absolute; width: 45px; text-align: center; right: 15px;">
            <a href="profile?action=edit&form=econ" style="text-decoration: none;">
                <span style="line-height: 44px; color: var(--button-color); font-size: 16px !important;">Edit</span>
            </a>
        </div>
    <?php }elseif($action == "" && $form == "minfo"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 
        
        <div style="position: absolute; width: 45px; text-align: center; right: 15px;">
            <a href="profile?action=edit&form=minfo" style="text-decoration: none;">
                <span style="line-height: 44px; color: var(--button-color); font-size: 16px !important;">Edit</span>
            </a>
        </div>
    <?php }elseif($action == "" && $form == "vlog"){?>
        <div style="width: 45px; text-align: center;">
            <a href="profile" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">arrow_back</i>
            </a>
        </div> 

        <div style="position: absolute; width: 45px; text-align: center; right: 15px;">
            <a href="profile?action=edit&form=vlog" style="text-decoration: none;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">add</i>
            </a>
        </div>
    <?php } ?>
    
    <?php if($action == "" && $form == ""){?>
    <div style="width: 45px; text-align: center;">
        <button id="btnSidenav" style="width: 45px; border: none; background: transparent;">
            <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">manage_accounts</i>
        </button>
    </div>  

    <div style="position: absolute; width: 45px; text-align: center; right: 15px;">
        <button id="btnQR" class="btnQR">
            <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">qr_code</i>
        </button>
    </div>
    <?php } ?>
  
</header>   

<?php include "php/sidenav.php"; ?>

<div class="main-content message-area">
    <div class="profile-center-area">
    <?php
    if($action == "edit" && $form == "pinfo"){?>
        <section class="section">
            <form method="POST">
                <div class="row py-4">
                    <div class="col-12 col-lg-12">
                        <span class="login-text">Basic Personal Information</span>
                    </div>

                    <div class="col-12 col-lg-12" style="text-align: center;">
                        <span style="font-size: 12px;">Edit your personal information</span>
                    </div>

                    <div class="col-12 col-lg-12 mt-4">
                        <span style="color: red;">* Required</span>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="firstname">First Name</label> <span style="color: red;">*</span><br/>
                        <input type="text" id="firstname" class="login-credentials" placeholder="e.g. Juan" name="firstname" value="<?php echo $firstname; ?>" required>
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="lastname">Last Name</label> <span style="color: red;">*</span><br/>
                        <input id="lastname" type="text" class="login-credentials" name="lastname" placeholder="e.g. Dela Cruz" value="<?php echo $lastname; ?>" required />  
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="midname">Middle Name</label><br/>
                        <input type="text" id="midname" class="login-credentials" placeholder="e.g. Santos" name="midname" value="<?php echo $midname; ?>">
                    </div>

                    <div class="col-6 col-lg-6 mt-3">
                        <label for="sex">Gender</label> <span style="color: red;">*</span><br/>
                        <input type="text" id="sex" class="login-credentials" placeholder="e.g. Male" name="sex" value="<?php echo $sex; ?>" required>
                    </div>

                    <div class="col-6 col-lg-6 mt-3">
                        <label for="age">Age</label> <span style="color: red;">*</span><br/>
                        <input type="text" id="age" class="login-credentials" placeholder="e.g. 21" name="age" value="<?php echo $age; ?>" required>
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="birthday">Date of Birth</label> <span style="color: red;">*</span><br/>
                        <!-- <input type="date" id="birthday" class="login-credentials" name="birthday"> -->
                        <input type="text" id="birthday" class="login-credentials" name="birthday" placeholder="e.g. 09/27/2000" value="<?php echo $birthday; ?>" required>
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="email">Email</label><br/>
                        <input id="email" type="text" class="login-credentials" name="email" placeholder="e.g. juandelacruz@gmail.com" value="<?php echo $email; ?>">  
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="location">Home Address</label> <span style="color: red;">*</span><br/>
                        <input id="location" type="text" class="login-credentials" name="location" placeholder="e.g. 64 Marikina City" value="<?php echo $location; ?>" required>  
                    </div>

                    <div class="col-12 col-lg-12 mt-5">
                        <input class="login-button" type="submit" name="submit-info" value="Update Profile">
                    </div>
                </div>
            </form>
        </section>


    <?php
    }elseif($action == "edit" && $form == "econ"){?>
        <section class="section">
            <form method="POST">
                <div class="row py-4">
                    <div class="col-12 col-lg-12">
                        <span class="login-text">Emergency Contact</span>
                    </div>

                    <div class="col-12 col-lg-12" style="text-align: center;">
                        <span style="font-size: 12px;">Edit your emergency contact in case of emergency</span>
                    </div>

                    <?php if(count($errors)== 1){ ?>
                        <div class="col-12 col-lg-12 mt-3">
                            <div class="mt-3 alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php
                                foreach($errors as $showerror){
                                    echo $showerror;
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(count($errors) > 1){ ?>
                        <div class="col-12 col-lg-12 mt-3">
                            <div class="mt-3 alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php
                                foreach($errors as $showerror){
                                    ?>
                                    <li><?php echo $showerror; ?></li>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-12 col-lg-12 mt-4">
                        <span style="color: red;">* Required</span>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="primary_contact">Primary Emergency Contact</label><br/>
                        <input type="text" id="primary_contact" class="login-credentials" placeholder="e.g. Juanita Dela Cruz" name="primary_contact" value="<?php echo $primary_contact;?>">
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="primary_relation">Relationship</label><br/>
                        <input id="primary_relation" type="text" class="login-credentials" name="primary_relation" placeholder="e.g. Mother" value="<?php echo $primary_relation;?>"/>  
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="primary_phone">Phone</label><br/>
                        <div class="input-box">
                            <span class="prefix">+63</span>
                            <input type="text" id="primary_phone" class="login-credentials" placeholder="e.g. 91234567890" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" name="primary_phone" value="<?php echo substr($primary_phone,3);?>">
                        </div>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <br/>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="secondary_contact">Secondary Emergency Contact</label><br/>
                        <input type="text" id="secondary_contact" class="login-credentials" placeholder="e.g. Maria Dela Cruz" name="secondary_contact" value="<?php echo $secondary_contact;?>">
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="secondary_relation">Relationship</label><br/>
                        <input type="text" id="secondary_relation" class="login-credentials" placeholder="e.g. Aunt" name="secondary_relation" value="<?php echo $secondary_relation;?>">
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="secondary_phone">Phone</label><br/>
                        <div class="input-box">
                            <span class="prefix">+63</span>
                            <input type="text" id="secondary_phone" class="login-credentials" name="secondary_phone" placeholder="e.g. 91234567890" minlength="10" maxlength="10" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" value="<?php echo substr($secondary_phone, 3);?>">
                        </div>
                    </div>

                    <div class="col-12 col-lg-12 mt-5">
                        <div class="col-12 col-lg-12 mt-3"> 
                            <input class="login-button" type="submit" name="emergency-info" value="Update Emergency Contact">
                        </div>
                    </div>
                </div>
            </form>
        </section>
    <?php 
    }elseif($action == "edit" && $form == "minfo"){?>
        <section class="section">
            <form method="POST">
                <div class="row py-4">
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
                        <textarea name="allergies" class="login-credentials2" id="allergies" placeholder="e.g.&#10;Penicillin&#10;Amoxicillin&#10;Ibuprofen" cols="30" rows="10"><?php echo $allergies; ?></textarea>
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="medications">Prescribed Medications</label><br/>
                        <textarea name="medications" class="login-credentials2" id="medications" placeholder="e.g.&#10;Amoxicillin&#10;Vitamin D&#10;Ibuprofen" cols="30" rows="10"><?php echo $medications; ?></textarea>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="conditions">Medical Conditions</label><br/>
                        <textarea name="conditions" class="login-credentials2" id="conditions" placeholder="e.g.&#10;Ischemic heart disease&#10;Cancer&#10;Pneumonia" cols="30" rows="10"><?php echo $conditions; ?></textarea>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="other-conditions">Other Conditions</label><br/>
                        <textarea name="other-conditions" class="login-credentials2" id="other-conditions" placeholder="Other health or medical information you want us to know about." cols="30" rows="10"><?php echo $other_conditions; ?></textarea>
                    </div>

                    <div class="col-12 col-lg-12 mt-5">
                        <div class="col-12 col-lg-12 mt-3"> 
                            <input class="login-button" type="submit" name="medical-info" value="Update Medical Information">
                        </div>
                    </div>
                </div>
            </form>
        </section>
    <?php 
    }elseif($action == "edit" && $form == "vlog"){?>
        <section class="section">
            <form method="POST">
                <div class="row py-4">
                    <div class="col-12 col-lg-12">
                        <span class="login-text">Visit Log</span>
                    </div>

                    <div class="col-12 col-lg-12" style="text-align: center;">
                        <span style="font-size: 12px;">Visit Log for Contact Tracing</span>
                    </div>

                    <div class="col-12 col-lg-12 mt-4">
                        <span style="color: red;">* Required</span>
                    </div>
                    
                    <div class="col-8 col-lg-8">
                        <input type="hidden" name="id-visit" value="<?php echo $id_visit; ?>">
                        <label for="loc_visit">Location</label> <span style="color: red;">*</span><br/>
                        <input type="text" id="loc_visit" class="login-credentials" placeholder="e.g. Marikina City Hall" name="loc_visit" value="<?php echo $loc_visit; ?>" required>
                    </div>
                    
                    <div class="col-4 col-lg-4 mt-3">
                        <button type="button" onclick="getLocation()" class="getloc">My location</button>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="date_visit">Date of Visit</label> <span style="color: red;">*</span><br/>
                        <input type="text" id="date_visit" class="login-credentials" placeholder="e.g. 10/27/2021" name="date_visit"  value="<?php echo $date_visit; ?>" required>
                    </div>

                    <div class="col-12 col-lg-12 mt-3">
                        <label for="date_visit">Body Temperature</label><br/>
                        <input type="text" id="temp" class="login-credentials" placeholder="e.g. 36" name="temp" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))" value="<?php echo $temp; ?>">
                    </div>

                    <div class="col-12 col-lg-12 mt-3"> 
                        <label for="companion">Companions</label><br/>
                        <textarea name="companion" class="login-credentials2" id="companion" placeholder="e.g.&#10;Antonio Dela Cruz&#10;Maria Dela Cruz&#10;Juanita Dela Cruz" cols="30" rows="10"><?php echo $companions; ?></textarea>
                    </div>

                    <div class="col-12 col-lg-12 mt-5">
                        <input class="login-button" type="submit" name="save-log" value="Add To Log">
                    </div>
                </div>
            </form>
        </section>
    <?php } ?>

    <?php
    if($action == "" && $form == "pinfo"){?>
        <section class="section">   
            <div id="pinfo" class="row py-4">
                <div class="col-12 col-lg-12">
                    <span class="login-text">Basic Personal Information</span>
                </div>

                <div class="col-12 col-lg-12 mt-4">
                    Full Name:<br/>
                    <span style="color: var(--button-color);"><?php echo $lastname.", ".$firstname." ".$midname;?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    Gender:<br/>
                    <span style="color: var(--button-color);"><?php echo $sex; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3">
                    Age:<br/>
                    <span style="color: var(--button-color);"><?php echo $age; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Date of Birth:<br/>
                    <!-- <input type="date" id="birthday" class="login-credentials" name="birthday"> -->
                    <span style="color: var(--button-color);"><?php echo $birthday; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Email:<br/>
                    <span style="color: var(--button-color);"><?php echo $email; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Home Address:<br/>
                    <span style="color: var(--button-color);"><?php echo $location; ?></span>
                </div>
            </div>
        </section>
    <?php 

    }elseif($action == "" && $form == "econ"){?>
        <section class="section">
            <div id="econ" class="row py-4">
                <div class="col-12 col-lg-12">
                    <span class="login-text">Emergency Contact</span>
                </div>

                <div class="col-12 col-lg-12 mt-4"> 
                    Primary Emergency Contact:<br/>
                    <span style="color: var(--button-color);"><?php echo $primary_contact; ?></span>
                </div>
                
                <div class="col-12 col-lg-12 mt-3"> 
                    Relationship:<br/>
                    <span style="color: var(--button-color);"><?php echo $primary_relation; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Phone:<br/>
                    <span style="color: var(--button-color);"><?php echo $primary_phone; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-4">
                    <br/>
                </div>

                <div class="col-12 col-lg-12 mt-4"> 
                    Secondary Emergency Contact:<br/>
                    <span style="color: var(--button-color);"><?php echo $secondary_contact; ?></span>
                </div>
                
                <div class="col-12 col-lg-12 mt-3"> 
                    Relationship:<br/>
                    <span style="color: var(--button-color);"><?php echo $secondary_relation; ?></span>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Phone:<br/>
                    <span style="color: var(--button-color);"><?php echo $secondary_phone; ?></span>
                </div>
            </div>
        </section>
    <?php
    }elseif($action == "" && $form == "minfo"){?>
        <section class="section">
            <div id="econ" class="row py-4">
                <div class="col-12 col-lg-12">
                    <span class="login-text">Medical Information</span>
                </div>

                <div class="col-12 col-lg-12 mt-4"> 
                    Drug Allergies:<br/>
                    <?php 
                        $list_allergies = explode("\n", $allergies);
                        foreach($list_allergies as $allergy){
                            if(!empty($allergy))
                                echo "<span style='color: var(--button-color);'>".$allergy."</span><br/>";
                        }
                    ?>                
                </div>
                
                <div class="col-12 col-lg-12 mt-3"> 
                    Prescribed Medications:<br/>
                    <?php 
                        $list_medications = explode("\n", $medications);
                        foreach($list_medications as $medication){
                            if(!empty($medication))
                                echo "<span style='color: var(--button-color);'>".$medication."</span><br/>";
                        }
                    ?>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Health Conditions:<br/>
                    <?php 
                        $list_conditions = explode("\n", $conditions);
                        foreach($list_conditions as $conditions){
                            if(!empty($conditions))
                                echo "<span style='color: var(--button-color);'>".$conditions."</span><br/>";
                        }
                    ?>
                </div>

                <div class="col-12 col-lg-12 mt-3"> 
                    Other Conditions:<br/>
                    <?php 
                        $list_other_conditions = explode("\n", $other_conditions);
                        foreach($list_other_conditions as $other_condition){
                            if(!empty($other_condition))
                                echo "<span style='color: var(--button-color);'>".$other_condition."</span><br/>";
                        }
                    ?>
                </div>
            </div>
        </section>
    <?php
    }elseif($action == "" && $form == "vlog"){?>
        <section class="section">
            <div id="c_tracing" class="row py-4">
                <div class="col-12 col-lg-12">
                    <span class="login-text">Visit Log</span>
                </div>

                <div class="col-12 col-lg-12 mt-4"> 
                    <table id="example" class="table table-striped" style="width:100%; font-size: 11px;">
                        <thead>
                            <tr>
                                <th class="row-head">Date</th>
                                <th class="row-head">Location</th>
                                <th class="row-head">Temp.</th>
                                <th class="row-head">Companions</th>
                                <th class="row-head">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if ($result5->num_rows > 0) {
                                while ($row5 = $result5->fetch_assoc()) {?>
                                    <tr>
                                        <td id="data"><?php echo $row5['date_visit']; ?></td>
                                        <td id="data"><?php echo $row5['loc_visit']; ?></td>
                                        <td id="data"><?php echo $row5['temperature']; ?>&deg;C</td>
                                        <td id="data"><?php echo str_replace("\n", "<br/>", $row5['companions']); ?></td>
                                        <td id="data"> 
                                            <a href="#" class="d-flex align-items-center dropdown-toggle action-btn" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </a>
                                    
                                            <div class="dropdown-menu" aria-labelledby="dropdownUser3" style="position: fixed; z-index: 100;">  
                                                <a href='profile.php?edit-vlog=<?php echo $row5['id']?>&action=edit&form=vlog' class="announcement_ddown">
                                                    <i class='bx bx-check'></i>
                                                    <span class="">Edit</span>
                                                </a>
                                                <button type="button" class="announcement_ddown btnaction" id="btn-del-vlog<?php echo $row5['id'];?>">
                                                    <i class='bx bx-x'></i>
                                                    <span class="">Delete</span>
                                                </button>

                                                <script>
                                                    $("#btn-del-vlog<?php echo $row5['id'];?>").click(function(){
                                                        $("#modal-del-conf<?php echo $row5['id'];?>").modal("show");
                                                    })
                                                </script>
                                            </div>

                                            <div class="modal fade" id="modal-del-conf<?php echo $row5['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Visit Log</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body" style="overflow-x:hidden;">
                                                           Are you sure you want to delete this log?
                                                        </div>

                                                        <div class="modal-footer">
                                                            <a href="controller?del-vlog=<?php echo $row5['id']; ?>">
                                                                <button type="button" name="del-vlog" class="btn btn-secondary" style="background-color: var(--button-color);" data-bs-dismiss="modal">Delete</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td> 
                                    </tr>
                                <?php } 
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php
    }elseif($action == "" && $form == ""){ ?>
        <section class="section">   
            <div id="pinfo" class="row py-4">
                <div class="col-12 col-lg-12">
                    <span class="login-text">Your Profile</span>
                </div>

                <div id="personal-info" class="col-12 col-lg-12 mt-4">
                    <div class="row">
                        <div class="col-9">
                            <span style="line-height: 36px; font-size: 14px !important;">Basic Personal Information</span>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <span style="line-height: 36px; font-size: 14px !important; color: var(--button-color);">View</span>
                        </div>
                    </div>
                </div>

                <div id="emergency-contact" class="col-12 col-lg-12 mt-4">
                    <div class="row">
                        <div class="col-9">
                            <span style="line-height: 36px; font-size: 14px !important;">Emergency Contacts</span>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <span style="line-height: 36px; font-size: 14px !important; color: var(--button-color);">View</span>
                        </div>
                    </div>
                </div>

                <div id="med-info" class="col-12 col-lg-12 mt-4">
                    <div class="row">
                        <div class="col-9">
                            <span style="line-height: 36px; font-size: 14px !important;">Medical Information</span>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <span style="line-height: 36px; font-size: 14px !important; color: var(--button-color);">View</span>
                        </div>
                    </div>
                </div>

                <div id="visit-log" class="col-12 col-lg-12 mt-4">
                    <div class="row">
                        <div class="col-9">
                            <span style="line-height: 36px; font-size: 14px !important;">Visit Logs</span>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <span style="line-height: 36px; font-size: 14px !important; color: var(--button-color);">View</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="section" id="qr_code">
            <form method="POST">
                <div class="row py-4">
                    <div class="col-12 col-lg-12" style="text-align: center;">
                    <?php
                        $user_id = $_SESSION['user_id'];
                        $dirname2 = "images/public/uploads/users/".$user_id."/";
                        $dirname2 = $dirname2."qr_code/";
                        $scanned_directory2 = array_diff(scandir($dirname2), array('..', '.'));
                        foreach($scanned_directory2 as $key2 => $image2) {?>
                            <img src="<?php echo $dirname2.$image2;?>" height="100%" width="100%" alt="qr_code">
                    <?php }
                    ?>
                    </div>

                    <div class="col-12 col-lg-12" style="text-align: center;">
                        <input type="submit" name="download" class="login-button" value="Download"></div>
                    </div>
                </div>
            </form>
        </section>
    <?php } ?>
    </div>        
</div>
<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->
<script>
$(document).ready(function() {

    $('#example').DataTable( {
        "scrollCollapse": true,
        "paging":         false,
        "searching": false,
        "ordering": false,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
    } );
 
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');

} );
</script>

<script>
    $("#personal-info").click(function(){
        window.location.href = "profile?form=pinfo";
    });
    $("#emergency-contact").click(function(){
        window.location.href = "profile?form=econ";
    });
    $("#med-info").click(function(){
        window.location.href = "profile?form=minfo";
    });
    $("#visit-log").click(function(){
        window.location.href = "profile?form=vlog";
    });

    $("#btnQR").click(function(){
        document.getElementById('qr_code').scrollIntoView({behavior: "smooth"});
    });
</script>

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBL9Y78cLma1-i464zWwKrtAzhirMSnFKk&callback=initMap&v=weekly"
      async>
</script>

<script defer>
const x = document.getElementById("loc_visit");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.value = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) { 
    const latlng = {
      lat: parseFloat(position.coords.latitude),
      lng: parseFloat(position.coords.longitude),
    };
    console.log(latlng);
    const geocoder = new google.maps.Geocoder();
    console.log(geocoder);
    geocoder.geocode({'location': latlng}, function(results, status) { 
        console.log(x);
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                const add = results[1].formatted_address;
                x.value = add;
            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });
}
</script>
</body>
</html>
