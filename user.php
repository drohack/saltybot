<link rel="stylesheet" href="style.css" type="text/css">

<?php 
include 'header.php';
include 'loadUserAndCurrentData.php';
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

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>

<script type='text/javascript'>
	function bet(side) {
		getRequest(
			'bet.php?side=' + side + '&bet=' + document.getElementById("bet").value, // URL for the PHP file
			loadData,  // handle successful request
			drawError    // handle error
		);
		return false;
	};
	// handles drawing an error message
	function drawError() {
		alert('Bummer: there was an error!');
	}
	// handles the response, adds the html
	function loadData() {
		getRequest(
			'loadUserAndCurrentData.php', // URL for the PHP file
			refreshInfo,  // handle successful request
			drawError    // handle error
		);
		return false;
	}
	function refreshInfo() {
		jQuery('#userInfo').load(document.URL +  ' #userInfo');
		jQuery('#bettingInfo').load(document.URL +  ' #bettingInfo');
		return false;
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

<p><font><strong>Logged in as: </strong><u><?php echo $username; ?></u></font> <input type="button" value="Refresh Page" onclick="location.reload();" style="float: right; padding: 15px 50px 15px 50px""></p>

<!-- output data in a table -->
<table id="userInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
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

<strong>Who's Fighting</strong> <div id="wait_div"></div>
<table id="bettingInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="2">Red / Odds</th>
		<th colspan="2">Blue / Odds</th>
	</tr>
	<tr>
		<td width="25%" align="center"><?php echo $current_red_fighter; ?></td>
		<td width="25%" align="center"><?php echo $current_red_odds; ?></td>
		<td width="25%" align="center"><?php echo $current_blue_fighter; ?></td>
		<td width="25%" align="center"><?php echo $current_blue_odds; ?></td>
	</tr>
</table>
<table id="bettingTable" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
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
		<td colspan="2" align="center"><input type="button" value="Bet Red" style="padding: 15px 50px 15px 50px" onclick="bet('Red')"/></td>
		<td colspan="2" align="center"><input type="button" value="Bet Blue" style="padding: 15px 50px 15px 50px" onclick="bet('Blue')"/></td>
	</tr>
</table>

<script type='text/javascript'>
    //This is run onLoad.
	//Take the current time minus the start time of the video and subtract that from the length of video and reload the whole page at the end of that time.
	var length = <?php echo $current_length; ?>;
	var current_time = parseInt((new Date).getTime() / 1000, 10);
	var start_time = <?php echo $current_start_time; ?>;
	var wait_time = ((length - (current_time - start_time)) * 1000);
	
	if(wait_time > 0) {
		setTimeout(function(){location.reload()}, wait_time);
		
		//If the video type is "Betting" (2) then refresh the user data & fighters data every 1 second.
		if(<?php echo $current_video_type_id; ?> == 2) {
			setInterval(function(){
				loadData();
				current_time = parseInt((new Date).getTime() / 1000, 10);
				time_left = (length - (current_time - start_time));
				document.getElementById("wait_div").innerHTML = "Time left: " + time_left + " seconds";
			}, 1000);
		} else {
			setInterval(function(){
				current_time = parseInt((new Date).getTime() / 1000, 10);
				time_left = (length - (current_time - start_time));
				document.getElementById("wait_div").innerHTML = "Time left: " + time_left + " seconds";
			}, 1000);
		}
	} else {
		//Error (video paused and I am off sync or end of all videos)
		document.getElementById("wait_div").innerHTML = "Error finding video. Please refresh this page when video has resumed playing.";
	}
</script>