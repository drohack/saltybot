<?php 
//database server 
define('db_server', 'localhost'); 

//user, password, and database variables 
$db_user = 'dro'; 
$db_password = 'password';     
$db_dbname = 'saltybet'; 

include 'header.php';

//connect to the database server 
$db = mysql_connect(db_server, $db_user, $db_password); 
if (!$db) { 
   die('Could Not Connect: ' . mysql_error()); 
} else {
	//select database name 
	mysql_select_db($db_dbname); 

	//run query and output results 
	//run a select query 
	$select_query = 'SELECT uniqueId, username, saltyBucks, betAmount, odds, winRate FROM users'; 
	$result = mysql_query($select_query); 

	//output data in a table 
	echo "<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'\n"; 
	echo "<tr>\n";
	echo "<th>Unique ID</th>\n";
	echo "<th>Username</th>\n";
	echo "<th>Salty Bucks</th>\n";
	echo "<th>Bet Amount</th>\n";
	echo "<th>Odds</th>\n";
	echo "<th>Win Rate</th>\n";
	while ($row = mysql_fetch_row($result)){     
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