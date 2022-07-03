function fill(){
	var div=document.getElementById('choose');
	if(!div)
		return;
	
	var cont=document.createElement('div');
	cont.id='container404';
	cont.classList.add("choose");
	div.appendChild(cont);
	
	var bt=document.createElement('button');
	bt.innerHTML="Moe Lester<br>Junior Javascript Developer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Moe Lester")');
	cont.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Soshita Nakagata<br>Software quality assurance (QA) engineer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Soshita Nakagata")');
	cont.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Chris P. Bacon<br>Full Sandwich Developer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Chris P. Bacon")');
	cont.appendChild(bt);
	
	bt=document.createElement('button');
	bt.innerHTML="Ana L. Joy<br>Back-End developer.";
	bt.classList.add("btn404");
	bt.setAttribute('type','button');
	bt.setAttribute('onclick','thanks("Ana L. Joy")');
	cont.appendChild(bt);
	
	bt=document.createElement('div');
	bt.innerHTML="<p>We are terribly sorry of this inconvinience. One of our employees have done another small opsie.<br>Please, choose the name of the employee you think might be responsible for this.<p>";
	div.appendChild(bt);
	
}

function thanks(n){
	var div=document.getElementById('choose');
	if(!div)
		return;
	div.innerHTML='<p>Thank you for your feedback!<br>You chose ' + n + '.<br>We are going to fire him/her immediatelly!</p>';
	
	
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


