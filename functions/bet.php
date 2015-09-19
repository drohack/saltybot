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

	// Get user data
	$sth = $conn->prepare('SELECT uniqueId, username, saltyBucks, betAmount, betSide, odds, wins, gamesPlayed FROM users WHERE uniqueId=\'' . $_COOKIE['uniqueID'] . '\';');
	$sth->execute();
	$row = $sth->fetch();
	if($row) {
		$uniqueId = $row['uniqueId'];
		$username = $row['username'];
		$saltyBucks = $row['saltyBucks'];
		$betAmount = $row['betAmount'];
		$betSide = $row['betSide'];
		$odds = $row['odds'];
		$wins = $row['wins'];
		$gamesPlayed = $row['gamesPlayed'];
	}
	
	//clear sth & row
	$sth = null;
	$row = null;
	
	// Check to see if user is betting within their means
	if($_GET['bet'] > 0 && $_GET['bet'] <= $saltyBucks) {
		// Get current video data
		$sth = $conn->prepare('SELECT video_id, file_name, video_type_id, length, start_time, red_fighter, blue_fighter, red_bet, blue_bet, red_odds, blue_odds FROM current_video;');
		$sth->execute();
		$row = $sth->fetch();
		if($row) {
			$current_video_id = $row['video_id'];
			$current_file_name = $row['file_name'];
			$current_video_type_id = $row['video_type_id'];
			$current_length = $row['length'];
			$current_start_time = $row['start_time'];
			$current_red_fighter = $row['red_fighter'];
			$current_blue_fighter = $row['blue_fighter'];
			$current_red_bet = $row['red_bet'];
			$current_blue_bet = $row['blue_bet'];
			$current_red_odds = $row['red_odds'];
			$current_blue_odds = $row['blue_odds'];
		}
		
		//clear sth & row
		$sth = null;
		$row = null;
		
		// Check to see if it is currently a betting time
		if($current_video_type_id == 1) {
			//save the bet
			if($_GET['fighter'] == $current_red_fighter) {
				$odds = $current_red_odds / $current_blue_odds;
			} else {
				$odds = $current_blue_odds / $current_red_odds;
			}
			$sth = $conn->prepare("UPDATE users SET betAmount=" . $_GET['bet'] . ", betSide='" . $_GET['fighter'] . "', odds=" . $odds . " WHERE uniqueId='" . $_COOKIE['uniqueID'] . "';");
			$sth->execute();
			
			//clear sth
			$sth = null;
			
			//Update the total bets depending on who the user voted for
			if($_GET['fighter'] == $current_red_fighter) {
				$sth = $conn->prepare("UPDATE current_video SET red_bet=" . ($current_red_bet + $_GET['bet']) . " WHERE id=1;");
				$sth->execute();
			} else {
				$sth = $conn->prepare("UPDATE current_video SET blue_bet=" . ($current_blue_bet + $_GET['bet']) . " WHERE id=1;");
				$sth->execute();
			}
		}
	}
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
	//close database connection 
	$conn = null;
	$sth = null;
	$row = null;
}
?>