function fill(){
	var div=document.getElementById('choose');
	if(!div)
		return;
	
	var bt=document.createElement('button');
	bt.innerHTML="Moe Lester - Junior Java Developer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Moe Lester")');
	div.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Soshita Nakagata - Software quality assurance (QA) engineer";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Soshita Nakagata")');
	div.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Chris P. Bacon - Responsible of Breakroom. (He once unplugged the server to cook a toast)";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Chris P. Bacon")');
	div.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Ana L. Joy - Back-End developer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Ana L. Joy")');
	div.appendChild(bt);
	
	bt=document.createElement('paragaph');
	bt.innerHTML="We are terribly sorry of this inconvinience. One of our employees have done another small opsie. Please, choose the name of the employee you think might be responsible for this.";
	div.appendChild(bt);
	
}

function thanks(n){
	var div=document.getElementById('choose');
	if(!div)
		return;
	div.innerHTML='<p id="404-thanks">Thank you for your feedback! You chose ' + n + '. We are going to fire him/her immediatelly!</p>';
	
	
}

function updateUserTagList(response){
	var div=document.getElementById('selectedTagsList');
	if(!div)
		return;
	var list=JSON.parse(response);
	if(list==null)
		return;
	var keys = Object.keys(list);
	div.innerHTML="<p>Selected Tags: ("+ keys.length +" / 20)</p>";
	var tmp=document.getElementById('FSkill');
	if(tmp)
		tmp.innerHTML ='';
	for(i=0; i<keys.length; i++){
		var bt=document.createElement('button');
		bt.innerHTML=keys[i];
		bt.value=list[keys[i]];
		bt.classList.add("btnTag");
		bt.setAttribute('type','button');
		bt.setAttribute('onclick',"AjaxRemoveTag('"+keys[i]+"')");
		div.appendChild(bt);
		if(tmp)
			tmp.innerHTML += '<li>' + keys[i] + '</li>';
	}
}

window.onload = function(){
	fill();
}


