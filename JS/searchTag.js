var timer;
function delay(){
   clearTimeout(timer);
   timer=setTimeout(() => AjaxTagSearch(tagList),200);
}

function updateUserTagList(response){
	var div=document.getElementById('selectedTagsList');
	if(!div)
		return;
	var list=JSON.parse(response);
	if(list==null)
		return;
	var keys = Object.keys(list);
	div.innerHTML='<p class="SelectedTags">Selected Tags: ('+ keys.length +')</p>';
	if(!keys)
		div.innerHTML+='<p class="hiddenHelp">Empty</p>';
	var tmp=document.getElementById('FSkill');
	if(tmp)
		tmp.innerHTML ='';
	for(i=0; i<keys.length; i++){
		var bt=document.createElement('button');
		bt.innerHTML=keys[i];
		bt.value=list[keys[i]];
		bt.classList.add("btnTag");
		bt.setAttribute('type','button');
		bt.addEventListener('click', AjaxRemoveTag);
		div.appendChild(bt);
		if(tmp)
			tmp.innerHTML += '<li>' + keys[i] + '</li>';
	}
}

function AjaxUpdate(callback){
	const xhttp = new XMLHttpRequest();
	var data = {'Update':true};
	xhttp.open('POST', '../PHP/Modules/Util.php', true);
	
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

window.addEventListener('load',function() {
	AjaxUpdate(updateUserTagList);
})

function AjaxRemoveTag(obj){
	const xhttp = new XMLHttpRequest();
	var data = {'Sub':true,'Name':obj.target.innerHTML};
	xhttp.open('POST', '../PHP/Modules/Util.php', true);
	
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

function AjaxAddTag(obj){
	const xhttp = new XMLHttpRequest();
	var data = {'Add':true,'Name':obj.target.innerHTML,'Value':obj.target.value};
	xhttp.open('POST', '../PHP/Modules/Util.php', true);
	
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

function tagList(response){
	var div=document.getElementById('tagsList');
	if(!div)
		return;
	div.innerHTML='<p class="chooseTags">Available Tags :</p>';
	var list=JSON.parse(response);
	if(list==null){
		div.innerHTML+='<p class="hiddenHelp">Empty</p>';
		return;
	}
	var keys = Object.keys(list);
	for (i=0; i<keys.length;i++){
		var bt=document.createElement('button');
		bt.innerHTML=keys[i];
		bt.value=list[keys[i]];
		bt.classList.add('btnTag');
		bt.setAttribute('type','button');
		bt.addEventListener('click', AjaxAddTag);
		div.appendChild(bt);
	}
}

function AjaxTagSearch(callback){
	var tag=document.getElementById('searchTag').value;
	if(tag.length<2){
		var div=document.getElementById('tagsList');
		div.innerHTML='<p class="chooseTags">Available Tags :</p><p class="hiddenHelp">Empty</p>';
		return;
	}
	const xhttp = new XMLHttpRequest();
	var data = {'Tag':tag};
	
	xhttp.open('POST', '../PHP/Modules/AjaxTagSearch.php', true);
	
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

if(document.getElementById('searchTag')){
	document.getElementById('searchTag').addEventListener('keyup', function(evt){delay();});
}


