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

$barangay_id = "";

if(isset($_GET['barangay-id'])){
    $barangay_id = $_GET['barangay-id'];

    $sql4 = "SELECT * FROM barangay_cases WHERE id ='$barangay_id'";
    $result4 = $conn->query($sql4);
    while ($row4 = $result4->fetch_assoc()) {
        $active_cases = $row4['active_cases'];
        $recovered_cases = $row4['recovered_cases'];
        $death_cases = $row4['death_cases'];
    }
}

$sql = "SELECT svg_path.barangay_id, svg_path.barangay, barangay_cases.active_cases, svg_path.svg_id, svg_path.d, svg_path.label FROM 
svg_path INNER JOIN barangay_cases ON svg_path.barangay_id = barangay_cases.barangay_id";
$result = $conn->query($sql);

$sql2 = "SELECT update_time FROM information_schema.tables WHERE table_name = 'barangay_cases'";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $mysql_date = $row2['update_time'];
    }
}

$sql3 = "SELECT barangay_cases.barangay_id, svg_path.barangay, barangay_cases.active_cases, barangay_cases.recovered_cases, barangay_cases.death_cases FROM
barangay_cases INNER JOIN svg_path ON barangay_cases.barangay_id=svg_path.barangay_id";
$result3 = $conn->query($sql3);

$sql4 = "SELECT SUM(active_cases) AS total_active_cases, SUM(recovered_cases) AS total_recovered_cases, SUM(death_cases) AS total_death_cases FROM barangay_cases";
$result4 = $conn->query($sql4);

$phpdate = strtotime($mysql_date . "+8 hours");
$mysqldate = date('D\, j M Y h:i a', $phpdate );

?>
<?php include "php/header1.php"; ?>
<title>Barangay Cases — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<?php include "php/alert-message.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="row profile-form p-3">
        <div class="col-12 col-lg-4 order-lg-first line2">
            <form action="" method="POST" enctype="multipart/form-data">
                <h5>Barangay 
                    <?php 
                    switch ($barangay_id){
                        case "1": echo "Barangka";
                            break;
                        case "2": echo "Concepcion Dos";
                            break;
                        case "3": echo "Concepcion Uno";
                            break;
                        case "4": echo "Fortune";
                            break;
                        case "5": echo "Industrial Valley Complex";
                            break;
                        case "6": echo "Jesus De La Peña";
                            break;
                        case "7": echo "Kalumpang";
                            break;
                        case "8": echo "Malanday";
                            break;
                        case "9": echo "Marikina Heights";
                            break;
                        case "10": echo "Nangka";
                            break;
                        case "11": echo "Parang";
                            break;
                        case "12": echo "San Roque";
                            break;
                        case "13": echo "Santa Elena";
                            break;
                        case "14": echo "Santo Niño";
                            break;
                        case "15": echo "Tañong";
                            break;
                        case "16": echo "Tumana";
                            break;
                    } ?>
                </h5>

                <div class="row mb-3">
                    <div class="col">
                        <label for="confirmed_cases">Active Cases</label><br/>
                        <input type="text" class="login-credentials" id="confirmed_cases" name="active_cases" placeholder="Enter number of active cases" value="<?php if(!empty($active_cases)){echo $active_cases;} else{echo "0";}?>" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))"required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="recovered_cases">Patient/s Recovered</label><br/>
                        <input type="text" class="login-credentials" id="recovered_cases" name="recovered_cases" placeholder="Enter number of recovered cases" value="<?php if(!empty($recovered_cases)){echo $recovered_cases;} else{echo "0";}?>" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))"required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="deaths">Death/s</label><br/>
                        <input type="text" class="login-credentials" id="deaths" name="death_cases" placeholder="Enter number of death cases" value="<?php if(!empty($death_cases)){echo $death_cases;}else{echo "0";} ?>" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))"required>
                
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <?php if(!empty($barangay_id)){
                            echo "<a href='covid19-cases'><button type='button' class='cancel-button'>Cancel</button></a>";
                        }
                        ?>
                    </div>
                    <div class="col-6">
                        <input class="login-button"  value="Update Cases" name="update-cases" type="submit">
                    </div>
                </div>
            </form>     
        </div>

        <div class="col-12 col-lg-8 order-first">
            <div class="row">
                <div class="col-12 col-lg-9 order-first mt-3">
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
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {?>

                                <a xlink:title="<?php echo $row['barangay']?>"xlink:href="covid19-cases?barangay-id=<?php echo $row['barangay_id']?>">
                                    <path
                                    class="<?php if($row['active_cases'] > 75){echo "red";}else if($row['active_cases'] >= 25){echo "yellow";}else{echo "green";}?>"
                                    d="<?php echo $row['d']?>"
                                    id="<?php echo $row['svg_id']?>"
                                    inkscape:label="<?php echo $row['label']?>">
                                    
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
                </div>
                <div class="col-12 col-lg-3 mt-3">
                    <div class="row">
                        <div class="col-4 col-lg-12 order-first">
                            <div class="box-test">
                                <div class='box red'></div>
                                <span class="box-text">High Risk</span>
                            </div>
                        </div>
                        <div class="col-4 col-lg-12">
                            <div class="box-test">
                                <div class='box yellow'></div>
                                <span class="box-text">Moderate Risk</span>
                            </div>
                        </div>
                        <div class="col-4 col-lg-12">
                            <div class="box-test">
                                <div class='box green'></div>
                                <span class="box-text">Low Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="text-align:center;"><em>Marikina City Map</em></div>
            </div>
        </div>
    </div>
    <hr>

    <h4><?php echo "As of ".$mysqldate; ?></h4>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="row-head" >ID</th>
                <th class="row-head">Barangay</th>
                <th class="row-head">Active Cases</th>
                <th class="row-head">Patient/s Recovered</th>
                <th class="row-head">Death/s</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row3['barangay_id']; ?></td>
                            <td><?php echo $row3['barangay']; ?></td>
                            <td><?php echo number_format($row3['active_cases']); ?></td>
                            <td><?php echo number_format($row3['recovered_cases']); ?></td>
                            <td><?php echo number_format($row3['death_cases']); ?></td>
                        </tr>
                <?php } 
            }?>
        </tbody>
        <tfoot>
        <?php
            if ($result4->num_rows > 0) {
                while ($row4 = $result4->fetch_assoc()) {
                    ?>
                        <tr>
                            <th colspan="2" style="text-align:right">Total:</th>
                            <th><?php echo number_format($row4['total_active_cases']); ?></th>
                            <th><?php echo number_format($row4['total_recovered_cases']); ?></th>
                            <th><?php echo number_format($row4['total_death_cases']); ?></th>
                        </tr>
                <?php } 
            }?>
        </tfoot>
    </table>
</div>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "scrollCollapse": true,
        "paging":         false,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ]
    } );
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');
} );
</script>

<script src="../javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<!--Container Main end-->
<?php include "php/navigation2.php"; ?>
