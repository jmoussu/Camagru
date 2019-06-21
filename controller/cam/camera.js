
function file(){
	document.getElementById('inp').onchange = function(e) {
		stop2();
		var img = new Image();
		img.src = URL.createObjectURL(this.files[0]);
		img.onload = draw;
		img.onerror = failed;
	};
	function draw() {
		var canvas = document.getElementById('canvas');
		// canvas.width = this.width;
		// canvas.height = this.height;
		var ctx = canvas.getContext('2d');
		ctx.clearRect(0, 0, 1000, 750);
		var img_h = this.height;
		var img_w = this.width;
		if (img_w > 1000 || img_h > 750)
		{
			var ratio = img_w / img_h;
			if (ratio > (4/3))
			{
				img_h = Math.round(img_h*(1000/img_w));
				img_w = 1000;
			}
			else
			{

				img_w = Math.round(img_w*(750/img_h));
				img_h = 750;
			}
		}
		ctx.drawImage(this, canvas.width / 2 - img_w / 2, canvas.height / 2 - img_h / 2, img_w, img_h);
	}
	function failed() {
		console.error("The provided file couldn't be loaded as an Image media");
		document.getElementById('inp').value = "";
		alert('Merci d\'utiliser un fichier image');
	}
}

function startcam(e){
	if (navigator.mediaDevices.getUserMedia) {
		document.getElementById('canvas').getContext('2d').clearRect(0, 0, 1000, 750);
		document.getElementById('inp').value = "";
		var video = document.querySelector("#videoElement");
		navigator.mediaDevices.getUserMedia({ video: true })
		.then(function (stream) {
			video.srcObject = stream;
		})
		.catch(function (e)
		{
			console.log("Something went wrong no camera!");
		});
	}
}
function stop(e) {
	var video = document.querySelector("#videoElement");
	document.getElementById('inp').value = "";
	document.getElementById('canvas').getContext('2d').clearRect(0, 0, 1000, 750);
	if (video.srcObject == null)
	{
		return;
	}
	var stream = video.srcObject;
	var tracks = stream.getTracks();
	for (var i = 0; i < tracks.length; i++) {
		var track = tracks[i];
		track.stop();
	}
	video.srcObject = null;
}
function stop2(e) {
	var video = document.querySelector("#videoElement");
	document.getElementById('canvas').getContext('2d').clearRect(0, 0, 1000, 750);
	if (video.srcObject == null)
	{
		return;
	}
	var stream = video.srcObject;
	var tracks = stream.getTracks();
	for (var i = 0; i < tracks.length; i++) {
		var track = tracks[i];
		track.stop();
	}
	video.srcObject = null;
}
