<?php 
//database server 
define('db_server', 'localhost'); 

//user, password, and database variables 
$db_user = 'dro'; 
$db_password = 'password';     
$db_dbname = 'saltybet'; 

//connect to the database server 
$db = mysql_connect(db_server, $db_user, $db_password); 
if (!$db) { 
   die('Could Not Connect: ' . mysql_error()); 
} else {
	//select database name 
	mysql_select_db($db_dbname); 
	
	//save the next video data as the current video
	$update_current_video_query = "UPDATE current_video SET start_time=" . time() . " WHERE id=1;"; 
	mysql_query($update_current_video_query);
}

//close database connection 
mysql_close($db) 
?>