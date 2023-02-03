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

$sql = "SELECT * FROM health_centers";
$result = $conn->query($sql);

//execute the query

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
            <i class="material-icons" style="line-height: 45px; font-size: 24px !important; color: var(--button-color)">manage_accounts</i>
        </button>
    </div>     
</header>   

<?php include "php/sidenav.php"; ?>

<!--Container Main start-->
<div class="main-content container-fluid">
    <div class="mapInfo" id="mapInfo">
        <section class="section2" id="inputsec">
            <div class="row mb-3">
                <div class="col-10 col-lg-11 mt-3 order-lg-first">
                    <div class="col-12 col-lg-12 order-lg-first">
                        <div class="input-box2">
                            <span class="prefix2"><i class="material-icons" style="color: var(--button-color)">trip_origin</i></span>
                            <input type="text" id="from" placeholder="Origin" class="login-credentials" onkeyup="clearFrom2()">
                            <input type="hidden" id="from2">
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 mt-3">
                        <div class="input-box2">
                            <span class="prefix2"><i class="material-icons" style="color: var(--button-color)">place</i></span>
                            <input type="text" id="to" placeholder="Destination" class="login-credentials" onkeyup="clearTo2()">
                            <input type="hidden" id="to2">
                        </div>
                    </div>
                </div>
                <div class="col-2 col-lg-1 mt-3 ps-1" style="display: flex; align-items: center;">
                    <button onclick="switchInput()" style="border: none; background: transparent;"><i class="material-icons" style="font-size: 32px !important; color: var(--button-color)">import_export</i></button>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-12" style="display: flex; align-items: center;">
                    <button class="getdir" onclick="calcRoute();">Get direction</button>
                    <button onclick="getLocation()" class="getloc">My location</button>
                </div>
            </div>
        </section>
        <section class="section2" id="infoDis">
            <div class="row mb-3">
                <div class="col-12 col-lg-12 order-lg-first">
                    <button id="back-input" style="border: none; background: transparent;"><i class="material-icons" style="line-height: 45px; font-size: 24px !important; color: var(--button-color)">arrow_back</i></button>
                </div>
                <div class="col-12 col-lg-12">
                    Origin: <span class="dirmapinfo" id="from-display"></span>
                </div>
                <div class="col-12 col-lg-12 mt-3">
                    Destination: <span class="dirmapinfo" id="to-display"></span>
                </div>
                <div class="col-12 col-lg-12 mt-3">
                    Distance: <span class="dirmapinfo" id="distance-display"></span>
                </div>
                <div class="col-12 col-lg-12 mt-3">
                    Duration: <span class="dirmapinfo" id="duration-display"></span>
                </div>
            </div>
        </section>
    </div>
    
    <div class="row map-area pb-3">
        <div class="col-12 col-lg-12">
            <div id="map"></div>
        </div>
    </div>
</div>

<?php include "php/navigation.php"; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBL9Y78cLma1-i464zWwKrtAzhirMSnFKk&libraries=places"></script>
<script>
    //javascript.js
//set map options
var myLatLng = {lat:14.6507,lng:121.1029};
var mapOptions = {
    center: myLatLng,
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
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
};

//create map
var map = new google.maps.Map(document.getElementById('map'), mapOptions);

//create a DirectionsService object to use the route method and get a result for our request
var directionsService = new google.maps.DirectionsService();

//create a DirectionsRenderer object which we will use to display the route
var directionsDisplay = new google.maps.DirectionsRenderer();

//bind the DirectionsRenderer to the map
directionsDisplay.setMap(map);

var markers = [
    <?php 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {?>
        {
        coords:{lat:<?php echo $row['latitude']?>,lng:<?php echo $row['longitude']?>},
        iconImage:'images/hospital-marker.png',
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


//define calcRoute function
function calcRoute() {
    //create request

    var getFrom = document.getElementById("from2");
    var getTo = document.getElementById("to2");
    if(getFrom.value == ""){
        var inputFrom = document.getElementById("from");
    }else{
        var inputFrom = document.getElementById("from2");
    }

    if(getTo.value == ""){
        var inputTo = document.getElementById("to");
    }else{
        var inputTo = document.getElementById("to2");
    }
    var request = {
        origin: inputFrom.value,
        destination: inputTo.value,
        travelMode: google.maps.TravelMode.DRIVING, //WALKING, BYCYCLING, TRANSIT
        unitSystem: google.maps.UnitSystem.METRIC
    }

    //pass the request to the route method
    directionsService.route(request, function (result, status) {
        if (status == google.maps.DirectionsStatus.OK) {

            document.getElementById('infoDis').scrollIntoView({behavior: "smooth"});

            //Get distance and time
            const fromOutput = document.querySelector('#from-display');
            const toOutput = document.querySelector('#to-display');
            const distanceOutput = document.querySelector('#distance-display');
            const durationOutput = document.querySelector('#duration-display');

            fromOutput.innerHTML = document.getElementById("from").value;
            toOutput.innerHTML = document.getElementById("to").value;
            distanceOutput.innerHTML = result.routes[0].legs[0].distance.text;
            durationOutput.innerHTML = result.routes[0].legs[0].duration.text;

            //display route
            directionsDisplay.setDirections(result);
        } else {
            //delete route from map
            directionsDisplay.setDirections({ routes: [] });
            //center map in London
            map.setCenter(myLatLng);

            //show error message
            distanceOutput.innerHTML = "<div class='alert-danger'><i class='fas fa-exclamation-triangle'></i> Could not retrieve driving distance.</div>";
        }
    });
}

var x = document.getElementById("from");
var y = document.getElementById("from2");
var z = document.getElementById("to");
var a = document.getElementById("to2");

var temp;
var temp2;

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else { 
    x.value = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
    x.value = "My location";
    y.value = position.coords.latitude + "," + position.coords.longitude;

    var icon = {
        url: "images/startpin.png", // url
        scaledSize: new google.maps.Size(20, 20), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(0, 0) // anchor
    };


    var userPosition = {
        coords:{lat:position.coords.latitude,lng: position.coords.longitude},
        iconImage: icon,
        content:"<h6>Me</h6>"
    }

    addIcon(userPosition);

    function addIcon(props){
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

    map.setCenter({lat:position.coords.latitude,lng: position.coords.longitude});

}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.value = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.value = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.value = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.value = "An unknown error occurred."
      break;
  }
}

function switchInput(){
    temp = y.value;
    y.value = a.value;
    a.value = temp;

    temp2 = x.value;
    x.value = z.value;
    z.value = temp2; 
}


function clearFrom2(){
    y.value = "";
}

function clearTo2(){
    a.value = "";
}
//create autocomplete objects for all inputs
var options = {
    type: ['hospital'],
    componentRestrictions: {country: "ph"}
}

var input1 = document.getElementById("from");
var autocomplete1 = new google.maps.places.Autocomplete(input1, options);

var input2 = document.getElementById("to");
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);


$("#back-input").click(function(){
    document.getElementById('inputsec').scrollIntoView({behavior: "smooth"});
});

</script>
</body>
</html>
