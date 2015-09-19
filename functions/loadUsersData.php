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
	$users_query = $conn->prepare('SELECT username, saltyBucks, betAmount, betSide, odds, wins, gamesPlayed FROM users ORDER BY (wins/IF(gamesPlayed = 0, 1, gamesPlayed)) desc');
	$users_query->execute();
	
	$red_fighter_query = $conn->prepare('SELECT red_fighter FROM current_video WHERE id=1;');
	$red_fighter_query->execute();
	$red_fighter_row = $red_fighter_query->fetch();
	if($red_fighter_row) {
		$this_red_fighter = $red_fighter_row['red_fighter'];
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
			echo "<td>" . $row['username'] . "</td>\n"; 
			echo "<td>" . $row['saltyBucks'] . "</td>\n"; 
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