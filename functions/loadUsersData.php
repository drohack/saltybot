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

	//run query and output results 
	//run a select query 
	$users_query = 'SELECT username, saltyBucks, betAmount, betSide, odds, wins, gamesPlayed FROM users ORDER BY (wins/IF(gamesPlayed = 0, 1, gamesPlayed)) desc'; 
	$users_result = mysql_query($users_query); 

	//output data in a table 
	echo "<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'\n"; 
	echo "<tr>\n";
	echo "<th>Username</th>\n";
	echo "<th>Salty Bucks</th>\n";
	echo "<th>Bet Amount</th>\n";
	echo "<th>Bet Side</th>\n";
	echo "<th>Odds</th>\n";
	echo "<th>Win Rate</th>\n";
	while ($row = mysql_fetch_array($users_result)){
		$winRate = "";
		if($row['wins'] != "" && $row['gamesPlayed'] != "") {
			$winRate = $row['wins'] / $row['gamesPlayed'] * 100;
		}
		echo "<tr>\n"; 
			echo "<td>" . $row['username'] . "</td>\n"; 
			echo "<td>" . $row['saltyBucks'] . "</td>\n"; 
			echo "<td>" . $row['betAmount'] . "</td>\n"; 
			echo "<td>" . $row['betSide'] . "</td>\n"; 
			echo "<td>" . $row['odds'] . "</td>\n"; 
			echo "<td>" . round($winRate, 2) . "%</td>\n"; 
		echo "</tr>\n"; 
	} 
	echo '</table>';
}

//close database connection 
mysql_close($db) 
?>