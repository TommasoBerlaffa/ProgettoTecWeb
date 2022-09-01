<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	require_once 'DBAccess.php';
	if(!isset($_SESSION['user_Username']))
	{
		$paginaHTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Login.html');
		
		$messaggioErrore = '';
		if(isset($_POST['Login'])) {
			
		// Check Username
			$user = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
			if(strlen($user) == 0)
				$messaggioErrore .= '<p id="error_user">Missing username, please <a href="#Username">insert your username here </a></p>';
		// Check Password
			$pwd = filter_var($_POST['Password'], FILTER_SANITIZE_STRING);
			if(strlen($pwd) == 0)
				$messaggioErrore .= '<p id="error_pw">Missing password, please <a href="#Password">insert your password here </a></p>';
		
			if($messaggioErrore == '') {
				$DBAccess = new DBAccess();
				if(!($DBAccess->openDBConnection())){
					header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
					exit;
				}
				$Logged=$DBAccess->login($user, $pwd);
			
				if($Logged != null) {
					
					$user = ''; $pwd = '';
					
					if(isset($Logged['Admin']) && $Logged['Admin']==1){
						$_SESSION['Admin'] = $Logged['Admin'];
					}
					
					$_SESSION['user_ID'] = $Logged['ID'];
					$_SESSION['user_Status'] = $Logged['Status'];
					$_SESSION['user_Username'] = $Logged['Username'];
					$_SESSION['user_Icon'] = $Logged['Icon'];
				
			
					$taglist = $DBAccess->getTags($Logged['ID'],0);
					$DBAccess->closeDBConnection();
					unset($_SESSION['TagList']);
					
					$_SESSION['TagList']=array();
					if($taglist)
					{
						foreach($taglist as $name=>$value){
						$_SESSION['TagList'][$name] = $value;
						}	
					}
					// Redirect 
					header('Location:Welcome.php');
					if(isset($_SESSION['redirect']))
						header('Location:'.$_SESSION['redirect']);
					unset($_SESSION['redirect']);
					
					exit();
				} 
				else
					$messaggioErrore =  '<div id="errorList" class="box"><p id="error_user"></p><p id="error_pw">The username and password you inserted are not correct. Please <a href="#Username">try inserting your nickname and password again</a>.</p></div>';
			} 
			else
				$messaggioErrore = '<div id="errorMessages" class="box"><ul>' . $messaggioErrore . '</ul></div>';
		} 

		$paginaHTML =  str_replace('<messaggiForm />', $messaggioErrore, $paginaHTML);
		echo $paginaHTML;
	}
	else{
		// Redirect 
		header('Location:Welcome.php');
		if(isset($_SESSION['redirect']))
			header('Location:'.$_SESSION['redirect']);
		unset($_SESSION['redirect']);
	}
	
	exit();
	

?>
