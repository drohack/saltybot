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
	}
	
	//clear sth & row
	$sth = null;
	$row = null;
	
	//Update the odds based on the current bets
	$red_odds  = ($current_blue_bet / $current_red_bet);
	$blue_odds = ($current_red_bet / $current_blue_bet);
	$sth = $conn->prepare("UPDATE current_video SET red_odds=" . $red_odds . ", blue_odds=" . $blue_odds . ";");
	$sth->execute();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
	//close database connection 
	$conn = null;
	$sth = null;
	$row = null;
}
?>