<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR ."DBAccess.php";

if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$.:,;-_%]{8,50}$/', $password)) {
    echo 'the password does not meet the requirements!';
}
	
	if(isset($_SESSION['user_Username']))
	{
		if(isset($_POST['ChangePsw']))
		{
			$errorList ='<ul class="resultfail">';
			$password = filter_var($_POST["OldPsw"], FILTER_SANITIZE_STRING);
			$Newpassword = filter_var($_POST["Password"], FILTER_SANITIZE_STRING);
			if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$.:,;-_%]{8,50}$/', $Newpassword))
				$errorList.= '<li id="pw_not_valid">The password does not meet the requirements!</li>';
			$NewpasswordCheck = filter_var($_POST["Repeat-Password"], FILTER_SANITIZE_STRING);
			
			if($errorList == '<ul class="resultfail">'){
				if($password !== $Newpassword) 
				{  
					if($Newpassword === $NewpasswordCheck)
					{
						$DBAccess = new DBAccess();
						if(!($DBAccess->openDBConnection())){
							header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
							$DBAccess->closeDBConnection();
							exit();
						}
						if($DBAccess->login($_SESSION['user_Username'], $password)){
							if($DBAccess->changePassword($_SESSION['user_ID'],$Newpassword))
								$errorList ='<ul class="resultsucc"><li>Succesfully changed password</li>';
						}
						else //Errore password vecchia sbagliata
							$errorList.='<li id="old_pw">Incorrect old password. Please try to <a href="#OldPsw">enter your old password again</a>.</li>';
						$DBAccess->closeDBConnection();
					}
					else //Errore password non coincidono 
						$errorList.='<li id="new_pw_match">Your new password doesn\'t match.Please try to <a href="#Password">enter your new password again</a>.</li>';
				}
				else //Errore password vecchia uguale a nuova
					$errorList.='<li id="old_new_pw">Your old and new password can\'t be the same. Please try to <a href="#OldPsw">enter your old password</a>.</li>';
			}
			
			if($errorList!='<ul class="resultfail">')
				$_SESSION['error'] = $errorList . '</ul>';
		}
		  header("Location:..". DIRECTORY_SEPARATOR ."Password.php");
	}
	else {
		$_SESSION['redirect']='..'. DIRECTORY_SEPARATOR .'Password.php';
		header("Location:..". DIRECTORY_SEPARATOR ."Login.php");
	}
exit();
  
?>

 


  