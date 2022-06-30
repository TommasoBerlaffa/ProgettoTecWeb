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

