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

	// Get user data
	$user_query = 'SELECT uniqueId, username, saltyBucks, betAmount, betSide, odds, winRate FROM users WHERE uniqueId=\'' . $_COOKIE['uniqueID'] . '\';'; 
	$user_result = mysql_query($user_query); 
	
	while ($row = mysql_fetch_array($user_result)) 
	{
		$uniqueId = $row['uniqueId'];
		$username = $row['username'];
		$saltyBucks = $row['saltyBucks'];
		$betAmount = $row['betAmount'];
		$betSide = $row['betSide'];
		$odds = $row['odds'];
		$winRate = $row['winRate'];
	}
	
	// Get current video data
	$current_video_query = 'SELECT video_id, file_name, video_type_id, length, start_time, red_fighter, blue_fighter, red_odds, blue_odds  FROM current_video;'; 
	$current_video_result = mysql_query($current_video_query); 
	while ($row = mysql_fetch_array($current_video_result)) 
	{
		$current_video_id = $row['video_id'];
		$current_file_name = $row['file_name'];
		$current_video_type_id = $row['video_type_id'];
		$current_length = $row['length'];
		$current_start_time = $row['start_time'];
		$current_red_fighter = $row['red_fighter'];
		$current_blue_fighter = $row['blue_fighter'];
		$current_red_odds = $row['red_odds'];
		$current_blue_odds = $row['blue_odds'];
	}
}

//close database connection 
mysql_close($db);
?>

<script>
	function decreaseBet() {
		if(document.getElementById("bet").value > 0) {
			document.getElementById("bet").stepDown(1);
		}
	}
	function increaseBet() {
		if(document.getElementById("bet").value < <?php echo $saltyBucks; ?>) {
			document.getElementById("bet").stepUp(1);
		}
	}
</script>

<script>
	function bet(side) {
		getRequest(
			'bet.php?side=' + side + '&bet=' + document.getElementById("bet").value, // URL for the PHP file
			refreshPage,  // handle successful request
			drawError    // handle error
		);
		return false;
	};
	// handles drawing an error message
	function drawError() {
		alert('Bummer: there was an error!');
	}
	// handles the response, adds the html
	function refreshPage(responseText) {
		location.reload();
		return true;
	}
	// helper function for cross-browser request object
	function getRequest(url, success, error) {
		var req = false;
		try{
			// most browsers
			req = new XMLHttpRequest();
		} catch (e){
			// IE
			try{
				req = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				// try an older version
				try{
					req = new ActiveXObject("Microsoft.XMLHTTP");
				} catch(e) {
					return false;
				}
			}
		}
		if (!req) return false;
		if (typeof success != 'function') success = function () {};
		if (typeof error!= 'function') error = function () {};
		req.onreadystatechange = function(){
			if(req.readyState == 4) {
				return req.status === 200 ? 
					success(req.responseText) : error(req.status);
			}
		}
		req.open("GET", url, true);
		req.send(null);
		return req;
	}
</script>

<p><font><strong>Logged in as: </strong><u><?php echo $username; ?></u></font></p>

<!-- output data in a table -->
<table border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th>Salty Bucks</th>
		<th>Bet Amount</th>
		<th>Bet Side</th>
		<th>Odds</th>
		<th>Win Rate</th>
	</tr>
	<tr>
		<td width="20%" align="center"><?php echo $saltyBucks; ?></td>
		<td width="20%" align="center"><?php echo $betAmount; ?></td>
		<td width="20%" align="center"><?php echo $betSide; ?></td>
		<td width="20%" align="center"><?php echo $odds; ?>x</td>
		<td width="20%" align="center"><?php echo $winRate; ?></td>
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
		<td align="center"><?php echo $current_red_fighter; ?></td>
		<td align="center"><?php echo $current_red_odds; ?></td>
		<td align="center"><?php echo $current_blue_fighter; ?></td>
		<td align="center"><?php echo $current_blue_odds; ?></td>
	</tr>
	<tr>
		<td colspan="4" align="center" style="padding: 100px">
			<button onclick="decreaseBet()" style="padding: 15px 50px 15px 50px">-</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="number" id="bet" value=5 style="width:150px;">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button onclick="increaseBet()" style="padding: 15px 50px 15px 50px">+</button>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="button" value="Bet Red" style="width:50%;" onclick="bet('Red')"/></td>
		<td colspan="2" align="center"><input type="button" value="Bet Blue" style="width:50%;" onclick="bet('Blue')"/></td>
	</tr>
</table>