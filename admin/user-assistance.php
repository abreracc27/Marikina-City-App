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

if(isset($_GET['status'])){
    $show_list = $_GET['status'];

    if($show_list == 'unsettled'){
        $sql = "SELECT a.user_id, a.user_unique_id, a.phone, b.lastname, b.firstname, b.midname, 
                b.location, b.covid_status, 
                d.id, d.datetime, d.report_status
                FROM users a
                INNER JOIN user_profile b ON a.user_id= b.user_id
                INNER JOIN user_emergency_contact c ON b.user_id = c.user_id
                INNER JOIN user_assistance d ON c.user_id = d.user_id
                WHERE d.report_status = 'unsettled' ORDER BY d.datetime";
    }
    elseif($show_list == 'received'){
        $sql = "SELECT a.user_id, a.user_unique_id, a.phone, b.lastname, b.firstname, b.midname, 
                b.location, b.covid_status, 
                d.id, d.datetime, d.report_status
                FROM users a
                INNER JOIN user_profile b ON a.user_id= b.user_id
                INNER JOIN user_emergency_contact c ON b.user_id = c.user_id
                INNER JOIN user_assistance d ON c.user_id = d.user_id
                WHERE d.report_status = 'received' ORDER BY d.datetime";
    }
    else{
        $sql = "SELECT a.user_id, a.user_unique_id, a.phone, b.lastname, b.firstname, b.midname, 
                b.location, b.covid_status, 
                d.id, d.datetime, d.report_status
                FROM users a
                INNER JOIN user_profile b ON a.user_id= b.user_id
                INNER JOIN user_emergency_contact c ON b.user_id = c.user_id
                INNER JOIN user_assistance d ON c.user_id = d.user_id
                ORDER BY d.datetime";
    }
}

$result = $conn->query($sql);

$inTwoMonths = 60 * 60 * 24 * 60 + time();
setcookie('lastVisit_user_assistance', date("j F Y h:i a"), $inTwoMonths);

?>
<?php include "php/header1.php"; ?>
<title>User Assistance — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<?php include "php/alert-message.php"; ?>
<!--Container Main start-->



<div class="main-content container-fluid">
    <h3 style="text-align: center; margin-bottom: 0 !important;"><?php echo ucfirst($show_list); ?> Reports</h3><br/>
    <form method="POST" autocomplete="off">
        <div class="dropdown mb-2 table-nav">
            <label>
                Show
                <button class="list-button dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><?php echo ucfirst($show_list); ?></button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="user-assistance?status=unsettled">Unsettled</a></li>
                    <li><a class="dropdown-item" href="user-assistance?status=received">Received</a></li>
                    <li><a class="dropdown-item" href="user-assistance?status=all">All</a></li>
                </ul>
                entries
            </label>
        </div>
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="row-head">ID</th>
                    <th class="row-head">First Name</th>
                    <th class="row-head">Middle Name</th>
                    <th class="row-head">Last Name</th>
                    <th class="row-head">Phone</th>
                    <th class="row-head">Location</th>
                    <th class="row-head">COVID Status</th>
                    <th class="row-head">Date Time</th>
                    <th class="row-head">Test Result</th>
                    <th class="row-head">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $dirname = "../images/public/uploads/users/".$user_id."/";
                        $dirname = $dirname."test_result/";
                        $scanned_directory = array_diff(scandir($dirname), array('..', '.'));
                        $filecount = count(glob($dirname."*.*"));
                        foreach($scanned_directory as $key => $image) {
                            $fileExtension = substr($image, strrpos($image, '.')+1);

                            $fileName = substr($image, 0, strpos($image, "."));

                            if(strlen($fileName) > 19){
                                $first = substr($fileName, 0, 8);
                                $last = substr($fileName, -8, strlen($fileName));
                                $image = $first."...".$last.".".$fileExtension;
                            }
                        }
                        $images = array_slice(scandir($dirname), 2);      
                        ?>
                            <tr>
                                <td id="data"><?php echo $row['id']; ?></td>
                                <td id="data"><?php echo $row['firstname']; ?></td>
                                <td id="data"><?php echo $row['midname']; ?></td>
                                <td id="data"><?php echo $row['lastname']; ?></td>
                                <td id="data"><?php echo $row['phone']; ?></td>
                                <td id="data"><?php echo $row['location']; ?></td>
                                <td id="data"><?php echo ucfirst($row['covid_status']); ?></td>
                                <td id="data"><?php echo date('F j, Y h:i a', $row['datetime']); ?></td>
                                <td id="data">
                                    <?php 
                                        if(isset($image)){
                                            echo $image;
                                            echo "<br/><a style='text-decoration: none; color: #000000;' target='_blank' href='".$dirname.$fileName.".".$fileExtension."'><i class='bi bi-eye eye-icon'></i>View</a></td>";
                                        }else{
                                            echo "<em>Test result removed</em>";
                                        }; 
                                    ?>
                                <td id="data">

                                    
                                    <a href="#" class="d-flex align-items-center dropdown-toggle action-btn" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                     </a>
                                    

                                    <div class="dropdown-menu" aria-labelledby="dropdownUser3" style="position: fixed; z-index: 100;">  
                                        <?php
                                        if($row['report_status'] == 'unsettled'){?>  
                                            <a href='controller.php?report-id=<?php echo $row['user_id']?>&status=received' class="announcement_ddown">
                                                <i class='bx bx-check'></i>
                                                <span class="">Mark as Received</span>
                                            </a>
                                            <button type="button" class="announcement_ddown btninvalid" id="btninvalid">
                                                <i class='bx bx-x'></i>
                                                <span class="">Mark as Invalid</span>
                                            </button>

                                            <script>
                                                $("#btninvalid").click(function(){
                                                    $("#modal-invalid").modal("show");
                                                })
                                            </script>


                                        <?php }?>
                                        <a target="_blank" href="controller.php?download-id=<?php echo $row['user_id']?>" class="announcement_ddown">
                                            <i class='bx bxs-download'></i>
                                            <span class="">Download</span>
                                        </a>
                                    </div>
                                </td>

                                <div class="modal fade" id="modal-invalid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Mark Report as Invalid</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body" style="overflow-x:hidden;">
                                                Reason/s of invalidation:<br>
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>">
                                                <input type="checkbox" id="reason1" name="reason1" value="Incomplete details">
                                                <label for="reason1">Incomplete details.</label><br>
                                                <input type="checkbox" id="reason2" name="reason2" value="Provided unclear/blurry test result">
                                                <label for="reason2">Provided unclear/blurry test result.</label><br>
                                                <textarea type="text" name="other-reasons" placeholder="Other reasons" class="announcement"></textarea>
                                            </div>

                                            <div class="modal-footer">
                                                <input type="submit" name="report-invalid" class="btn btn-secondary" style="background-color: var(--button-color);" data-bs-dismiss="modal" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                    <?php } 
                }?>
            </tbody>
        </table>
    </form>
</div>

<script>
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

} );
</script>

<script src="../javascript/alertTimeout.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/notif-permission.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/user-assistance-notif.js?v=<?php echo time(); ?>"></script>

<script src="../javascript/messages-notif.js?v=<?php echo time(); ?>"></script>

<!--Container Main end-->
<?php include "php/navigation2.php"; ?>
