<?php 
//user, password, and database variables 
$db_server = 'localhost';
$db_user = 'dro'; 
$db_password = 'password';     
$db_dbname = 'saltybet'; 

try {
	//connect to the database server 
	$conn = new PDO("mysql:host=$db_server;dbname=$db_dbname", $db_user, $db_password);
	// set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//save the next video data as the current video
	$sth = $conn->prepare("UPDATE current_video SET start_time=" . time() . " WHERE id=1;");
	$sth->execute();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
	//close database connection 
	$conn = null;
	$sth = null;
}
?>