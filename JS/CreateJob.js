function checkRequiredInputs() {
	//controllare il livello di sicurezza usando un parametro per la ricerca getElementById. controllare XSS
	var fieldset = document.getElementById(createJob);
	for(var i=0; i < fieldset.elements.length; i++){
		if(fieldset.elements[i].value === '' && fieldset.elements[i].hasAttribute('required')){
			return false;
		}
	}
	return true;
}