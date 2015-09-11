<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>

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

<video id="myVideo" height="80%" onended="videoEnded()" controls>
	<source src="videos/s1.mp4" type="video/mp4">
	Your browser does not support HTML5 video.
</video>

</br></br>

<button onclick="playCurrentVideo()">Play "current" video</button>