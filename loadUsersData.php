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
	$users_query = 'SELECT username, saltyBucks, betAmount, betSide, odds, winRate FROM users ORDER BY winRate'; 
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
	while ($row = mysql_fetch_row($users_result)){     
		echo "<tr>\n"; 
		foreach ($row as $val) { 
			echo "<td>$val</td>\n"; 
		} 
		echo "</tr>\n"; 
	} 
	echo '</table>';
}

//close database connection 
mysql_close($db) 
?>