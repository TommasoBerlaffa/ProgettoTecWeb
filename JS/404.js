var code='<div id="choose">'+
		'<button type="button" class="btn404" value="Moe Lester">Moe Lester<br>Junior Javascript Developer.</button>'+
		'<button type="button" class="btn404" value="Soshita Nakagata">Soshita Nakagata<br>Software quality assurance (QA) engineer.</button>'+
		'<button type="button" class="btn404" value="Chris P. Bacon">Chris P. Bacon<br>Full Sandwich Developer.</button>'+
		'<button type="button" class="btn404" value="Ana L. Joy">Ana L. Joy<br>Back-End developer.</button>'+
		'<p>We are terribly sorry of this inconvinience. One of our employees have done another small opsie.<br>Please, choose the name of the employee you think might be responsible for this.<p>'+
	'</div>';


window.onload = function(){
document.getElementById("main").innerHTML=document.getElementById("main").innerHTML+code;
document.getElementById("choose").addEventListener('click',(evnt)=>{
	if(evnt.target.nodeName === 'BUTTON')
		thanks(evnt.target.value);
	return;
})
};


function thanks(n){
	var div=document.getElementById('choose');
	if(!div)
		return;
	div.innerHTML='<p>Thank you for your feedback!<br>You chose ' + n + '.<br>We are going to fire him/her immediatelly!</p>';
	
	
}


