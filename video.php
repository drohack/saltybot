<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<?php
ob_start();
include_once("functions/loadCurrentVideo.php");
ob_end_clean();
?>

<script type='text/javascript'>
	var fighterIntervalId = null;
	
	function playCurrentVideo() {
		//Stop refreshing the fighter info
		clearInterval(fighterIntervalId);
		fighterIntervalId = null;
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
		clearInterval(fighterIntervalId);
		fighterIntervalId = null;
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
			success: startFighterInterval,
			error: drawError
		});
		 
		return false;
	}
	function startFighterInterval() {
		//If video_type_id == 1 (betting) then update the odds every 10 seconds
		if(<?php echo $current_video_type_id; ?> == 1) {
			fighterIntervalId = setInterval(function(){
				//Update the odds
				$.ajax({
					type: 'GET',
					url: 'functions/updateOdds.php',
					error: drawError
				});
			}, 10000);
		}
	}
</script>

<video id="myVideo" height="80%" onended="videoEnded()" style="margin:0 auto; width:100%;" controls>
	<source src="videos/s1.mp4" type="video/mp4">
	Your browser does not support HTML5 video.
</video>

</br></br>

<div align="center">
	<button onclick="playCurrentVideo()">Play "current" video</button>
</div>
</html>