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
	$current_video_query = 'SELECT video_id, file_name, video_type_id, length, red_fighter, blue_fighter, red_odds, blue_odds, winner, last_red_fighter, last_blue_fighter, last_red_odds, last_blue_odds, last_winner FROM current_video;'; 
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
		$last_red_fighter = $row['last_red_fighter'];
		$last_blue_fighter = $row['last_blue_fighter'];
		$last_red_odds = $row['last_red_odds'];
		$last_blue_odds = $row['last_blue_odds'];
		$last_winner = $row['last_winner'];
	}
	
	//Update all users that have bet with their winnings/losings (if the current video type is 2 (fighting))
	if($current_video_type_id == 2) {
		$all_betting_users_query = 'SELECT * FROM users WHERE betAmount IS NOT null AND betSide IS NOT null AND odds IS NOT null;';
		$all_bets_result = mysql_query($all_betting_users_query);
		while ($user = mysql_fetch_array($all_bets_result)) 
		{
			$saltyBucks = $user['saltyBucks'];
			if($user['wins'] == "") {
				$wins = 0;
			} else {
				$wins = $user['wins'];
			}
			if($user['gamesPlayed'] == "") {
				$gamesPlayed = 1;
			} else {
				$gamesPlayed = $user['gamesPlayed'] + 1;
			}
			//If user bet correctly add their winnings to their account
			//Else subtract their bet amount to their account
			if($user['betSide'] == $current_winner) {
				$saltyBucks = $saltyBucks + ($user['betAmount'] * $user['odds']);
				$wins = $wins + 1;
			} else {
				$saltyBucks = $saltyBucks - $user['betAmount'];
			}
			
			//Reset user to 10 saltyBucks if they are below the amount
			if($saltyBucks < 10) {
				$saltyBucks = 10;
			}
			
			//Update user
			$update_user_query = 'UPDATE users SET saltyBucks=' . $saltyBucks . ' , betAmount=null, betSide=null, odds=null, wins=' . $wins . ', gamesPlayed=' . $gamesPlayed . ' WHERE uniqueId=\'' . $user['uniqueId'] . '\';';
			mysql_query($update_user_query); 
		}
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
	
	$red_odds = "50";
	$blue_odds = "50";
	
	if($next_video_type_id == 2) {
		$current_winner = $last_winner;
	} else if($next_video_type_id == 3) {
		$red_odds = "null";
		$blue_odds = "null";
	}
	
	if($next_video_type_id != 3) {
		if($last_red_fighter != null) {
			$current_red_fighter = $last_red_fighter;
		} else {
			$current_red_fighter = null;
		}
		if($last_blue_fighter != null) {
			$current_blue_fighter = $last_blue_fighter;
		} else {
			$current_blue_fighter = null;
		}
		if($last_red_odds != null) {
			$current_red_odds = $last_red_odds;
		} else {
			$current_red_odds = null;
		}
		if($last_blue_odds != null) {
			$current_blue_odds = $last_blue_odds;
		} else {
			$current_blue_odds = null;
		}
	}
	
	if($current_red_odds == null) {
		$current_red_odds = "null";
	}
	if($current_blue_odds == null) {
		$current_blue_odds = "null";
	}
	
	//save the next video data as the current video
	$update_current_video_query = "UPDATE current_video SET video_id=" . $next_id . ", file_name='" . $next_file_name . "', video_type_id=" . $next_video_type_id . ", length=" . $next_length .
		", start_time=" . time() .
		", red_fighter='" . $next_red_fighter . "', blue_fighter='" . $next_blue_fighter . "', red_odds=" . $red_odds . ", blue_odds=" . $blue_odds . ", winner='" . $next_winner . "'" .
		", last_red_fighter='" . $current_red_fighter . "', last_blue_fighter='" . $current_blue_fighter . "'" .
		", last_red_odds=" . $current_red_odds . ", last_blue_odds=" . $current_blue_odds . ", last_winner='" . $current_winner . "' WHERE id=1;"; 
	mysql_query($update_current_video_query); 

	echo $next_file_name;
}

//close database connection 
mysql_close($db) 
?>