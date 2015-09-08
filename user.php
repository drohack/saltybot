<link rel="stylesheet" href="style.css" type="text/css">

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

	/** 
	* Run MySQL query and output  
	* results in a HTML Table 
	*/ 
	$select_query = 'SELECT uniqueId, username, saltyBucks, betAmount, odds, winRate FROM users WHERE uniqueId=\'' . $_COOKIE['uniqueID'] . '\';'; 
	$result = mysql_query($select_query); 
	
	while ($row = mysql_fetch_array($result)) 
	{
		$uniqueId = $row['uniqueId'];
		$username = $row['username'];
		$saltyBucks = $row['saltyBucks'];
		$betAmount = $row['betAmount'];
		$odds = $row['odds'];
		$winRate = $row['winRate'];
	}
}

//close database connection 
mysql_close($db);
?>

<p><font><strong>Logged in as: </strong><u><?php echo $username; ?></u></font></p>

<!-- output data in a table -->
<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th>Salty Bucks</th>
		<th>Bet Amount</th>
		<th>Odds</th>
		<th>Win Rate</th>
	</tr>
	<tr>
		<td><?php echo $saltyBucks; ?></td>
		<td><?php echo $betAmount; ?></td>
		<td><?php echo $odds; ?></td>
		<td><?php echo $winRate; ?></td>
	</tr>
</table>

</br></br>

<strong>Who's Fighting</strong>
<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="2">Red / Odds</th>
		<th colspan="2">Blue / Odds</th>
	</tr>
	<tr>
		<td align="center">Fighter 1</td>
		<td align="center">50%</td>
		<td align="center">Fighter 2</td>
		<td align="center">50%</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="button" value="Bet Blue" style="width:50%;"/></td>
		<td colspan="2" align="center"><input type="button" value="Bet Red" style="width:50%;"/></td>
	</tr>
</table>