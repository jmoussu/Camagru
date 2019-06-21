var start = 15;
var limit = 6;
if (window.location.pathname.includes("/cam.php") && window.location.pathname.includes("?") == false){
	window.onscroll = infini_scroll2;
}
else{
	window.onscroll = infini_scroll;
}
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
	// var status = contentHeight+" | "+y;
	// console.log(status);
}

function getData(){
	var ajax = new XMLHttpRequest();
	var user = document.getElementsByClassName("title")[0].id;
	ajax.onload = () => {
		if (ajax.status == 200)
		{
			start += limit;
			document.getElementById("imgscontainer").innerHTML += ajax.response;
			
		}
	}
	ajax.open("GET",'../controller/data_user.php?start='+start+'&limit='+limit+'&user='+user, true);
	ajax.setRequestHeader('Content-Type', 'text');
	ajax.send();

}

// function infini_scroll2()
// {
// 	// var warp = document.getElementById('imgscontainer');
// 	var contentHeight = document.body.scrollHeight; // get page content height
// 	var yOffset = window.pageYOffset; // get the vertical scroll position
// 	var y = yOffset + window.innerHeight;
// 	if (y >= contentHeight)
// 	{
// 		getData2();
// 	}
// 	// var status = contentHeight+" | "+y;
// 	// console.log(status);
// }

// function getData2(){
// 	var ajax = new XMLHttpRequest();
// 	var user = document.getElementsByClassName("title")[0].id;
// 	ajax.onload = () => {
// 		if (ajax.status == 200)
// 		{
// 			start += limit;
// 			document.getElementById("imgscontainer").innerHTML += ajax.response;
			
// 		}
// 	}
// 	ajax.open("GET",'../controller/data_user.php?start2='+start+'&limit2='+limit+'&user='+user, true);
// 	ajax.setRequestHeader('Content-Type', 'text');
// 	ajax.send();

// }
function sup(element){
	r = confirm("Voulez vous vraiment supprimer cette image ?");
	id = element.id.substring(5); // croix+id to id
	if (r == true){
		var ajax = new XMLHttpRequest();
		ajax.onload = () => {
			if (ajax.status == 200)
			{
				//vider avant ? non hein 
				start = 0;
				limit = 0;
				document.getElementById("imgscontainer").innerHTML = '';
				document.getElementById("imgscontainer").innerHTML = ajax.response;
				start = 15;
				limit = 6;
			}
		}
		ajax.open("GET",'../controller/data_user.php?supId='+id, true);
		ajax.setRequestHeader('Content-Type', 'text');
		ajax.send();
	}
}
