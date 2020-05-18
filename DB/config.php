<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'campus');
 
/* Attempt to connect to MySQL database */
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($connection === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}



// function test_input($data) {
// 	$data = trim($data);
// 	$data = stripslashes($data);
// 	$data = htmlspecialchars($data);
// return $data;
// }

?>