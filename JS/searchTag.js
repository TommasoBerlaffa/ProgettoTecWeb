const myTimeout = setTimeout(myGreeting, 100);

function myGreetings(){return;}

document.getElementById('searchTag').onkeyup = function (e, mycallback) {
  e = e || window.event;
	const xhttp = new XMLHttpRequest();
	var data = {};
	if(document.getElementById('searchTag').value.lenght<3)
    return;
	data = {Tag:document.getElementById('searchTag').value};
	
	xhttp.open('POST', '../PHP/AjaxTagSearch.php', false);
	
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState === 4 && xhttp.status === 200){
			if(callback(e, xhttp.responseText))
				return true;
			return false;			
		}
		else if (xhttp.readyState === 4 && xhttp.status === 429){
			document.getElementById('searchTag').innerText='Too many requests, retry in 5 secs.';
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
		//document.getElementById(content + 'Taken').innerText='';
		return true;
	}
	else
		//document.getElementById(content + 'Taken').innerText=response;
	return false;
}
