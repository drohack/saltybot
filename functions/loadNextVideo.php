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
	
	$last_red_odds=null;
	$last_blue_odds=null;

	//get the current playing video data
	$sth = $conn->prepare('SELECT video_id, file_name, video_type_id, length, red_fighter, blue_fighter, red_bet, blue_bet, red_odds, blue_odds, winner, last_red_fighter, last_blue_fighter, last_red_bet, last_blue_bet, last_red_odds, last_blue_odds, last_winner FROM current_video;');
	$sth->execute();
	$row = $sth->fetch();
	if($row) {
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
	} else {
		throw new Exception("Could not get current playing video");
	}
	
	//clear sth & row
	$sth = null;
	$row = null;
	
	//Update all users that have bet with their winnings/losings (if the current video type is 2 (fighting))
	if($current_video_type_id == 2) {
		$sth = $conn->prepare('SELECT * FROM users WHERE betAmount IS NOT null AND betSide IS NOT null AND odds IS NOT null;');
		$sth->execute();
		while ($user = $sth->fetch()) {
			//Save all users that bet into the user_logs table
			$sth2 = $conn->prepare('INSERT INTO user_logs (user_id, video_id, betAmount, betSide, odds) VALUES (' . $user['id'] . ', ' . $current_video_id . ', ' . $user['betAmount'] . ', \'' . $user['betSide'] . '\', ' . $user['odds'] . ');');
			$sth2->execute();
			$sth2 = null;
			
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
			$sth3 = $conn->prepare('UPDATE users SET saltyBucks=' . $saltyBucks . ' , betAmount=null, betSide=null, odds=null, wins=' . $wins . ', gamesPlayed=' . $gamesPlayed . ' WHERE uniqueId=\'' . $user['uniqueId'] . '\';');
			$sth3->execute();
			$sth3 = null;
		}
		
		//clear sth & user
		$sth = null;
		$user = null;
	}
	
	
	//get the next video data
	$sth = $conn->prepare('SELECT id, file_name, video_type_id, red_fighter, blue_fighter, winner FROM videos WHERE id=' . ($current_video_id + 1) . ';');
	$sth->execute();
	$row = $sth->fetch();
	if($row) {
		$next_id = $row['id'];
		$next_file_name = $row['file_name'];
		$next_video_type_id = $row['video_type_id'];
		$next_red_fighter = $row['red_fighter'];
		$next_blue_fighter = $row['blue_fighter'];
		$next_winner = $row['winner'];
	} else {
		throw new Exception("No next video found");
	}
	
	//clear sth & row
	$sth = null;
	$row = null;
	
	// include getID3() library (can be in a different directory if full path is specified) 
	require_once('getid3/getid3.php'); 
	// Initialize getID3 engine 
	$getID3 = new getID3; 
	// Get next video's length wigh getID3
	$DirectoryToScan = '../videos/' . $next_file_name; 
	$file = $getID3->analyze($DirectoryToScan); 
	$next_length = round($file['playtime_seconds']);

	//Reset bets and odds to 1 at the start of betting
	if($next_video_type_id == 1) {
		$red_bet = "10";
		$blue_bet = "10";
		$red_odds = "1";
		$blue_odds = "1";
	} else {
		$red_bet = $current_red_bet;
		$blue_bet = $current_blue_bet;
		$red_odds = $current_red_odds;
		$blue_odds = $current_blue_odds;
	}
	
	//If the next video is a fight then do not update the "last fighter info". Do this by loading the current fighter info with current "last fighter info"
	if($next_video_type_id == 2) {
		$current_winner = $last_winner;

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
		if($last_red_bet != null) {
			$current_red_bet = $last_red_bet;
		} else {
			$current_red_bet = "null";
		}
		if($last_blue_bet != null) {
			$current_blue_bet = $last_blue_bet;
		} else {
			$current_blue_bet = "null";
		}
		if($last_red_odds != null) {
			$current_red_odds = $last_red_odds;
		} else {
			$current_red_odds = "null";
		}
		if($last_blue_odds != null) {
			$current_blue_odds = $last_blue_odds;
		} else {
			$current_blue_odds = "null";
		}
	}
	
	if($current_red_odds == null) {
		$current_red_odds = "null";
	}
	if($current_blue_odds == null) {
		$current_blue_odds = "null";
	}
	if($current_red_bet == null) {
		$current_red_bet = "null";
	}
	if($current_blue_bet == null) {
		$current_blue_bet = "null";
	}
	
	//save the next video data as the current video
	$sth = $conn->prepare("UPDATE current_video SET video_id=" . $next_id . ", file_name='" . $next_file_name . "', video_type_id=" . $next_video_type_id . ", length=" . $next_length .
		", start_time=" . time() .
		", red_fighter='" . $next_red_fighter . "', blue_fighter='" . $next_blue_fighter . "', red_bet=" . $red_bet . ", blue_bet=" . $blue_bet .
		", red_odds=" . $red_odds . ", blue_odds=" . $blue_odds . ", winner='" . $next_winner . "'" .
		", last_red_fighter='" . $current_red_fighter . "', last_blue_fighter='" . $current_blue_fighter . "'" .
		", last_red_bet=" . $current_red_bet . ", last_blue_bet=" . $current_blue_bet .
		", last_red_odds=" . $current_red_odds . ", last_blue_odds=" . $current_blue_odds . ", last_winner='" . $current_winner . "' WHERE id=1;"); 
	$sth->execute();

	echo $next_file_name;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} catch(Exception $e) {
	echo "Exception: " . $e->getMessage();
} finally {
	//close database connection 
	$conn = null;
	$sth = null;
	$row = null;
}
?>