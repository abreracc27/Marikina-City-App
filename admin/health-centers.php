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

$sql2 = "SELECT * FROM health_centers";
$result = $conn->query($sql2);
$result2 = $conn->query($sql2);

if(isset($_GET['id'])){
    $center_id = $_GET['id'];

    $sql = "SELECT * FROM health_centers WHERE id ='$center_id'";
    $result3 = $conn->query($sql);
    while ($row3 = $result3->fetch_assoc()) {
        $name = $row3['name'];
        $address = $row3['address'];
        $hours = $row3['hours'];
        $phone = $row3['phone'];
        $medical_receptionist = $row3['medical_receptionist'];
        $latitude = $row3['latitude'];
        $longitude = $row3['longitude'];
    }
}

//execute the query

?>
<?php include "php/header1.php"; ?>
<title>Health Centers — Marikina City Health & Safety Application</title>
<?php include "php/header2.php"; ?>
<?php include "php/navigation.php"; ?>
<?php include "php/alert-message.php"; ?>
<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="row profile-form p-3">
        <div class="col-12 col-lg-5 order-lg-first line2">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label for="location">Location Name</label><br/>
                        <input type="text" class="login-credentials" id="location" name="location" value="<?php if(!empty($name)){echo $name;} ?>" placeholder="Enter location name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="address">Location Address</label><br/>
                        <input type="text" class="login-credentials" id="address" name="address" value="<?php if(!empty($address)){echo $address;} ?>" placeholder="Enter location address" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="hours">Operating Hours</label><br/>
                        <input type="text" class="login-credentials" id="hours" name="hours" value="<?php if(!empty($hours)){echo $hours;} ?>" placeholder="Enter operating hours">
                    </div>

                    <div class="col-6">
                        <label for="phone">Phone</label><br/>
                        <input type="text" class="login-credentials" id="phone" name="phone" value="<?php if(!empty($phone)){echo $phone;} ?>" placeholder="Enter phone number">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="medical-receptionist">Medical Receptionist</label><br/>
                        <input type="text" class="login-credentials" id="medical-receptionist" name="medical-receptionist" value="<?php if(!empty($medical_receptionist)){echo $medical_receptionist;} ?>" placeholder="Enter medical receptionist" >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="latitude">Latitude</label><br/>
                        <input type="text" class="login-credentials" id="latitude" name="latitude" value="<?php if(!empty($latitude)){echo $latitude;} ?>" required placeholder="Enter latitude" >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="longitude">Longitude</label><br/>
                        <input type="text" class="login-credentials" id="longitude" name="longitude" value="<?php if(!empty($longitude)){echo $longitude;} ?>" placeholder="Enter longitude" required>
                    </div>
                </div>

                <div class="row">
                    <?php 
                    $action = "";
                    if(isset($_GET['action_type'])){
                        $action = $_GET['action_type'];

                        if($action == 'edit'){ ?>
                            <div class='col-6'>
                                <a href='health-centers'><button type='button' class='cancel-button'>Cancel</button></a>
                            </div>

                            <div class='col-6'>
                                <input class='login-button' value='Update Pinned' name='update-pin' type='submit'>
                            </div>
                        <?php }
                    }else{ ?>
                        <div class='col-6'></div>
                        <div class='col-6'>
                            <input class='login-button' value='Pin Location' name='pin-location' type='submit'>
                        </div>
                    <?php }
                    ?>
                </div>
            </form> 
        </div>

        <div class="col-12 col-lg-7 order-first">
            <div id="map"></div>
        </div>
    </div>

    <hr>

    <form action="" method="POST" autocomplete="off">
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="row-head">ID</th>
                    <th class="row-head">Location Name</th>
                    <th class="row-head">Location Address</th>
                    <th class="row-head">Hours</th>
                    <th class="row-head">Phone</th>
                    <th class="row-head">Medical Receptionist</th>
                    <th class="row-head">Latitude</th>
                    <th class="row-head">Longitude</th>
                    <th class="row-head">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        ?>
                            <tr>
                                <td id="data"><?php echo $row2['id']; ?></td>
                                <td id="data"><?php echo $row2['name']; ?></td>
                                <td id="data"><?php echo $row2['address']; ?></td>
                                <td id="data"><?php echo $row2['hours']; ?></td>
                                <td id="data"><?php echo $row2['phone']; ?></td>
                                <td id="data"><?php echo $row2['medical_receptionist']; ?></td>
                                <td id="data"><?php echo $row2['latitude']; ?></td>
                                <td id="data"><?php echo $row2['longitude']; ?></td>
                                <td id="data">

                                    <a href="#" class="d-flex align-items-center dropdown-toggle action-btn" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                     </a>
                                    
                                    <div class="dropdown-menu" aria-labelledby="dropdownUser3" style="position: fixed; z-index: 100;">  
                                        <a href="health-centers?action_type=edit&id=<?php echo $row2['id']?>" class="announcement_ddown">
                                            <i class='bx bx-edit nav_icon'></i>
                                            Edit
                                        </a>
                                        <a href="controller?delete-location-id=<?php echo $row2['id']?>" class="announcement_ddown">
                                            <i class='bx bx-trash nav_icon'></i>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php } 
                }?>
            </tbody>
        </table>
    </form>
<!--Container Main end-->
</div>
<script>
    function initMap(){
        
    // Map options
    var options = {
        zoom: 14,
        center:{lat:14.6507,lng:121.1029},
        styles: [    
            {
                "featureType": "poi",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text",
                "stylers": [
                {
                    "visibility": "on"
                }
                ]
            },
            {
                "featureType": "poi.attraction",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.business",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.government",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.medical",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "on"
                }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.place_of_worship",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.school",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            },
            {
                "featureType": "poi.sports_complex",
                "elementType": "labels.icon",
                "stylers": [
                {
                    "visibility": "off"
                }
                ]
            }
        ] 
    }

    // New map
    var map = new google.maps.Map(document.getElementById('map'), options);

    // Listen for click on map
    var markers = [
        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {?>
            {
            coords:{lat:<?php echo $row['latitude']?>,lng:<?php echo $row['longitude']?>},
            iconImage:'../images/hospital-marker.png',
            content:"<h6><?php echo $row['name']?></h6>"+
            "<div class='my-2'><?php echo $row['address']?></div>"+
            "<div class='my-2'><?php echo $row['hours']?></div>"+
            "<div class='my-2'><?php echo $row['phone']?></div>"+
            "<div class='my-2'><?php echo $row['medical_receptionist']?></div><br>"+
            "<div class='my-2'>COVID-19 test available here.</div>"
            },
        <?php } }?>
    ];

      // Loop through markers
    for(var i = 0;i < markers.length;i++){
        // Add marker
        addMarker(markers[i]);
    }

      // Add Marker Function
    function addMarker(props){
        var marker = new google.maps.Marker({
            position:props.coords,
            map:map,
        //icon:props.iconImage
        });

        // Check for customicon
        if(props.iconImage){
            // Set icon image
            marker.setIcon(props.iconImage);
        }

        // Check content
        if(props.content){

            var infoWindow = new google.maps.InfoWindow({
                content:props.content
            });

            marker.addListener('click', function(){
                infoWindow.open(map, marker);
            });
        }
    }

    var clickMarker = new google.maps.Marker({
        draggable: true,
        map: map,
        icon:'../images/hospital-marker.png',
    });

    const geocoder = new google.maps.Geocoder();

    google.maps.event.addListener(clickMarker, 'dragend', function(event) {
        document.getElementById("latitude").value = event.latLng.lat();
        document.getElementById("longitude").value = event.latLng.lng();

        const ccoords = event.latLng;
        geocoder.geocode({'latLng': ccoords}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    const addr = results[1].formatted_address;
                    document.getElementById("address").value = addr;
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
    });


    google.maps.event.addListener(map, 'click', function(event) {
        document.getElementById("latitude").value = event.latLng.lat();
        document.getElementById("longitude").value = event.latLng.lng();
        clickMarker.setPosition(event.latLng);

        const ccoords = event.latLng;
        geocoder.geocode({'latLng': ccoords}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    const addr = results[1].formatted_address;
                    document.getElementById("address").value = addr;
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
    });
}

google.maps.event.addDomListener(window, "load", initialize());
</script>
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

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBL9Y78cLma1-i464zWwKrtAzhirMSnFKk&callback=initMap">
</script>
<?php include "php/navigation2.php"; ?>
