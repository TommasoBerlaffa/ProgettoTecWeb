var timer;
function delay(){
   clearTimeout(timer);
   timer=setTimeout(AjaxTagSearch(mycallback),250);
}
//function delay(fn, ms) {
//  let timer = 0
//  return function(...args) {
//    clearTimeout(timer)
//    timer = setTimeout(fn.bind(this, ...args), ms || 0)
//  }
//}

function mycallback(response){
	var div=document.getElementById('tagsList');
	div.innerHTML="";
	var list=JSON.parse(response);
	for (i=0; i<list.length;i++){
		var bt=document.createElement('button');
		bt.innerHTML=list[i][1];
		bt.value=list[i][0];
		div.appendChild(bt);
	}
	//document.appendChild(div);
	//document.getElementById('helpLabelsearchTag').innerText=typeof(list);
	//document.getElementById('helpLabelsearchTag').innerText='';
	//if(response === 'OK'){
	//	document.getElementById('helpLabelsearchTag').innerText='Errore Banana';
	//}
	//else
	//	document.getElementById('helpLabelsearchTag').innerText='No match';
}

function AjaxTagSearch(callback){
	var tag=document.getElementById('searchTag').value;
	if(tag.length<=2){
		var div=document.getElementById('tagsList');
		div.innerHTML="";
		return;
	}
	const xhttp = new XMLHttpRequest();
	var data = {Tag:tag};
	
	xhttp.open('POST', '../PHP/AjaxTagSearch.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			document.getElementById('helpLabelsearchTag').innerText='';
			callback(xhttp.responseText);
			return;
		}
		//else if (xhttp.readyState === 4 && xhttp.status === 429){
		//	document.getElementById('helpLabelsearchTag').innerText='Too many requests, retry in 5 secs.';
		//	return;
		//}
		return;
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	return;
}

document.getElementById('searchTag').onkeyup = function (e, callback) {
	e = e || window.event;
	delay();
}


