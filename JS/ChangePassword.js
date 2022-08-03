window.onload = function(){
	document.getElementById("Password").addEventListener('keyup',passwordSecurity)
	document.getElementById("Repeat-Password").addEventListener('keyup',passwordMatch)
};

function passwordSecurity() {
	if(document.getElementById("Password").value==''){
		document.getElementById('Security').innerHTML = '';
		return false;
	}
	let regexpPwd = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})');
	if(regexpPwd.test(document.getElementById("Password").value)){
    document.getElementById('Security').classList.remove('error');
		document.getElementById('Security').classList.add('correct');
		document.getElementById('Security').innerHTML = 'Strong Password';
		if(passwordMatch())
			return true;
		else
			return false;
	}
	else {
    document.getElementById('Security').classList.remove('correct');
		document.getElementById('Security').classList.add('error');
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
    document.getElementById('Match').classList.remove('error');
		document.getElementById('Match').classList.add('correct');
		document.getElementById('Match').innerHTML = 'matching';
		return true;
	} else {
    document.getElementById('Match').classList.remove('correct');
		document.getElementById('Match').classList.add('error');
		document.getElementById('Match').innerHTML = 'not matching';
		return false;
	}
}

