<script>
	function playCurrentVideo() {
		getRequest(
			'loadCurrentVideo.php', // URL for the PHP file
			loadNextVideo,  // handle successful request
			drawError    // handle error
		);
		return false;
	};
	function videoEnded() {
		getRequest(
			'loadNextVideo.php', // URL for the PHP file
			loadNextVideo,  // handle successful request
			drawError    // handle error
		);
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

<video id="myVideo" height="80%" onended="videoEnded()" controls>
	<source src="videos/s1.mp4" type="video/mp4">
	Your browser does not support HTML5 video.
</video>

</br></br>

<button onclick="playCurrentVideo()">Play "current" video</button>