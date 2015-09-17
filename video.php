<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<?php
ob_start();
include_once("functions/loadCurrentVideo.php");
ob_end_clean();
?>

<script type='text/javascript'>
	//var fighterIntervalId = null;
	
	function playCurrentVideo() {
		//Stop refreshing the fighter info
		//clearInterval(fighterIntervalId);
		$.ajax({
			type: 'GET',
			url: 'functions/loadCurrentVideo.php',
			success: loadNextVideo,
			error: drawError
		 });
		return false;
	};
	function videoEnded() {
		//Stop refreshing the fighter info
		//clearInterval(fighterIntervalId);
		$.ajax({
			type: 'GET',
			url: 'functions/loadNextVideo.php',
			success: loadNextVideo,
			error: drawError
		 });
		return false;
	};
	// handles drawing an error message
	function drawError() {
		alert('Bummer: there was an error!');
	}
	// handles the response, adds the html
	function loadNextVideo(responseText) {
		var vid = document.getElementById("myVideo");
		vid.src = "videos/" + responseText;
		vid.play();

		$.ajax({
			type: 'GET',
			url: 'functions/updateCurrentVideoStartTime.php',
			error: drawError
		});
		 
		return false;
	}
</script>

<!--<table id="currentFighterInfo" border='1' style='width:100%;border: 1px solid black;border-collapse: collapse;padding: 5px;'>
	<tr>
		<th colspan="4">CURRENT/NEXT FIGHT</th>
	</tr>
	<tr>
		<th colspan="2">Red / Odds</th>
		<th colspan="2">Blue / Odds</th>
	</tr>
	<tr>
		<td width="25%" align="center"><?php echo $current_red_fighter; ?></td>
		<td width="25%" align="center"><?php echo number_format($current_red_odds,2); ?></td>
		<td width="25%" align="center"><?php echo $current_blue_fighter; ?></td>
		<td width="25%" align="center"><?php echo number_format($current_blue_odds,2); ?></td>
	</tr>
</table>

</br>
-->
<video id="myVideo" height="80%" onended="videoEnded()" style="margin:0 auto; width:100%;" controls>
	<source src="videos/s1.mp4" type="video/mp4">
	Your browser does not support HTML5 video.
</video>

</br></br>

<div align="center">
	<button onclick="playCurrentVideo()">Play "current" video</button>
</div>

<script text="text/javascript">
	function startFighterInterval() {
		//If video_type_id == 1 (betting) then update the fighter info every 10 seconds
		if(<?php echo $current_video_type_id; ?> == 1) {
			fighterIntervalId = setInterval(function(){
				//Update the odds
				$.ajax({
					type: 'GET',
					url: 'functions/updateOdds.php',
					error: drawError
				});
			}, 10000);
		} else {
			//loadData();
		}
	}
	/* function loadData() {
		$.ajax({
			type: 'GET',
			url: 'functions/loadCurrentVideo.php',
			success: refreshFighterInfo,
			error: drawError
		});
	}
	function refreshFighterInfo(x) {
		jQuery('#currentFighterInfo').load(document.URL +  ' #currentFighterInfo');
		return false;
	} */
	
	startFighterInterval();
</script>
</html>