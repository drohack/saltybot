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

	//get the current playing video data
	$current_video_query = 'SELECT video_id, file_name, video_type_id, length, red_fighter, blue_fighter, red_odds, blue_odds, winner  FROM current_video;'; 
	$result = mysql_query($current_video_query); 
	while ($row = mysql_fetch_array($result)) 
	{
		$current_video_id = $row['video_id'];
		$current_file_name = $row['file_name'];
		$current_video_type_id = $row['video_type_id'];
		$current_length = $row['length'];
		$current_red_fighter = $row['red_fighter'];
		$current_blue_fighter = $row['blue_fighter'];
		$current_red_odds = $row['red_odds'];
		$current_blue_odds = $row['blue_odds'];
		$current_winner = $row['winner'];
	}
	
	//save the next video data as the current video
	$update_current_video_query = "UPDATE current_video SET start_time=" . time() . "' WHERE id=1;"; 
	mysql_query($update_current_video_query); 

	echo $current_file_name;
}

//close database connection 
mysql_close($db) 
?>