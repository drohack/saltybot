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

	// Get user data
	$user_query = 'SELECT uniqueId, username, saltyBucks, betAmount, betSide, odds, wins, gamesPlayed FROM users WHERE uniqueId=\'' . $_COOKIE['uniqueID'] . '\';'; 
	$user_result = mysql_query($user_query); 
	
	while ($row = mysql_fetch_array($user_result)) 
	{
		$uniqueId = $row['uniqueId'];
		$username = $row['username'];
		$saltyBucks = $row['saltyBucks'];
		$betAmount = $row['betAmount'];
		$betSide = $row['betSide'];
		$odds = $row['odds'];
		$wins = $row['wins'];
		$gamesPlayed = $row['gamesPlayed'];
	}
	
	// Check to see if user is betting within their means
	if($_GET['bet'] > 0 && $_GET['bet'] <= $saltyBucks) {
		// Get current video data
		$current_video_query = 'SELECT video_id, file_name, video_type_id, length, start_time, red_fighter, blue_fighter, red_odds, blue_odds FROM current_video;'; 
		$current_video_result = mysql_query($current_video_query); 
		while ($row = mysql_fetch_array($current_video_result)) 
		{
			$current_video_id = $row['video_id'];
			$current_file_name = $row['file_name'];
			$current_video_type_id = $row['video_type_id'];
			$current_length = $row['length'];
			$current_start_time = $row['start_time'];
			$current_red_fighter = $row['red_fighter'];
			$current_blue_fighter = $row['blue_fighter'];
			$current_red_odds = $row['red_odds'];
			$current_blue_odds = $row['blue_odds'];
		}
		
		// Check to see if it is currently a betting time
		if($current_video_type_id == 1) {
			//save the bet
			if($_GET['fighter'] == $current_red_fighter) {
				$odds = $current_red_odds / $current_blue_odds;
			} else {
				$odds = $current_blue_odds / $current_red_odds;
				
			}
			$update_bet_query = "UPDATE users SET betAmount=" . $_GET['bet'] . ", betSide='" . $_GET['fighter'] . "', odds=" . $odds . " WHERE uniqueId='" . $_COOKIE['uniqueID'] . "';"; 
			mysql_query($update_bet_query);
		}
	}
}

//close database connection 
mysql_close($db) 
?>