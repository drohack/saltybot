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
	
	$last_red_odds=null;
	$last_blue_odds=null;

	//get the current playing video data
	$current_video_query = 'SELECT video_id, file_name, video_type_id, length, red_fighter, blue_fighter, red_bet, blue_bet, red_odds, blue_odds, winner, last_red_fighter, last_blue_fighter, last_red_bet, last_blue_bet, last_red_odds, last_blue_odds, last_winner FROM current_video;'; 
	$result = mysql_query($current_video_query); 
	while ($row = mysql_fetch_array($result)) 
	{
		$current_video_id = $row['video_id'];
		$current_file_name = $row['file_name'];
		$current_video_type_id = $row['video_type_id'];
		$current_length = $row['length'];
		$current_red_fighter = $row['red_fighter'];
		$current_blue_fighter = $row['blue_fighter'];
		$current_red_bet = $row['red_bet'];
		$current_blue_bet = $row['blue_bet'];
		$current_red_odds = $row['red_odds'];
		$current_blue_odds = $row['blue_odds'];
		$current_winner = $row['winner'];
		$last_red_fighter = $row['last_red_fighter'];
		$last_blue_fighter = $row['last_blue_fighter'];
		$last_red_bet = $row['last_red_bet'];
		$last_blue_bet = $row['last_blue_bet'];
		$last_red_odds = $row['last_red_odds'];
		$last_blue_odds = $row['last_blue_odds'];
		$last_winner = $row['last_winner'];
	}
	
	//Update the odds based on the current bets
	$red_odds  = ($current_blue_bet / $current_red_bet);
	$blue_odds = ($current_red_bet / $current_blue_bet);
	$update_odds_query = "UPDATE current_video SET red_odds=" . $red_odds . ", blue_odds=" . $blue_odds . ";";
	mysql_query($update_odds_query); 
}

//close database connection 
mysql_close($db) 
?>