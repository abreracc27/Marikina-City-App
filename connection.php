<?php 
// $servername = "localhost";
// $username = "u973933805_admin"; // default username for localhost is root
// $password = "Admin123"; // default password for localhost is empty
// $dbname = "u973933805_marikina_db"; // database name

$servername = "localhost";
$username = "root"; // default username for localhost is root
$password = ""; // default password for localhost is empty
$dbname = "marikina_db"; // database name

// $servername = "";
// $username = "root"; // default username for localhost is root
// $password = ""; // default password for localhost is empty
// $dbname = "marikina_db"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>