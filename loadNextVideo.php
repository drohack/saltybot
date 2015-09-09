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
	
	//get the next video data
	$next_video_query = 'SELECT id, file_name, video_type_id, red_fighter, blue_fighter, winner, length FROM videos WHERE id=' . ($current_video_id + 1) . ';'; 
	$result = mysql_query($next_video_query); 
	while ($row = mysql_fetch_array($result)) 
	{
		$next_id = $row['id'];
		$next_file_name = $row['file_name'];
		$next_video_type_id = $row['video_type_id'];
		$next_red_fighter = $row['red_fighter'];
		$next_blue_fighter = $row['blue_fighter'];
		$next_winner = $row['winner'];
		$next_length = $row['length'];
	}
	
	if($next_winner == null || $next_winner == "") {
		$next_winner = $current_winner;
	}
	
	//save the next video data as the current video
	$update_current_video_query = "UPDATE current_video SET video_id=" . $next_id . ", file_name='" . $next_file_name . "', video_type_id=" . $next_video_type_id . ", length=" . $next_length .
		", start_time=" . time() .
		", red_fighter='" . $next_red_fighter . "', blue_fighter='" . $next_blue_fighter . "', red_odds=50, blue_odds=50" . ", winner='" . $next_winner . "'" .
		", last_red_fighter='" . $current_red_fighter . "', last_blue_fighter='" . $current_blue_fighter . "'" .
		", last_red_odds=" . $current_red_odds . ", last_blue_odds=" . $current_blue_odds . ", last_winner='" . $current_winner . "' WHERE id=1;"; 
	mysql_query($update_current_video_query); 

	echo $next_file_name;
}

//close database connection 
mysql_close($db) 
?>