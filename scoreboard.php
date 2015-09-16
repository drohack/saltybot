<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<?php
ob_start();
include_once("functions/loadCurrentVideo.php");
ob_end_clean();
?>

<table id="currentFighterInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="4">CURRENT/NEXT FIGHT</th>
	</tr>
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
</br>
<table id="lastFighterInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="4">LAST FIGHT</th>
	</tr>
	<tr>
		<th colspan="2"><div id="last_red_header">Red / Odds</div></th>
		<th colspan="2"><div id="last_blue_header">Blue / Odds</div></th>
	</tr>
	<tr>
		<td width="25%" align="center"><?php echo $last_red_fighter; ?></td>
		<td width="25%" align="center"><?php echo $last_red_odds; ?></td>
		<td width="25%" align="center"><?php echo $last_blue_fighter; ?></td>
		<td width="25%" align="center"><?php echo $last_blue_odds; ?></td>
	</tr>
</table>
</br>
<div id="usersDataDiv"/>

<script type='text/javascript'>
	setInterval(function(){
		loadUsersData();
	}, 1000);
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
		return false;
	}
</script>
</html>