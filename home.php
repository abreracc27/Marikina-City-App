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

$covid_stat= "";
$sql2 = "SELECT * FROM user_profile WHERE user_id = '$user_id'";
$res = mysqli_query($conn, $sql2);
if(mysqli_num_rows($res) > 0){
    $row = mysqli_fetch_assoc($res);
    $covid_stat = $row['covid_status'];
}

$sql3 = "SELECT svg_path.barangay_id, svg_path.barangay, barangay_cases.active_cases, svg_path.svg_id, svg_path.d, svg_path.label FROM 
svg_path INNER JOIN barangay_cases ON svg_path.barangay_id = barangay_cases.barangay_id";
$result3 = $conn->query($sql3);

$sql4 = "SELECT barangay_cases.barangay_id, svg_path.barangay, barangay_cases.active_cases, barangay_cases.recovered_cases, barangay_cases.death_cases FROM
barangay_cases INNER JOIN svg_path ON barangay_cases.barangay_id=svg_path.barangay_id ORDER BY barangay_cases.active_cases DESC";
$result4 = $conn->query($sql4);

$sql5 = "SELECT SUM(active_cases) AS total_active_cases, SUM(recovered_cases) AS total_recovered_cases, SUM(death_cases) AS total_death_cases FROM barangay_cases";
$result5 = $conn->query($sql5);


$folder = "images/public/uploads/users/".$user_id."/";
$folder = $folder."test_result/";
$files = array_slice(scandir($folder), 2);

$uploaded_count = count($files);
if($uploaded_count <= 0){
    $file_submit = "save-result";
    $input_value = "Upload";
}elseif($uploaded_count >= 1){
    $file_submit = "change-result";
    $input_value = "Update";
}

$up_time = "SELECT update_time FROM information_schema.tables WHERE table_name = 'barangay_cases'";
$up_res = $conn->query($up_time);
if ($up_res->num_rows > 0) {
    while ($row_up = $up_res->fetch_assoc()) {
        $mysql_date = $row_up['update_time'];
    }
}
$phpdate = strtotime($mysql_date . "+8 hours");
$mysqldate = date('D\, j M Y h:i a', $phpdate );
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
        <div style="width: 45px; text-align: center;">
            <button id="btnSidenav" style="width: 45px; border: none; background: transparent;">
                <i class="material-icons" style="line-height: 44px; font-size: 24px !important; color: var(--button-color)">manage_accounts</i>
            </button>
        </div>  
    </header>    

    <?php include "php/sidenav.php"; ?>

    <div class="main-content message-area">
        <div class="home-center-area">
            <section class="section">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row py-4">
                        <div class="modal fade" id="positive" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body" style="overflow-x:hidden;">
                                        Are you in need of medical assistance? By saying yes, you are allowing us to utilize your information to provide you the necessary assistance.<br><br>
                                        For questions related to COVID-19, you may view the answers of the Department of Health to the Frequently Asked Questions <a href="https://doh.gov.ph/COVID-19/FAQs">HERE</a>.<br><br>
                                        If you have more questions, you may send us a private message <a href="messages?admin_unique_id=465899818">HERE</a>.
                                    </div>

                                    <div class="modal-footer">
                                        <div class="row">
                                            <div class="col-6">
                                                <button class="modal-button" name="update_stat" id="positive-alert" onclick="positiveNotify()">Yes, I need medical assistance</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="cancel-modal-button" name="update_stat" id="positive-no-alert" onclick="positiveOnly()">No, I don't need medical assistance</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="negative" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body" style="overflow-x:hidden;">
                                        For questions related to COVID-19, you may view the answers of the Department of Health to the Frequently Asked Questions <a href="https://doh.gov.ph/COVID-19/FAQs">HERE</a>.<br><br>
                                        If you have more questions, you may send us a private message <a href="messages?admin_unique_id=465899818">HERE</a>.
                                    </div>

                                    <div class="modal-footer">
                                        <button class="modal-button" name="update_stat" id="negative" onclick="negativeOnly()">Got it</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-6"> 
                            <button id="pos-btn" style="width: 100%;" type="button" class="<?php if($covid_stat == "positive"){echo "report-button";}else{ echo "report-button2";}?>"> I'm COVID-19 <br><b>POSITIVE</b></button>
                        </div>

                        <div class="col-6 col-lg-6"> 
                            <button id="neg-btn" style="width: 100%;" type="button"class="<?php if($covid_stat == "negative"){echo "report-button";}else{ echo "report-button2";}?>">I'm COVID-19 <br><b>NEGATIVE</b></button>
                        </div>

                        <script>
                            $("#pos-btn").click(function(){
                                $("#positive").modal("show");
                            })

                            $("#neg-btn").click(function(){
                                $("#negative").modal("show");
                            })
                        </script>

                        <?php if(count($errors)== 1){ ?>
                            <div class="col-12 col-lg-12">
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
                            <div class="col-12 col-lg-12">
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

                        <div class="col-12 col-lg-12 mt-4" style="text-align: center;">
                            <span class="setup-profile-text">Share your COVID-19 test result</span>
                        </div>

                        <div class="col-12 col-lg-12 mt-3 upload-area" style="text-align: center;">
                            <input type="hidden" id="status" name="status">
                            <input type="hidden" id="notify" name="notify">
                            <div class="imgGallery"></div>

                            <?php
                                $dirname = "images/public/uploads/users/".$user_id."/";
                                $dirname = $dirname."test_result/";
                                $scanned_directory = array_diff(scandir($dirname), array('..', '.'));
                                $filecount = count(glob($dirname."*.*"));
                                ?>
                                <div class="imgGallery2">
                                    <?php
                                    foreach($scanned_directory as $key => $image) {
                                        $fileExtension = substr($image, strrpos($image, '.')+1);

                                        $fileName = substr($image, 0, strpos($image, "."));

                                        if(strlen($fileName) > 19){
                                            $first = substr($fileName, 0, 8);
                                            $last = substr($fileName, -8, strlen($fileName));
                                            $image = $first."...".$last.".".$fileExtension;
                                        }

                                        if($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png' || $fileExtension == 'doc' || $fileExtension == 'docx' || $fileExtension == 'pdf' || $fileExtension == 'mp3'){
                                            echo "<div class='appendedImg'><span style='line-height: 55px; vertical-align: middle;'><span>".$image."</span> <button class='close AClass btnRemove2' value=".$key.">Remove</button></span></div>";
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                                $images = array_slice(scandir($dirname), 2);      
                                
                            ?>

                            <input type="file" name="uploadfile[]" style="width: 100%; display:none;" id="chooseFile" accept=".jpg,.jpeg,.png,.doc,.docx,.pdf,.mp3">
                            <Button type="button" class="btnBrowse" onclick="document.getElementById('chooseFile').click();"><i class='bx bx-upload'></i> Upload File<br/>[.jpg, .jpeg, .png, .doc, .docx, .pdf, .mp3]</button>
                            <input type="hidden" id="removed_files" name="removed_files" value="">
                            <input type="hidden" id="removed_files2" name="removed_files2" value="">

                            <div class="mt-4" style="text-align: right">
                                <input type="submit" style="color: var(--button-color); border: none; font-size: 16px !important; background-color:transparent;" id="file-submit" name="<?php echo $file_submit;?>" value="<?php echo $input_value;?>">
                            </div>
                        </div>

                        <div class="col-12 col-lg-12 mt-4" style="text-align: justify; text-justify: inter-word;">
                            <span style="font-size: 14px;">If you have tested positive for COVID-19, sharing your test result and requesting medical assistance will help prevent the spreading of the virus.</span>
                        </div>
                    </div>
                </form>
            </section>

            <section class="section">  
                <div class="row py-4">
                    <div style="height: 100%; overflow-y: scroll;">
                        <div class="col-12 col-lg-12" style="text-align: center; position: relative"> 
                            <div class="mapdiv">
                                <svg
                                    version="1.1"
                                    id="svg9"
                                    viewBox="0 0 2546.3169 2414.3467"
                                    sodipodi:docname="marikina.svg"
                                    inkscape:version="1.1 (c68e22c387, 2021-05-23)"
                                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:svg="http://www.w3.org/2000/svg">
                                    <defs id="defs13" />
                                
                                    <g
                                    inkscape:groupmode="layer"
                                    inkscape:label="Image"
                                    id="g15"
                                    transform="translate(-406.13313,-211.98809)">

                                
                                    <?php
                                    if ($result3->num_rows > 0) {
                                        while ($row3 = $result3->fetch_assoc()) {?>

                                    <a xlink:title="<?php echo $row3['barangay']?>">
                                        <path
                                        class="<?php if($row3['active_cases'] > 75){echo "red";}else if($row3['active_cases'] >= 25){echo "yellow";}else{echo "green";}?>"
                                        d="<?php echo $row3['d']?>"
                                        id="<?php echo $row3['svg_id']?>"
                                        inkscape:label="<?php echo $row3['label']?>">
                                        
                                    </a>
                                    <?php }}?>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-weight:normal;font-size:38.6667px;line-height:1.25;font-family:sans-serif;fill-opacity:1;stroke:none"
                                    x="573.56787"
                                    y="2331.9446"
                                    id="text1041"><tspan
                                        sodipodi:role="line"
                                        id="tspan1039"
                                        x="573.56787"
                                        y="2331.9446"
                                        style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle">INDUSTRIAL</tspan><tspan
                                        sodipodi:role="line"
                                        x="573.56787"
                                        y="2380.2778"
                                        id="tspan2693"
                                        style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle">VALLEY</tspan><tspan
                                        sodipodi:role="line"
                                        x="573.56787"
                                        y="2428.6113"
                                        id="tspan2695"
                                        style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle">COMPLEX</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="887.84125"
                                    y="2418.2478"
                                    id="text6389"><tspan
                                        sodipodi:role="line"
                                        id="tspan6387"
                                        x="887.84125"
                                        y="2418.2478"
                                        style="font-size:40px">KALUMPANG</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1326.0542"
                                    y="2297.9841"
                                    id="text8107"><tspan
                                        sodipodi:role="line"
                                        id="tspan8105"
                                        x="1326.0542"
                                        y="2297.9841"
                                        style="font-size:40px">SAN ROQUE</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1307.7977"
                                    y="2039.72"
                                    id="text8107-0"><tspan
                                        sodipodi:role="line"
                                        id="tspan8105-7"
                                        x="1307.7977"
                                        y="2039.72"
                                        style="font-size:40px">SANTA ELENA</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1356.7129"
                                    y="1793.7871"
                                    id="text8107-8"><tspan
                                        sodipodi:role="line"
                                        x="1356.7129"
                                        y="1793.7871"
                                        id="tspan13241"
                                        style="font-size:40px">SANTO NIÑO</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="824.34875"
                                    y="2076.4482"
                                    id="text8107-8-0"><tspan
                                        sodipodi:role="line"
                                        x="824.34875"
                                        y="2076.4482"
                                        id="tspan13241-4">TAÑONG</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="651.7334"
                                    y="1864.4562"
                                    id="text8107-8-1"><tspan
                                        sodipodi:role="line"
                                        x="651.7334"
                                        y="1864.4562"
                                        id="tspan13241-8"
                                        style="font-size:40px">BARANGKA</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1094.877"
                                    y="1380.571"
                                    id="text8107-8-1-6"><tspan
                                        sodipodi:role="line"
                                        x="1094.877"
                                        y="1380.571"
                                        id="tspan13241-8-9"
                                        style="font-size:40px">MALANDAY</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1710.7394"
                                    y="1300.7512"
                                    id="text8107-8-1-8"><tspan
                                        sodipodi:role="line"
                                        x="1710.7394"
                                        y="1300.7512"
                                        id="tspan13241-8-7"
                                        style="font-size:40px;text-align:center;text-anchor:middle">CONCEPCION</tspan><tspan
                                        sodipodi:role="line"
                                        x="1710.7394"
                                        y="1350.7512"
                                        id="tspan20427"
                                        style="font-size:40px;text-align:center;text-anchor:middle">UNO</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1208.5972"
                                    y="944.08478"
                                    id="text8107-8-1-68"><tspan
                                        sodipodi:role="line"
                                        x="1208.5972"
                                        y="944.08478"
                                        id="tspan13241-8-4"
                                        style="font-size:40px">TUMANA</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1924.9089"
                                    y="848.13019"
                                    id="text8107-8-1-0"><tspan
                                        sodipodi:role="line"
                                        x="1924.9089"
                                        y="848.13019"
                                        id="tspan13241-8-95"
                                        style="font-size:40px">PARANG</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="1657.1288"
                                    y="515.6366"
                                    id="text8107-8-1-7"><tspan
                                        sodipodi:role="line"
                                        x="1657.1288"
                                        y="515.6366"
                                        id="tspan13241-8-6"
                                        style="font-size:40px">NANGKA</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="2560.8867"
                                    y="872.6767"
                                    id="text8107-8-1-85"><tspan
                                        sodipodi:role="line"
                                        x="2560.8867"
                                        y="872.6767"
                                        id="tspan13241-8-46"
                                        style="font-size:40px">FORTUNE</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="2347.2227"
                                    y="1195.3182"
                                    id="text8107-8-1-69"><tspan
                                        sodipodi:role="line"
                                        x="2347.2227"
                                        y="1195.3182"
                                        id="tspan13241-8-3"
                                        style="font-size:40px;text-align:center;text-anchor:middle">MARIKINA</tspan><tspan
                                        sodipodi:role="line"
                                        x="2347.2227"
                                        y="1245.3182"
                                        id="tspan26546"
                                        style="font-size:40px;text-align:center;text-anchor:middle">HEIGHTS</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:40px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold'"
                                    x="2317.1213"
                                    y="1677.2389"
                                    id="text8107-8-1-4"><tspan
                                        sodipodi:role="line"
                                        x="2317.1213"
                                        y="1677.2389"
                                        id="tspan27201"
                                        style="font-size:40px;text-align:center;text-anchor:middle">CONCEPCION DOS</tspan></text>
                                    <text
                                    xml:space="preserve"
                                    style="font-style:normal;fill:#ffffff;font-weight:normal;font-size:38.6667px;line-height:1.25;font-family:sans-serif;fill-opacity:1;stroke:none"
                                    x="1116.7949"
                                    y="1979.6456"
                                    id="text1041-0"><tspan
                                        sodipodi:role="line"
                                        x="1116.7949"
                                        y="1979.6456"
                                        id="tspan2695-2"
                                        style="font-style:normal;text-align:font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle">JESUS</tspan><tspan
                                        sodipodi:role="line"
                                        x="1116.7949"
                                        y="2027.979"
                                        style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle"
                                        id="tspan58361">DE LA</tspan><tspan
                                        sodipodi:role="line"
                                        x="1116.7949"
                                        y="2076.3123"
                                        style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:38.6667px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;text-anchor:middle"
                                        id="tspan58363">PEÑA</tspan></text>
                                    </g>
                                </svg>
                            </div>
                            <div class="legend">
                                <div class="box-test">
                                    <div class='box red'></div>
                                    <span class="box-text">High Risk</span>
                                </div>
                                <div class="box-test">
                                    <div class='box yellow'></div>
                                    <span class="box-text">Moderate Risk</span>
                                </div>
                                <div class="box-test">
                                    <div class='box green'></div>
                                    <span class="box-text">Low Risk</span>
                                </div>
                            </div> 
                        </div>

                        <div class="col-12 col-lg-12 mt-2 mb-4" style="text-align: center;">
                            <em>Marikina City Map</em>
                        </div> 

                        <div class="col-12 col-lg-12 mt-3">
                            <h6><?php echo "As of ".$mysqldate; ?></h6>
                        </div>
                        <?php
                        if ($result5->num_rows > 0) {
                            while ($row5 = $result5->fetch_assoc()) {
                                ?>
                                <div class="col-12 col-lg-12 my-2" style="height: 60px; background: #FFFFFF; border-radius: 5px;">
                                    <div style="float: left; padding: 5px 20px;">
                                        <span style="line-height: 25px; font-weight: bold; color: red;">Active Cases</span><br>
                                        <span style="line-height: 25px; font-weight: bold; font-size: 20px !important; color: red;"><?php echo number_format($row5["total_active_cases"]);?></span> 
                                    </div>
                                    <div style="text-align: right; float: right; padding: 5px 20px;">
                                        <i class='bx bx-line-chart' style="line-height: 50px; color: red; font-size: 30px !important;"></i>    
                                    </div>
                                </div>

                                <div class="col-12 col-lg-12 my-2" style="height: 60px; background: #FFFFFF; border-radius: 5px;">
                                    <div style="float: left; padding: 5px 20px;">
                                        <span style="line-height: 25px; font-weight: bold; color: green;">Recovered Cases</span><br>
                                        <span style="line-height: 25px; font-weight: bold; font-size: 20px !important; color: green;"><?php echo number_format($row5["total_recovered_cases"]);?></span> 
                                    </div>
                                    <div style="text-align: right; float: right; padding: 5px 20px;">
                                        <i class='bx bx-line-chart' style="line-height: 50px; color: green; font-size: 30px !important;"></i>    
                                    </div>
                                </div>

                                <div class="col-12 col-lg-12 my-2" style="height: 60px; background: #FFFFFF; border-radius: 5px;">
                                    <div style="float: left; padding: 5px 20px;">
                                        <span style="line-height: 25px; font-weight: bold; color: black;">Deaths</span><br>
                                        <span style="line-height: 25px; font-weight: bold; font-size: 20px !important; color: black;"><?php echo number_format($row5["total_death_cases"]);?></span> 
                                    </div>
                                    <div style="text-align: right; float: right; padding: 5px 20px;">
                                        <i class='bx bx-line-chart' style="line-height: 50px; color: black; font-size: 30px !important;"></i>    
                                    </div>
                                </div>
                            <?php } 
                        }?>
                    </div>
                </div>
            </section>

            <section class="section">   
                <div class="py-4" style="height: 100%; overflow-y: scroll;">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="row-head" >Barangay</th>
                                <th class="row-head" >Active</th>
                                <th class="row-head" >Recovered</th>
                                <th class="row-head" >Deaths</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if ($result4->num_rows > 0) {
                                while ($row4 = $result4->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row4['barangay']; ?></td>
                                            <td style="text-align: center;"><?php echo number_format($row4['active_cases']); ?></td>
                                            <td style="text-align: center;"><?php echo number_format($row4['recovered_cases']); ?></td>
                                            <td style="text-align: center;"><?php echo number_format($row4['death_cases']); ?></td>
                                        </tr>
                                <?php } 
                            }?>
                        </tbody>
                    </table>  
                </div>
            </section>
        </div>        
    </div>

<!-- partial:index.partial.html -->
<?php include "php/navigation.php"; ?>
<!-- partial -->

<script>
    function positiveNotify() {
        document.getElementById("status").value = "positive";
        document.getElementById("notify").value = "notify";
    };

    function positiveOnly() {
        document.getElementById("status").value = "positive";
        document.getElementById("notify").value = "no";
    };

    function negativeOnly() {
        document.getElementById("status").value = "negative";
        document.getElementById("notify").value = "no";
    };
</script>

<script>
    if($('.appendedImg').is(":visible")) {
        $(".btnBrowse").hide();
    }
</script>

<script>
window.newFileList = [];

$(document).on('click', '.btnRemove', function () {
    $(".btnBrowse").show();
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
        var fname = selection.files[i].name;
        var ext = selection.files[i].name.split('.').pop();
        var name_only = fname.substring(0, fname.lastIndexOf('.')) || fname;
        if(name_only.length > 19 ){
            var first = name_only.substring(0, 8);
            var last = name_only.substring((name_only.length - 8), name_only.length);
            var file = first + "..." + last + "." + ext;
        }else{
            var file = fname;
        }
        // if(ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "doc" || ext == "docx" || ext == "pdf" || ext == "mp3"){
            $('.imgGallery').append("<div class='appendedImg'><span style='line-height: 55px; vertical-align: middle;'><span>" + file + "</span> <button class='close AClass btnRemove' value='" + i + "'>Remove</i></button></span></div>");
        // }
    }
    $(".btnBrowse").hide();
});
</script>

<script>
$(document).on('click', '.btnRemove2', function () {
    $(".btnBrowse").show();
    var remove_element = $(this);
    var id = remove_element.val();
    remove_element.closest('.appendedImg').remove();
    var files = <?php echo json_encode($scanned_directory); ?>;
    window.newFileList.push(files[id]);
    document.getElementById('removed_files2').value = JSON.stringify(window.newFileList);
});
</script>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "scrollCollapse": false,
        "paging":         false,
        "searching": false,
        "ordering": true,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
    } );
 
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');

} );
</script>

<script src="javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>
</body>
</html>
