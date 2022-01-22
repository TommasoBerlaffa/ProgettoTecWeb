
function onLoad() {
    document.getElementById("Form_2").style.display='none';
    document.getElementById("Form_3").style.display='none';
    document.getElementById("Form_4").style.display='none';
}


function AjaxUsernameTaken() {
	const xhttp = new XMLHttpRequest();
	var data = {Username:document.getElementById('Username').value};
	xhttp.open('POST', '../PHP/AjaxSignUp.php', true);
	
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200){
			document.getElementById('UsernameTaken').innerText=this.responseText;
			return true;
		}
	}
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
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
	if(checkRequiredInputs("Form_1"))
		if(!AjaxUsernameTaken()){
			return;
			alert("no match");
		}
		else
			alert("match found");
		return;
			//if(passwordSecurity())
				//Form2();
}

function Form2() {
	document.getElementById("Form_1").style.display='none';
	document.getElementById("Form_2").style.display='inherit';
	document.getElementById("Form_3").style.display='none';
}
function Form2_foward() {
	if(checkRequiredInputs("Form_3"))
		Form3();
}

function Form3() {
	document.getElementById("Form_2").style.display='none';
	document.getElementById("Form_3").style.display='inherit';
	document.getElementById("Form_4").style.display='none';
	
}
function Form3_foward() {
	if(checkRequiredInputs("Form_4"))
		Form4();
}

function Form4() {
	document.getElementById("Form_3").style.display='none';
	document.getElementById("Form_4").style.display='inherit';
}