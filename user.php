<html>
<link rel="stylesheet" href="style.css" type="text/css">

<?php 
include 'header.php';
include 'functions/loadUserAndCurrentData.php';
?>

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>

<script type='text/javascript'>
	function decreaseBet() {
		if(document.getElementById("bet").value > 0) {
			document.getElementById("bet").stepDown(1);
			updatePayout();
		}
	}
	function increaseBet() {
		if(document.getElementById("bet").value < <?php echo $saltyBucks; ?>) {
			document.getElementById("bet").stepUp(1);
			updatePayout();
		}
	}
	function updatePayout(){
		var redPayout = document.getElementById("bet").value * (<?php echo $current_red_odds; ?> / <?php echo $current_blue_odds; ?>);
		var bluePayout = document.getElementById("bet").value * (<?php echo $current_blue_odds; ?> / <?php echo $current_red_odds; ?>);
		document.getElementById("redPayout").innerHTML = "($" + redPayout + ")";
		document.getElementById("bluePayout").innerHTML = "($" + bluePayout + ")";
	}
</script>

<script type='text/javascript'>
	function bet(side) {
		document.getElementById("bet").disabled = true;
		document.getElementById("plus_button").disabled = true;
		document.getElementById("minus_button").disabled = true;
		document.getElementById("bet_red_button").disabled = true;
		document.getElementById("bet_blue_button").disabled = true;
		$.ajax({
			type: 'GET',
			url: 'functions/bet.php?fighter=' + side + '&bet=' + document.getElementById("bet").value,
			success: loadData,
			error: drawError
		 });
		return false;
	};
	// handles drawing an error message
	function drawError() {
		alert('Bummer: there was an error!');
	}
	// handles the response, adds the html
	function loadData() {
		$.ajax({
			type: 'GET',
			url: 'functions/loadUserAndCurrentData.php',
			success: refreshInfo,
			error: drawError
		 });
		return false;
	}
	function refreshInfo() {
		jQuery('#userInfo').load(document.URL +  ' #userInfo');
		jQuery('#bettingInfo').load(document.URL +  ' #bettingInfo', function(){
			updatePayout();
		});
		return false;
	}
</script>

<p><font><strong>Logged in as: </strong><u><?php echo $username; ?></u></font> <input type="button" value="Refresh Page" onclick="location.reload();" style="float: right; padding: 15px 50px 15px 50px"></p>

<!-- output data in a table -->
<table id="userInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th>Salty Bucks</th>
		<th>Bet Amount</th>
		<th>Bet On</th>
		<th>Odds(Payout)</th>
		<th>Win Rate</th>
	</tr>
	<tr>
		<td width="20%" align="center"><?php echo $saltyBucks; ?></td>
		<td width="20%" align="center"><?php echo $betAmount; ?></td>
		<td width="20%" align="center"><?php echo $betSide; ?></td>
		<td width="20%" align="center"><?php echo $odds; ?>x ($<?php echo $payout; ?>)</td>
		<td width="20%" align="center"><?php echo $winRate; ?>%</td>
	</tr>
</table>

</br></br>

<strong>Who's Fighting</strong> <div id="wait_div"></div>
<table id="bettingInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="2">Red / Odds(Payout)</th>
		<th colspan="2">Blue / Odds(Payout)</th>
	</tr>
	<tr>
		<td width="25%" align="center"><?php echo $current_red_fighter; ?></td>
		<td width="25%" align="center"><?php echo $current_red_odds; ?><span id="redPayout"/></td>
		<td width="25%" align="center"><?php echo $current_blue_fighter; ?></td>
		<td width="25%" align="center"><?php echo $current_blue_odds; ?><span id="bluePayout"/></td>
	</tr>
</table>

<table id="bettingTable" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<td colspan="4" align="center" style="padding: 100px">
			<button id="plus_button" onclick="decreaseBet()" style="padding: 15px 50px 15px 50px">-</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="number" id="bet" value=5 style="width:150px;">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button id="minus_button" onclick="increaseBet()" style="padding: 15px 50px 15px 50px">+</button>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input id="bet_red_button" type="button" value="Bet Red" style="padding: 15px 50px 15px 50px" onclick="bet('<?php echo $current_red_fighter; ?>')"/></td>
		<td colspan="2" align="center"><input id="bet_blue_button" type="button" value="Bet Blue" style="padding: 15px 50px 15px 50px" onclick="bet('<?php echo $current_blue_fighter; ?>')"/></td>
	</tr>
</table>

<script type='text/javascript'>
    //This is run onLoad.
	//Take the current time minus the start time of the video and subtract that from the length of video and reload the whole page at the end of that time.
	var length = <?php echo $current_length; ?>;
	var current_time = parseInt((new Date).getTime() / 1000, 10);
	var start_time = <?php echo $current_start_time; ?>;
	var wait_time = ((length - (current_time - start_time)) * 1000) + 2000;
	
	if(wait_time > 0) {
		// Refresh the whole page after the wait_time
		setTimeout(function(){location.reload()}, wait_time);
	
		//If the video type is "Betting" (2) then refresh the user data & fighters data every 1 second.
		if(<?php echo $current_video_type_id; ?> == 1) {
			
			// Betting time so enable bet buttons (if not already bet)
			if("<?php echo $betAmount; ?>" == "") {
				document.getElementById("bet").disabled = false;
				document.getElementById("plus_button").disabled = false;
				document.getElementById("minus_button").disabled = false;
				document.getElementById("bet_red_button").disabled = false;
				document.getElementById("bet_blue_button").disabled = false;
			} else {
				document.getElementById("bet").disabled = true;
				document.getElementById("plus_button").disabled = true;
				document.getElementById("minus_button").disabled = true;
				document.getElementById("bet_red_button").disabled = true;
				document.getElementById("bet_blue_button").disabled = true;
			}

			setInterval(function(){
				loadData();
				current_time = parseInt((new Date).getTime() / 1000, 10);
				time_left = (length - (current_time - start_time));
				document.getElementById("wait_div").innerHTML = "Time left: " + time_left + " seconds";
			}, 1000);
		} else {
			// Not betting time so disable bet buttons
			document.getElementById("bet").disabled = true;
			document.getElementById("plus_button").disabled = true;
			document.getElementById("minus_button").disabled = true;
			document.getElementById("bet_red_button").disabled = true;
			document.getElementById("bet_blue_button").disabled = true;
			
			setInterval(function(){
				current_time = parseInt((new Date).getTime() / 1000, 10);
				time_left = (length - (current_time - start_time));
				document.getElementById("wait_div").innerHTML = "Time left: " + time_left + " seconds";
			}, 1000);
		}
	} else {
		//Error (video paused and I am off sync or end of all videos)
		document.getElementById("wait_div").innerHTML = "Error finding video. Please refresh this page when video has resumed playing.";
		// Not betting time so disable bet buttons
		document.getElementById("bet").disabled = true;
		document.getElementById("plus_button").disabled = true;
		document.getElementById("minus_button").disabled = true;
		document.getElementById("bet_red_button").disabled = true;
		document.getElementById("bet_blue_button").disabled = true;
	}
</script>
</html>