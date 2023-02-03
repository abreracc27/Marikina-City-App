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

$hour = 23;
$today = strtotime("today $hour:59:59");
$yesterday = strtotime("yesterday $hour:59:59");
$week_ago = strtotime("yesterday -7 day");
$month_ago = strtotime("yesterday -1 month");

if(isset($_GET['period'])){
    $period = $_GET['period'];
    if($period == '7days'){
        $text = "7 days";
        $sql = "SELECT FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) AS datetime_date, count(*) AS cases FROM user_assistance WHERE FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) < CURDATE() AND FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) >= DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 7 DAY) GROUP BY datetime_date";
    }    
    if($period == '30days'){
        $text = "30 days";
        $sql = "SELECT FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) AS datetime_date, count(*) AS cases FROM user_assistance WHERE FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) < CURDATE() AND FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) >= DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 30 DAY) GROUP BY datetime_date";
    }
    if($period == '1year'){
        $text = "1 year";
        $sql = "SELECT FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) AS datetime_date, count(*) AS cases FROM user_assistance WHERE FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) < CURDATE() AND FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) >= DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 1 YEAR) GROUP BY datetime_date";
    }
}else{
    $text = "All time";
    $sql = "SELECT FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) AS datetime_date, count(*) AS cases FROM user_assistance WHERE FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) < CURDATE() GROUP BY datetime_date";
}

$sql2 = "SELECT svg_path.barangay_id, svg_path.barangay, barangay_cases.active_cases, barangay_cases.recovered_cases, barangay_cases.death_cases FROM svg_path RIGHT JOIN barangay_cases ON svg_path.barangay_id = barangay_cases.barangay_id ORDER BY barangay_cases.active_cases DESC";

$sql3 = "SELECT SUM(active_cases) AS total_active_cases, SUM(recovered_cases) AS total_recovered_cases, SUM(death_cases) AS total_death_cases FROM barangay_cases";

$max_active = "SELECT a.barangay_id, a.barangay, b.barangay_id, b.active_cases
                FROM svg_path a
                INNER JOIN barangay_cases b
                ON a.barangay_id = b.barangay_id
                WHERE b.active_cases = (SELECT MAX(active_cases) FROM barangay_cases)";
                
$min_active = "SELECT a.barangay_id, a.barangay, b.barangay_id, b.active_cases
                FROM svg_path a
                INNER JOIN barangay_cases b
                ON a.barangay_id = b.barangay_id
                WHERE b.active_cases = (SELECT MIN(active_cases) FROM barangay_cases)";


$test = "CREATE TEMPORARY TABLE IF NOT EXISTS table2 (SELECT FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) AS datetime_date, count(*) AS cases FROM user_assistance WHERE FROM_UNIXTIME(datetime, GET_FORMAT (DATE,'JIS')) < CURDATE() GROUP BY datetime_date)";
$conn->query($test);

$test2 = "SELECT datetime_date,cases FROM table2 WHERE cases = (SELECT MAX(cases) from table2)";

$result= $conn->query($sql);
$table = $conn->query($sql);
$result2= $conn->query($sql2);
$result3 = $conn->query($sql3);
$result4 = $conn->query($max_active);
if ($result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $max_barangay = $row4['barangay'];
        $max_case = $row4['active_cases'];
    }
}
$result5 = $conn->query($min_active);
if ($result5->num_rows > 0) {
    while ($row5 = $result5->fetch_assoc()) {
        $min_barangay = $row5['barangay'];
        $min_case = $row5['active_cases'];
    }
}

$result6= $conn->query($test2);
if ($result6->num_rows > 0) {
    while ($row6 = $result6->fetch_assoc()) {
        $peak_date = $row6['datetime_date'];
        $peak_date = strtotime($peak_date);
        $peak_date = date('F j, Y', $peak_date);
        $peak_case = $row6['cases'];
    }
}
$active = 0;
$recovered = 0;
$death = 0;
$confirmed = 0;
if ($result3->num_rows > 0) {
    while ($row3 = $result3->fetch_assoc()) {
        $active = $row3["total_active_cases"]; 
        $recovered = $row3["total_recovered_cases"]; 
        $death = $row3["total_death_cases"]; 
        $confirmed = $active + $recovered + $death;
    }
}


$last_update = "SELECT update_time FROM information_schema.tables WHERE table_name = 'barangay_cases'";
$update_res = $conn->query($last_update);
if ($update_res->num_rows > 0) {
    while ($row_update = $update_res->fetch_assoc()) {
        $mysql_date = $row_update['update_time'];
    }
}
$phpdate = strtotime($mysql_date . "+8 hours");
$mysqldate = date('F j, Y', $phpdate );

?>
<?php include "php/header1.php"; ?>
<title>Statistic Reports — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">    
    <div class="row pb-2">
        <div class="col-6 col-lg-3 order-lg-first my-3">
            <div class="stat-num">
                <span class="stat-num-text active-cases">Active Cases</span><br/>
                <span class="stat-num-data active-cases"><?php echo number_format($active); ?></span>
            </div>
        </div>
                
        <div class="col-6 col-lg-3 my-3">
            <div class="stat-num">
                <span class="stat-num-text recovered-cases">Recovered Cases</span><br/>
                <span class="stat-num-data recovered-cases"><?php echo number_format($recovered); ?></span>
            </div>
        </div>
        <div class="col-6 col-lg-3 my-3">
            <div class="stat-num">
                <span class="stat-num-text death-cases">Death Cases</span><br/>
                <span class="stat-num-data death-cases"><?php echo number_format($death); ?></span>
            </div>
        </div>
        <div class="col-6 col-lg-3 my-3">
            <div class="stat-num">
                <span class="stat-num-text confirmed-cases">Confirmed Cases</span><br/>
                <span class="stat-num-data confirmed-cases"><?php echo number_format($confirmed); ?></span>
            </div>
        </div>
    </div>

    <div class="row py-3">
        <div class="col-12 col-lg-12">
            <div class="chart-container">
                <div id="chart_div" class="chart"></div>
                <div class="chart-interpretation">
                    Figure 1.1 summarizes barangay areas in Marikina City with the number of COVID-19 cases from most cases at the top to most minor cases at the bottom. 
                    As of <?php echo $mysqldate; ?>, the barangay area with the most active cases of 
                    <?php 
                        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $f->format($max_case);
                        echo "(".$max_case.")";
                    ?> 
                    is Barangay <?php echo $max_barangay;?>. Barangay with least cases of
                    <?php 
                        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $f->format($min_case);
                        echo "(".$min_case.")";
                    ?> 
                    is Barangay <?php echo $min_barangay;?>.
                </div>
            </div>
        </div>
    </div>

    <?php if ($result->num_rows > 0) {?>
    <div class="row py-3">
        <div class="col-12 col-lg-12">
            <div class="chart-container">
                <div class="dropdown my-2 chart-dropdown">
                    <label>
                        <button class="list-button dropdown-toggle period-select" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><?php echo ucfirst($text); ?></button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="statistic-reports">All time</a></li>
                            <li><a class="dropdown-item" href="statistic-reports?period=7days">7 days</a></li>
                            <li><a class="dropdown-item" href="statistic-reports?period=30days">30 days</a></li>
                            <li><a class="dropdown-item" href="statistic-reports?period=1year">1 year</a></li>
                        </ul>
                    </label>      
                </div>
                <div style="text-align: center;">Each day shows new cases reported since the previous day</div>
                <div id="curve_chart" class="chart"></div>
                <div class="chart-interpretation">
                    Figure 1.2 shows the number of reported COVID-19 cases through the application for each day since yesterday. 
                    Marikina City reached a peak in COVID-19 reported cases last 
                    <?php echo $peak_date;?>, with 
                    <?php 
                    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    echo $f->format($peak_case);
                    echo "(".$peak_case.")";
                    ?> reported cases using the application.<br><br>
                    
                    <?php if ($peak_case > 25){
                        echo "It is recommended to strictly implement movement restrictions and physical distancing measures on establishments and mass gatherings.";
                    }else{
                        echo "It is recommended to remind residents regarding COVID-19 protocols and guidelines. Advise residents to wear facemasks, practice social distancing at all times, and stay at home if possible.";
                    }
                    ?>
                </div>
                <table id="example" class="table table-striped text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th class="row-head">Date</th>
                            <th class="row-head">Reported Cases</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if ($table->num_rows > 0) {
                                while ($table_row = $table->fetch_assoc()) {?>
                                    <tr>
                                        <td id="data"><?php echo $table_row['datetime_date']; ?></td>
                                        <td id="data"><?php echo $table_row['cases']; ?></td>
                                    </tr>
                                <?php }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    
</div>
<!--Container Main end-->


<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

$(window).resize(function(){
    location.reload();
    drawChart();
});

function drawChart() {
var data = google.visualization.arrayToDataTable([
    ['Date', 'Reported Cases'],
    <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $datetime = strtotime($row['datetime_date']);
                echo "['".date('d M', $datetime)."', ".$row['cases']."], \n";
            }
        }
    ?>
]);

var options = {
    title: 'Figure 1.2 - Reported COVID-19 Cases Through Mobile App',
    // curveType: 'function',
    legend: { position: 'bottom' },
    vAxis:{
        format: "0"
    }
};

var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

chart.draw(data, options);
}
</script>

<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBarColors);

function drawBarColors() {
    var data = google.visualization.arrayToDataTable([
    ['Barangay', 'Active Cases', { role: 'style' }],
    <?php 
    if ($result2->num_rows > 0) {
        $colors = array(
            "#d93e52", "#40d9ba", "#1b1e52", "#357549", 
            "#d435a2", "#956763", "#c27602", "#5003d8", 
            "#fca95a", "#857322", "#c99374", "#28ec5b", 
            "#a699c1", "#c05043", "#bdb423", "#f0e654");
        $i = 0;
        while ($row2 = $result2->fetch_assoc()) {
            echo "['".$row2['barangay']."', ".$row2['active_cases'].", '".$colors[$i]."'],\n";
            $i = $i + 1;
        }
    }
    ?>
    ]);

    var options = {
        title: 'Figure 1.1 - Marikina City COVID-19 Active Cases per Barangay',
        chartArea: {
            width: '55%',
        },
        hAxis: {
        //   title: 'Total Population',
            minValue: 1,
            format: "0"
        },
        vAxis: {
            title: 'Barangay',
            textStyle : {
                fontSize: 12 // or the number you want
            }
        },
        legend: {
            position: 'none'
        }
    };
    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}

$(window).resize(function(){
    location.reload();
    drawBarColors();
});


$(document).ready(function() {
    $('#example').DataTable( {
        "scrollCollapse": true,
        "paging":         true,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    } );
 
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');

});
</script>


<?php include "php/navigation2.php"; ?>
