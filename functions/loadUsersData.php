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

	//run query and output results 
	//run a select query 
	$users_query = $conn->prepare('SELECT id, username, saltyBucks, betAmount, betSide, odds, wins, gamesPlayed FROM users ORDER BY (wins/IF(gamesPlayed = 0, 1, gamesPlayed)) desc');
	$users_query->execute();
	
	$red_fighter_query = $conn->prepare('SELECT video_id, red_fighter FROM current_video WHERE id=1;');
	$red_fighter_query->execute();
	$red_fighter_row = $red_fighter_query->fetch();
	if($red_fighter_row) {
		$this_red_fighter = $red_fighter_row['red_fighter'];
		$last_video_id = $red_fighter_row['video_id'];
	}

	//output data in a table 
	echo "<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'\n"; 
	echo "<tr>\n";
	echo "<th>Username</th>\n";
	echo "<th>Salty Bucks</th>\n";
	echo "<th>Bet Amount</th>\n";
	echo "<th>Bet Side</th>\n";
	echo "<th>Odds</th>\n";
	echo "<th>Win Rate</th>\n";
	while ($row = $users_query->fetch()){
		//Get last winner
		$last_winner = null;
		$last_winner_query = $conn->prepare('SELECT winner FROM videos WHERE id=' . ($last_video_id - 1) . ';');
		$last_winner_query->execute();
		$last_winner_row = $last_winner_query->fetch();
		if($last_winner_row) {
			$last_winner = $last_winner_row['winner'];
		}
		
		//Get user's last bet 
		$last_bet_side = null;
		$last_bet_side_query = $conn->prepare('SELECT betSide FROM user_logs WHERE user_id=' . $row['id'] . ' AND video_id=' . ($last_video_id - 1) . ';');
		$last_bet_side_query->execute();
		$last_bet_side_row = $last_bet_side_query->fetch();
		if($last_bet_side_row) {
			$last_bet_side = $last_bet_side_row['betSide'];
		}
		
		//Check to see if there was previously a fight. And if the user previously bet on that fight.
		$previously_bet = 0;
		$previously_won = null;
		if($last_winner != null && $last_winner != "" && $last_bet_side != null && $last_bet_side != "") {
			//If the user bet correctly last fight then add a big green plus next to their name
			//Else add a big red minus next to their name
			$previously_bet = 1;
			if($last_winner == $last_bet_side) {
				$previously_won = 1;
			} else {
				$previously_won = 0;
			}
		}
		
		$winRate = "";
		if($row['wins'] != "" && $row['gamesPlayed'] != "") {
			$winRate = $row['wins'] / $row['gamesPlayed'] * 100;
		}
		$formatted_bet_side = "";
		if($row['betSide'] == $this_red_fighter) {
			$formatted_bet_side = '<font color="red">' . $row['betSide'] . '</font>';
		} else {
			$formatted_bet_side = '<font color="blue">' . $row['betSide'] . '</font>';
		}
		$formatted_odds = "";
		if($row['odds'] != ""){
			$formatted_odds =(number_format($row['odds'],2)+0);
		}
		echo "<tr>\n"; 
			echo "<td>" . $row['username'] . $last_winner . $last_bet_side . "</td>\n"; 
			echo "<td>";
			if($previously_bet == 1) {
				if($previously_won == 1) {
					echo '<div width="100%">' . $row['saltyBucks'] . '<strong><font color="green" style="padding-right: 10px;float:right;-webkit-transform: scale(2,1);-moz-transform: scale(2,1);-ms-transform: scale(2,1);transform: scale(2,1);">&uarr;</font></strong></div>';
				} else {
					echo '<div width="100%">' . $row['saltyBucks'] . '<strong><font color="red" style="padding-right: 10px;float:right;-webkit-transform: scale(2,1);-moz-transform: scale(2,1);-ms-transform: scale(2,1);transform: scale(2,1);">&darr;</font></strong><div>';
				}
			} else {
				echo $row['saltyBucks'];
			}
			echo "</td>\n";
			echo "<td>" . $row['betAmount'] . "</td>\n"; 
			echo "<td>" . $formatted_bet_side . "</td>\n"; 
			echo "<td>" . $formatted_odds . "</td>\n"; 
			echo "<td>" . round($winRate, 2) . "%</td>\n"; 
		echo "</tr>\n"; 
	} 
	echo '</table>';
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
	//close database connection 
	$conn = null;
	$users_query = null;
	$red_fighter_query = null;
	$row = null;
	$red_fighter_row = null;
}
?>