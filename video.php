<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<?php
ob_start();
include_once("functions/loadCurrentVideo.php");
ob_end_clean();
?>

<script type='text/javascript'>
	function playCurrentVideo() {
		$.ajax({
			type: 'GET',
			url: 'functions/loadCurrentVideo.php',
			success: loadNextVideo,
			error: drawError
		 });
		return false;
	};
	function videoEnded() {
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
		vid.autoplay = true;
		
		$.ajax({
			type: 'GET',
			url: 'functions/updateCurrentVideoStartTime.php'
		 });
		 
		return true;
	}
</script>

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

<video id="myVideo" height="80%" onended="videoEnded()" style="margin:0 auto; width:100%;" controls>
	<source src="videos/s1.mp4" type="video/mp4">
	Your browser does not support HTML5 video.
</video>

</br></br>

<div align="center">
	<button onclick="playCurrentVideo()">Play "current" video</button>
</div>

<script text="text/javascript">
	setInterval(function(){
		$.ajax({
			type: 'GET',
			url: 'functions/loadCurrentVideo.php',
			success: refreshFighterInfo,
			error: drawError
		 });
	}, 1000);
	
	function refreshFighterInfo() {
		jQuery('#currentFighterInfo').load(document.URL +  ' #currentFighterInfo');
		return false;
	}
</script>