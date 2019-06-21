var filter = "";
var img = new Image();
window.onscroll = infini_scroll2;
var start = 15;
var limit = 6;
var size = 300;
var ratio = 4/3;
var height = 200;
var topp = 60;
var left = 60;
var t_x = 0;
var t_y = 0;
var el = window;
var eventName = 'keypress';
if (el.addEventListener) {
	el.addEventListener('keydown', keyListener, false); 
}
else if (el.attachEvent){ //IE COMPATIBILITY
	el.attachEvent('on'+eventName, keyListener);
}

function keyListener(event){ 
	event = event || modal.event;
	var key = event.key || event.which || event.keyCode;
	//
	var prev = document.getElementById('filterPreviw');
	if (filter == 'no_filter' || filter == "")
	{
		prev.innerHTML = "";
		size = 300;
		t_x = 0;
		t_y = 0;
		return;
	}
	img.src = "../resources/filter/"+filter+".png";
	ratio = img.width/img.height;
	var sizemax= 600;
	var sizemin = 100;
	var t_xmin = -400;
	var t_xmax = 400;
	var t_ymin = -350;
	var t_ymax = 350;
	// img.
	if (key == '+' && size < sizemax)
	{
		size += 10;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}
	if (key == '-' && size > sizemin)
	{
		size -= 10;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}
	if (key == 'ArrowUp' && t_y > t_ymin)
	{
		event.preventDefault();
		t_y -= 5;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}
	if (key == 'ArrowDown' && t_y < t_ymax)
	{
		event.preventDefault();
		t_y += 5;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}
	if (key == 'ArrowLeft' && t_x > t_xmin)
	{
		event.preventDefault();
		t_x -= 5;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}
	if (key == 'ArrowRight' && t_x < t_xmax)
	{
		event.preventDefault();
		t_x += 5;
		height = size*(1/ratio);
		topp = ((750/2) - (height/2)) + t_y;
		left = ((1000/2) - (size/2)) + t_x;
		prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
	}

}

function switch_filter(new_filter)
{
	filter = new_filter;
	var prev = document.getElementById('filterPreviw');
	if (new_filter == 'no_filter')
	{
		prev.innerHTML = "";
		size = 300;
		return;
	}
	img.src = "../resources/filter/"+filter+".png";
	ratio = img.width/img.height;
	height = size*(1/ratio);
	topp = 750/2 - height/2;
	left = 1000/2 - size/2;
	t_x = 0;
	t_y = 0;
	prev.innerHTML = "<img src='"+img.src+"'style='position:absolute; width:"+size+"; top:"+topp+"px; left:"+left+"px'></img>";
}

function capture() { 
	// if cam not allumed =D
	// varaible pour pas spam les photo;
	// canvas.width = 1000;
	// canvas.height = 750;
	var video = document.querySelector("#videoElement");
	var prev = document.getElementById('filterPreviw');
	canvas.getContext('2d').drawImage(video, 0, 0, 1000, 750);

	var pix = canvas.getContext('2d').getImageData(500, 375, 1, 1).data
	// console.log(document.getElementById('image-file').files[0].name);
	if ((video.srcObject == null && document.getElementById('inp').value == "" ) || pix == '0,0,0,0')
	{
		alert("Merci d'allumer la camera ! Ou d'ajouter une image");
		canvas.getContext('2d').clearRect(0, 0, 1000, 750);
		document.getElementById('inp').value = "";
		return;
	}
	if (filter == "")
	{
		alert("Merci de selectionner un filter (Restriction du Sujet)");
		if (document.getElementById('inp').value != "")
		{
			return;
		}
		canvas.getContext('2d').clearRect(0, 0, 1000, 750);
		document.getElementById('inp').value = "";
		return;
	}
	var ajax = new XMLHttpRequest();
	var video = document.querySelector("#videoElement");
	var canvasData = canvas.toDataURL("image/png"); // ici = blanc apres = noir
	if (video.srcObject == null && document.getElementById('inp').value != "")
	{
		ajax.open("POST",'../controller/cam/file_save.php', false);
		ajax.setRequestHeader('Content-Type', 'application/upload');
		ajax.send(canvasData);
	}
	else{
		ajax.open("POST",'../controller/cam/pic_save.php', false);
		ajax.setRequestHeader('Content-Type', 'application/upload');
		ajax.send(canvasData);
	}
	if (filter != "no_filter"){
		ajax.open("POST",'../controller/cam/add_filter.php', false);
		ajax.setRequestHeader("content-type", "application/x-www-form-urlencoded");
		ajax.send('filter='+filter+'&new_width='+size+'&t_x='+t_x+'&t_y='+t_y); // size SIZE size Size size SIZE // // //
	}
	filter = "";
	reload_images();
	prev.innerHTML = "";
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

function reload_images()
{
	var ajax = new XMLHttpRequest();
	start = 0;
	limit = 15;
	ajax.onload = () => {
		if (ajax.status == 200)
		{
			start += limit;
			document.getElementById("imgscontainer").innerHTML = '';
			document.getElementById("imgscontainer").innerHTML = ajax.response;
			var preview = document.getElementsByClassName('img_previw')[0];
			preview.getElementsByTagName('img')[3].click(); // open it
			// wait and change variabble for take picture again
		}
	}
	ajax.open("GET",'../controller/cam/data_cam.php?start='+start+'&limit='+limit, true);
	ajax.setRequestHeader('Content-Type', 'text');
	ajax.send();
}

function infini_scroll2()
{
	// var warp = document.getElementById('imgscontainer');
	var contentHeight = document.body.scrollHeight; // get page content height
	var yOffset = window.pageYOffset; // get the vertical scroll position
	var y = yOffset + window.innerHeight;
	if (y >= contentHeight)
	{
		getData2();
	}
	// var status = contentHeight+" | "+y;
	// console.log(status);
}
function getData2(){
	var ajax = new XMLHttpRequest();
	var user = document.getElementsByClassName("title")[0].id;
	ajax.onload = () => {
		if (ajax.status == 200)
		{
			start += limit;
			document.getElementById("imgscontainer").innerHTML += ajax.response;
			
		}
	}
	ajax.open("GET",'../controller/data_user.php?start2='+start+'&limit2='+limit+'&user='+user, true);
	ajax.setRequestHeader('Content-Type', 'text');
	ajax.send();

}
