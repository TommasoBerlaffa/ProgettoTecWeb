<?php

    require_once 'DBAccess.php';
	
	
	//$rexSafety = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
	//if (preg_match($rexSafety, 'ma{rtin')) {
	//	var_dump('bad name');
	//} else {
	//	var_dump('ok');
	//}
	//
	//function isValid($str) {
	//	return !preg_match('/[^A-Za-z0-9.#\\-$]/', $str);
	//}
	//function isValid($str) {
	//	return !preg_match('/[^A-Za-z]/', $str);
	//}
	//function isValid($str) {
	//	return !preg_match('/[^A-Za-z0-9.#\\-$]/', $str);
	//}

    session_start();
    //Controllo se Login è già stato effettuato
	$page=null;
	if(isset($_GET['section'])){
		$page=filter_var($_GET['section'], FILTER_VALIDATE_INT);
	}
	$paginaHTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'SignUp.html');
	
	$messaggioErrore = '';
	
	$Username = '';
    $Name = '';
    $Surname = '';
    $Password = '';
	$Birth = '';
    $Email = '';
    $Nationality = '';
    $City = '';
    $Address = '';
    $Phone = '';
	$Picture = '';
	$Curriculum = '';
	$Description = '';
	$Skill1 = '';
	$Skill2 = '';
	$Skill3 = '';
	$Skill4 = '';
	$Skill5 = '';
	
	
	
    if(isset($_POST['Sign Up'])) {
		
		if(!isset($_POST['Username']))
			$messaggioErrore .='<li>Username field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Username field must be filled.</li>';
			else{
				$DBAccess= new DBAccess();
				if($DBAccess->UsernameTaken($tmp))
					$messaggioErrore .= '<li>This Username is already taken.</li>';
				$Username = $tmp;
			}
		}
		
		if(!isset($_POST['Name']))
			$messaggioErrore .='<li>Name field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Name'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Name field must be filled.</li>';
			//questa cosa è da valutare. Quali caratteri dovrei filtrare sul nome di una persona?
			//e nel caso che messaggio dovrei stampare?
			else if(preg_match('/[^A-Za-z\']/', $tmp))
				$messaggioErrore .= '<li>Name field contains invalid characters.</li>';
			else
				$Name = $tmp;
		}
		
		if(!isset($_POST['Surname']))
			$messaggioErrore .='<li>Surname field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Surname'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Surname field must be filled.</li>';
			//questa cosa è da valutare. Quali caratteri dovrei filtrare sul nome di una persona?
			//e nel caso che messaggio dovrei stampare?
			else if(preg_match('/[^A-Za-z\']/', $tmp))
				$messaggioErrore .= '<li>Surname field contains invalid characters.</li>';
			else
				$Surname = $tmp;
		}
		
		if(!isset($_POST['Password']))
			$messaggioErrore .='<li>Password field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Password'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Password field must be filled.</li>';
			else
				$Password = $tmp;
		}
		
		if(!isset($_POST['Repeat-Password'])){
			$messaggioErrore .='<li>Please Repeat Password on the second field.</li>';
			$Password = '';
		}
		else{
			if($Password != ''){
				$tmp = filter_var($_POST['Repeat-Password'], FILTER_SANITIZE_STRING);
				if(strlen($tmp) == 0){
					$messaggioErrore .= '<li>Repeat Password field must be filled.</li>';
					$Password = '';
				}
				else if($tmp!=$Password){
					$messaggioErrore .= '<li>Password and Repeat Password do not match.</li>';
					$Password = '';
				}
			}
		}
		
		if(!isset($_POST['Bday']))
			$messaggioErrore .='<li>Birthday field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Bday'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Birthday field must be filled.</li>';
			else if(preg_match('/[^0-9-]/', $tmp)){
				$dt = DateTime::createFromFormat("Y-m-d", $tmp);
				if($dt !== false && !array_sum($dt::getLastErrors()))
					$messaggioErrore .= '<li>Birthday field has an invalid date.</li>';
				$messaggioErrore .= '<li>Birthday field should be in fomat YYYY-MM-DD.</li>';
			}
			$birthDate=explode('-',$tmp);
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")? ((date("Y") - $birthDate[0]) - 1): (date("Y") - $birthDate[0]));
			if($age<18)
				$messaggioErrore .= '<li>Your age is less than what required by laws (18).</li>';
			else
				$Birth = $tmp;
		}
		
		if(!isset($_POST['Email']))
			$messaggioErrore .='<li>Email field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Email field must be filled.</li>';
			$domain=explode('@',$tmp);
			if( exec('grep '.escapeshellarg($domain[1]).' ..'. DIRECTORY_SEPARATOR .'disposable-email-domains.txt'))
				$messaggioErrore .= '<li>Email domain is blacklisted as disposable.</li>';
			else{
				$DBAccess= new DBAccess();
				if($DBAccess->EmailTaken($tmp))
					$messaggioErrore .= '<li>This Email is already used.</li>';
				$Email = $tmp;
			}
		}
		
		if(!isset($_POST['Nationality']))
			$messaggioErrore .='<li>Email field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Nationality'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Nationality field must be filled.</li>';
			else if(!ctype_alpha($tmp))
				$messaggioErrore .= '<li>Nationality contains non alphabetcis characters.</li>';
			else
				$Nationality = $tmp;
		}
		
		if(!isset($_POST['City']))
			$messaggioErrore .='<li>City field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['City'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>City field must be filled.</li>';
			if(!ctype_alpha($tmp))
				$messaggioErrore .= '<li>City contains non alphabetcis characters.</li>';
			else
				$City = $tmp;
		}
		
		if(isset($_POST['Address'])){
			$tmp = filter_var($_POST['Address'], FILTER_SANITIZE_STRING);
			$Address = $tmp;
		}
		
		if(isset($_POST['Tel'])){
			$tmp = filter_var($_POST['Tel'], FILTER_VALIDATE_INT);
			if(!$tmp)
				$messaggioErrore .='<li>This is not a phone number.</li>';
			else
				$Phone = $tmp;
		}
		
		if(!isset($_POST['Picture']))
			$messaggioErrore .='<li>Picture field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Picture'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Picture field must be filled.</li>';
			else
				$Picture = $tmp;
		}
		
		if(isset($_POST['Curr'])){
			$tmp = filter_var($_POST['Curr'], FILTER_SANITIZE_URL);
			$Curriculum = $tmp;
		}
		
		if(!isset($_POST['Description']))
			$messaggioErrore .='<li>Description field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Description'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Description field must be filled.</li>';
			else
				$Description = $tmp;
		}
		
		
		
		if($messaggioErrore==''){

			$DBAccess = new DBAccess();
            $Success=$DBAccess->Regiser_new_user($Password, $Name, $Surname, $Username, $Birth, $Email, $Nationality, $City, $Address, $Phone, $Picture, $Curriculum, $Description);
			if(!$Success)
				$messaggioErrore .= '<li>Something went wrong while creating your new account.</li>';
			else
				header('Location:Login.php');
			
		}
		
	}

	$paginaHTML =  str_replace('<Username />', $Username, $paginaHTML);
	$paginaHTML =  str_replace('<Name />', $Name, $paginaHTML);
	$paginaHTML =  str_replace('<Surname />', $Surname, $paginaHTML);
	$paginaHTML =  str_replace('<Birth />', $Birth, $paginaHTML);
	$paginaHTML =  str_replace('<Email />', $Email, $paginaHTML);
	$paginaHTML =  str_replace('<Nationality />', $Nationality, $paginaHTML);
	$paginaHTML =  str_replace('<City />', $City, $paginaHTML);
	$paginaHTML =  str_replace('<Address />', $Address, $paginaHTML);
	$paginaHTML =  str_replace('<Phone />', $Phone, $paginaHTML);
	$paginaHTML =  str_replace('<Picture />', $Picture, $paginaHTML);
	if($Curriculum=='')
		$Curriculm="https:://";
	$paginaHTML =  str_replace('<Curriculum />', $Curriculum, $paginaHTML);
	$paginaHTML =  str_replace('<Description />', $Description, $paginaHTML);
		
		
    $paginaHTML =  str_replace('<messaggiForm />', $messaggioErrore, $paginaHTML);
    echo $paginaHTML;
?>