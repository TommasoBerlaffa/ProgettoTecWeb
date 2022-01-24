
function onLoad() {
    document.getElementById("Form_2").style.display='none';
    document.getElementById("Form_3").style.display='none';
    document.getElementById("Form_4").style.display='none';
}


function Ajax_Taken(content,callback) {
	const xhttp = new XMLHttpRequest();
	var data = {};
	if(content==='Username')
		data = {Username:document.getElementById('Username').value};
	else if(content==='Email')
		data = {Email:document.getElementById('Email').value};
	else
		return;
	
	xhttp.open('POST', '../PHP/AjaxSignUp.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			if(callback(content, xhttp.responseText))
				return true;
			return false;			
		}
		else if (xhttp.readyState === 4 && xhttp.status === 429){
			document.getElementById(content + 'Taken').innerText='Too many requests, retry in 5 secs.';
			return false;
		}
		return false;
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	return true;
	
}

function mycallback(content, response){
	if(response === 'OK'){
		document.getElementById(content + 'Taken').innerText='';
		return true;
	}
	else
		document.getElementById(content + 'Taken').innerText=response;
	return false;
}



function passwordSecurity() {
	if(document.getElementById("Password").value==''){
		document.getElementById('Security').innerHTML = '';
		return false;
	}
	let regexpPwd = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})');
	if(regexpPwd.test(document.getElementById("Password").value)){
		document.getElementById('Security').style.color = 'green';
		document.getElementById('Security').innerHTML = 'Strong Password';
		if(passwordMatch())
			return true;
		else
			return false;
	}
	else {
		document.getElementById('Security').style.color = 'red';
		document.getElementById('Security').innerHTML = 'Weak Password';
		return false;
	}
}

function passwordMatch() {
	if(document.getElementById('Password').value =='' || document.getElementById('Repeat-Password').value ==''){
		document.getElementById('Match').innerHTML = '';
		return false;
	}
	if (document.getElementById('Password').value == document.getElementById('Repeat-Password').value) {
		document.getElementById('Match').style.color = 'green';
		document.getElementById('Match').innerHTML = 'matching';
		return true;
	} else {
		document.getElementById('Match').style.color = 'red';
		document.getElementById('Match').innerHTML = 'not matching';
		return false;
	}
}


function checkRequiredInputs(name) {
	//controllare il livello di sicurezza usando un parametro per la ricerca getElementById. controllare XSS
	var fieldset = document.getElementById(name);
	for(var i=0; i < fieldset.elements.length; i++){
		if(fieldset.elements[i].value === '' && fieldset.elements[i].hasAttribute('required')){
			return false;
		}
	}
	return true;
}
   
function Form1() {
	document.getElementById("Form_1").style.display='inherit';
	document.getElementById("Form_2").style.display='none';
}
function Form1_foward() {
	if(checkRequiredInputs("Form_1")){
		document.getElementById('Missing1').innerText='';
		if(Ajax_Taken('Username',mycallback))
			if(passwordSecurity())
				Form2();
	}
	document.getElementById('Missing1').innerText='Please fill up all fields with "*" on the name';
		
}

function Form2() {
	document.getElementById("Form_1").style.display='none';
	document.getElementById("Form_2").style.display='inherit';
	document.getElementById("Form_3").style.display='none';
}
function Form2_foward() {
	if(checkRequiredInputs("Form_2")){
		document.getElementById('Missing2').innerText='';
		if(Ajax_Taken('Email',mycallback))
			Form3();
	}
	document.getElementById('Missing2').innerText='Please fill up all fields with "*" on the name';
}

function Form3() {
	document.getElementById("Form_2").style.display='none';
	document.getElementById("Form_3").style.display='inherit';
	document.getElementById("Form_4").style.display='none';
	
}
function Form3_foward() {
	if(checkRequiredInputs("Form_3")){
		document.getElementById('Missing3').innerText='';
		Form4();
	}
	document.getElementById('Missing3').innerText='Please fill up all fields with "*" on the name';
}

function Form4() {
	document.getElementById("Form_3").style.display='none';
	document.getElementById("Form_4").style.display='inherit';
}