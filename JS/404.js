window.onload = function(){
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


