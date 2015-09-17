<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<?php
ob_start();
include_once("functions/loadCurrentVideo.php");
ob_end_clean();
?>

<table id="currentFighterInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="5">CURRENT/NEXT FIGHT</th>
	</tr>
	<tr>
		<th colspan="5">
			<div style="width:50%; float:left;">
				<font color="red">Red</font> / Odds(Payout)
			</div>
			<div style="width:50%; float:right;">
				<font color="blue">Blue</font> / Odds(Payout)
			</div>
		</th>
	</tr>
	<tr>
		<td width="20%" align="center"><?php echo $current_red_fighter; ?></td>
		<td width="20%" align="center"><?php echo (number_format($current_red_odds,2)+0); ?></td>
		<td width="20%" align="center"><?php if($current_red_odds > $current_blue_odds){echo '<font color="red">' . (number_format($current_red_odds/$current_blue_odds,2)+0) . '</font>:<font color="blue">1';}else {echo '<font color="red">1</font>:<font color="blue">' . (number_format($current_blue_odds/$current_red_odds,2)+0) . '</font>';} ?></td>
		<td width="20%" align="center"><?php echo $current_blue_fighter; ?></td>
		<td width="20%" align="center"><?php echo (number_format($current_blue_odds,2)+0); ?></td>
	</tr>
</table>
</br>
<table id="lastFighterInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="5">LAST FIGHT</th>
	</tr>
	<tr>
		<th colspan="5">
			<div style="width:50%; float:left;">
				<font color="red">Red</font> / Odds(Payout)
			</div>
			<div style="width:50%; float:right;">
				<font color="blue">Blue</font> / Odds(Payout)
			</div>
		</th>
	</tr>
	<tr>
		<td width="20%" align="center"><?php echo $last_red_fighter; ?></td>
		<td width="20%" align="center"><?php if($last_red_odds != ""){echo (number_format($last_red_odds,2)+0);} ?></td>
		<td width="20%" align="center"><?php if($last_red_odds != ""){if($last_red_odds > $last_blue_odds){echo '<font color="red">' . (number_format($last_red_odds/$last_blue_odds,2)+0) . '</font>:<font color="blue">1';}else {echo '<font color="red">1</font>:<font color="blue">' . (number_format($last_blue_odds/$last_red_odds,2)+0) . '</font>';}} ?></td>
		<td width="20%" align="center"><?php echo $last_blue_fighter; ?></td>
		<td width="20%" align="center"><?php if($last_red_odds != ""){echo (number_format($last_blue_odds,2)+0);} ?></td>
	</tr>
</table>
</br>
<div id="usersDataDiv"/>

<script type='text/javascript'>
	function start() {
		setTimeout(function(){
			loadUsersData();
		}, 1000);
	}
	function loadUsersData() {
		$.ajax({
			type: 'GET',
			url: 'functions/loadUsersData.php',
			success: populateUsersDataDiv,
			error: drawError,
			dataType: "text"
		 });
		 $.ajax({
			type: 'GET',
			url: 'functions/loadCurrentVideo.php',
			success: refreshFighterInfo,
			error: drawError
		 });
		return false;
	};
	// handles drawing an error message
	function drawError() {
		alert('Bummer: there was an error!');
	}
	// handles the response, adds the html
	function populateUsersDataDiv(usersData) {
		document.getElementById("usersDataDiv").innerHTML = usersData;
		return false;
	}
	function refreshFighterInfo() {
		jQuery('#currentFighterInfo').load(document.URL +  ' #currentFighterInfo');
		jQuery('#lastFighterInfo').load(document.URL +  ' #lastFighterInfo', function(){
			var last_winner = "<?php echo $last_winner ?>";
			var last_red_fighter = "<?php echo $last_red_fighter ?>";
			var last_blue_fighter = "<?php echo $last_blue_fighter ?>";
			//alert("test: " + last_winner + ":" + last_red_fighter);
			if(last_winner != "" && last_red_fighter != "" && last_winner == last_red_fighter) {
				document.getElementById("last_red_header").innerHTML = "Red / Odds - WINNER";
			} else if(last_winner != "" && last_blue_fighter != "" && last_winner == last_blue_fighter) {
				document.getElementById("last_blue_header").innerHTML = "Blue / Odds - WINNER";
			}
		});
		start();
		return false;
	}
	
	start();
</script>
</html>