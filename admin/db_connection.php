<?php
$servername = 'localhost';
$username = 'root';
$password = "";
$db_name = "shortslug_db";


$conn = new mysqli($servername, $username, $password, $db_name);
if($conn->connect_error){
    die ("Database Connection Error". $conn->connect_error());
}

?>