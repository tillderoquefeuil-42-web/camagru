(function() {

	var streaming	= false,
		video       = document.querySelector('#video'),
		cover       = document.querySelector('#cover'),
		canvas      = document.querySelector('#canvas'),
		photo       = document.querySelector('#photo'),
		start		= document.querySelector('#start'),
		filter      = document.querySelector('#pic_filter'),
		width		= 320,
		height		= 0;

	navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
						   navigator.msGetUserMedia);

	navigator.getMedia(
		{
		video: true,
				audio: false
				},
		function(stream) {
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}
			video.play();
		},
		function(err) {
			console.log("An error occured! " + err);
		}
		);

	video.addEventListener('canplay', function(ev){
			if (!streaming) {
				height = video.videoHeight / (video.videoWidth/width);
				video.setAttribute('width', width);
				video.setAttribute('height', height);
				canvas.setAttribute('width', width);
				canvas.setAttribute('height', height);
				streaming = true;
			}
		}, false);

	function takepicture() {
		canvas.width = width;
		canvas.height = height;
		canvas.getContext('2d').drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL('image/png');
		start.setAttribute('value', data);
//		photo.setAttribute('src', data);
	}

	start.addEventListener('click', function(ev){
			takepicture();
		}, false);
})();

function ft_filter() {
	
	var start		= document.querySelector('#start'),
        pic_filter	= document.querySelector('#pic_filter'),
		upload		= document.querySelector('#upload_img');

	var filtre = document.forms["form_filter"]["filter"].value;
	if (filtre != null && filtre != "")
	{
		var pic = new Image();
		pic.src = filtre;
		var parent = document.getElementById("form_cam");
		var div = document.getElementById("img");
		if (div)
			parent.removeChild(div);
		div = document.createElement("DIV");
		div.setAttribute('id', 'img');
		document.getElementById("form_cam").appendChild(div);
		document.getElementById("img").appendChild(pic);
		pic_filter.setAttribute('value', filtre);
		start.removeAttribute("hidden");
		upload.removeAttribute("hidden");
	}
}

function ft_share() {
	alert("Votre photo a ete publiee!");
}