var timer;
function delay(){
   clearTimeout(timer);
   timer=setTimeout(AjaxTagSearch(mycallback),250);
}

function updateUserTagList(response){
	var div=document.getElementById('selectedTagsList');
	div.innerHTML="<h4>Selected Tags:</h4>";
	var list=JSON.parse(response);
	for(const tagname in list){
		var bt=document.createElement('button');
		bt.innerHTML=tagname;
		bt.value=tagname;
		bt.setAttribute('type','button');
		bt.setAttribute('onclick',"AjaxRemoveTag('"+tagname+"')");
		div.appendChild(bt);
	}
}

function AjaxUpdate(callback){
	const xhttp = new XMLHttpRequest();
	var data = {'Update':true};
	xhttp.open('POST', '../PHP/Util.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			callback(xhttp.responseText);
			return;
		}
		return;
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	return;
}

window.onload=function(){
	AjaxUpdate(updateUserTagList);
}

function AjaxRemoveTag(name){
	const xhttp = new XMLHttpRequest();
	var data = {'Sub':true,Name:name};
	xhttp.open('POST', '../PHP/Util.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			AjaxUpdate(updateUserTagList);
			return;
		}
		return;
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	return;
}

function AjaxAddTag(name){
	const xhttp = new XMLHttpRequest();
	var data = {'Add':true,Name:name};
	xhttp.open('POST', '../PHP/Util.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			AjaxUpdate(updateUserTagList);
			return;
		}
		return;
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	return;
}

function mycallback(response){
	var div=document.getElementById('tagsList');
	div.innerHTML="";
	var list=JSON.parse(response);
	for (i=0; i<list.length;i++){
		var bt=document.createElement('button');
		bt.innerHTML=list[i].Name;
		bt.value=list[i].Name;
		bt.setAttribute('type','button');
		bt.setAttribute('onclick',"AjaxAddTag('"+list[i].Name+"')");
		div.appendChild(bt);
	}
}

function AjaxTagSearch(callback){
	var tag=document.getElementById('searchTag').value;
	if(tag.length<=2){
		var div=document.getElementById('tagsList');
		div.innerHTML="";
		return;
	}
	const xhttp = new XMLHttpRequest();
	var data = {'Tag':tag};
	
	xhttp.open('POST', '../PHP/AjaxTagSearch.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			callback(xhttp.responseText);
			return;
		}
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


