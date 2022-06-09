var timer;
function delay(){
   clearTimeout(timer);
   timer=setTimeout(AjaxTagSearch(mycallback),250);
}

function updateUserTagList(response){
	var div=document.getElementById('selectedTagsList');
	var list=JSON.parse(response);
	var keys = Object.keys(list);
	div.innerHTML="<p>Selected Tags: ("+ keys.length +" / 20)</p>";
	for(i=0; i<keys.length; i++){
		var bt=document.createElement('button');
		bt.innerHTML=keys[i];
		bt.value=list[keys[i]];
		bt.setAttribute('type','button');
		bt.setAttribute('onclick',"AjaxRemoveTag('"+keys[i]+"')");
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
	var data = {'Sub':true,'Name':name};
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

function AjaxAddTag(name,val){
	const xhttp = new XMLHttpRequest();
	var data = {'Add':true,'Name':name,'Value':val};
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
	var keys = Object.keys(list);
	for (i=0; i<keys.length;i++){
		var bt=document.createElement('button');
		bt.innerHTML=keys[i];
		bt.value=list[keys[i]];
		bt.setAttribute('type','button');
		bt.setAttribute('onclick',"AjaxAddTag('"+keys[i]+"','"+list[keys[i]]+"')");
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


