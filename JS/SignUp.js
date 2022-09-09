
window.addEventListener('load',function() {
	document.getElementById("Sign_Up").disabled=true;
    document.getElementById("Form_2").style.display='none';
    document.getElementById("Form_3").style.display='none';
    document.getElementById("Form_4").style.display='none';
	document.getElementById('Password').addEventListener('keyup', passwordSecurity);
	document.getElementById('Repeat-Password').addEventListener('keyup', passwordMatch);
	document.getElementById('Next1').addEventListener('click', Form1_forward);
	document.getElementById('Back1').addEventListener('click', Form1);
	document.getElementById('Next2').addEventListener('click', Form2_forward);
	document.getElementById('Back2').addEventListener('click', Form2);
	document.getElementById('Next3').addEventListener('click', Form3_forward);
	document.getElementById('Back3').addEventListener('click', Form3);
})


function Ajax_Taken(content,callback) {
	const xhttp = new XMLHttpRequest();
	var data = {};
	if(content==='Username')
		data = {'Username':document.getElementById('Username').value};
	else if(content==='Email')
		data = {'Email':document.getElementById('Email').value};
	else
		return;
	
	xhttp.open('POST', '../PHP/Modules/AjaxSignUp.php', false);
	
	var st=true;
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200)
			st=callback(content, xhttp.responseText);
		else if (xhttp.readyState === 4 && xhttp.status === 429){
			document.getElementById(content + 'Taken').innerText='Too many requests, retry in 5 secs.';
			st= false;
		}
	}
	
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.send(JSON.stringify(data));
	
	return st;
}

function mycallback(content, response){
	if(response === 'OK'){
		document.getElementById(content + 'Taken').innerText='';
		return true;
	}
	else{
		document.getElementById(content + 'Taken').innerText=response;
		Form1();
	}
	return false;
}



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
function Form1_forward() {
	if(checkRequiredInputs("Form_1")){
		document.getElementById('Missing1').innerText='';
		if(Ajax_Taken('Username',mycallback) && (Ajax_Taken('Email',mycallback)) )
			if(passwordSecurity())
				Form2();
	}
  else
	  document.getElementById('Missing1').innerText='Please fill up all required fields';	
}

function Form2() {
	document.getElementById("Form_1").style.display='none';
	document.getElementById("Form_2").style.display='inherit';
	document.getElementById("Form_3").style.display='none';
}
function Form2_forward() {
	var date=document.getElementById('Birthday').value;
  var Firstname=document.getElementById('Firstname').value;
  var Lastname=document.getElementById('Lastname').value;
  var Country=document.getElementById('Country').value;
  var City=document.getElementById('City').value;
  var Address=document.getElementById('Address').value;
  var Tel=document.getElementById('Tel').value;
  
	console.log(date);	if(checkRequiredInputs("Form_2")){
		document.getElementById('Missing2').innerText='';
		Form3();
	}
    else{
      
      if(Firstname!='' && Firstname.length >=64)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Firstname">name</a> shorter than 64 characters';
      else if (Firstname == '')
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Firstname">name</a>';
      else if(Lastname!='' && Lastname.length >=64)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Lastname">surname</a> shorter than 64 characters';
      else if (Lastname == '')
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Lastname">surname</a>';
      else if(document.getElementById('Birthday').value =='')
        document.getElementById('Missing2').innerHTML='Please fill Birthday field with a valid date';
      else if(Country.length >=56)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Country">country</a> shorter than 56 characters';
      else if(Country=='')
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Country">country</a>';
      else if(Country.length <4)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Country">country</a> longer than 3 characters';

      else if(City!='' && City.length >=45)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#City">city</a> shorter than 45 characters';
      else if (City == '')
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#City">city</a>';
      else if(Address.length > 75)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Address">address</a> shorter than 75 characters';
      else if(Tel.length >=15)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Tel">phone number</a> shorter than 15 characters';
      else if(Tel.length <7)
        document.getElementById('Missing2').innerHTML='Please insert a <a href="#Tel">phone number</a> longer than 7 characters';
      else 
          document.getElementById('Missing2').innerHTML='Please fill up all required fields';
      
    }
}

function Form3() {
	document.getElementById("Form_2").style.display='none';
	document.getElementById("Form_3").style.display='inherit';
	document.getElementById("Form_4").style.display='none';
	
}
function Form3_forward() {
  var desc=document.getElementById('Desc').value;
	if(checkRequiredInputs("Form_3")){

    if(desc != '' && desc.length <4096 && desc.length >10){
      document.getElementById('Missing3').innerText='';
      Form4();
    }
    else
      if(desc == '')
        document.getElementById('Missing3').innerHTML='Please insert a <a href="#Desc">description</a>';
      else if(desc.length>4096)
        document.getElementById('Missing3').innerHTML='Please insert a <a href="#Desc">description</a> shorter than 4096 characters';
      else if(desc.length<10)
        document.getElementById('Missing3').innerHTML='Please insert a <a href="#Desc">description</a> longer than 10 characters ';
	}
  else
	  document.getElementById('Missing3').innerText='Please fill up all required fields';
}

function Form4() {
	document.getElementById("Form_3").style.display='none';
	document.getElementById("Form_4").style.display='inherit';
	document.getElementById("Sign_Up").disabled=false;
  //      <label id="Fpfp">Profile Picture : </label>

  if(document.getElementById("Picture").files[0]){
	var oFReader = new FileReader();
	oFReader.readAsDataURL(document.getElementById("Picture").files[0]);
	oFReader.onload = function (oFREvent) {
		document.getElementById("newpfp").src = oFREvent.target.result;
		document.getElementById("newpfp").hidden=false;
		document.getElementById("Fpfp").hidden=false;
	};
  }
  else{
	  document.getElementById("newpfp").hidden=true;
	  document.getElementById("Fpfp").hidden=true;
  }
  
  document.getElementById('FNickname').innerHTML = 'Username : ' + document.getElementById('Username').value ;  
  document.getElementById('FName').innerHTML = 'Name : ' + document.getElementById('Firstname').value;
  document.getElementById('FSurname').innerHTML = 'Surname : ' + document.getElementById('Lastname').value;
  document.getElementById('FEmail').innerHTML = 'Email : ' + document.getElementById('Email').value;
  document.getElementById('FBirth').innerHTML = 'Birthday : ' + document.getElementById('Birthday').value;
  document.getElementById('FNationality').innerHTML = 'Nationality : ' + document.getElementById('Country').value;
  document.getElementById('FCity').innerHTML = 'City : ' + document.getElementById('City').value;
  document.getElementById('FAddress').innerHTML = 'Address : ' + document.getElementById('Address').value;
  document.getElementById('FTel').innerHTML = 'Telephone Number : ' + document.getElementById('Tel').value;
  document.getElementById('FLink').innerHTML = 'Link to a Curriculum : ' + document.getElementById('Curr').value;
  document.getElementById('FDesc').innerHTML = 'Description : ' + document.getElementById('Desc').value;
  
}