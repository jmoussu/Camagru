function infini_scroll()
{
	// var warp = document.getElementById('imgscontainer');
	var contentHeight = document.body.scrollHeight; // get page content height
	var yOffset = window.pageYOffset; // get the vertical scroll position
	var y = yOffset + window.innerHeight;
	if (y >= contentHeight)
	{
		getData();
	}
	// var status = document.getElementById('status');
	// status.innerHTML = contentHeight+" | "+y; 
}
window.onscroll = infini_scroll;

var start = 15;
var limit = 6;

function getData(){
	var ajax = new XMLHttpRequest();
	ajax.onload = () => {
		if (ajax.status == 200)
		{
			start += limit;
			document.getElementById("imgscontainer").innerHTML += ajax.response;
			
		}
	}
	ajax.open("GET",'controller/data.php?start='+start+'&limit='+limit, true);
	ajax.setRequestHeader('Content-Type', 'text');
	ajax.send();

}
