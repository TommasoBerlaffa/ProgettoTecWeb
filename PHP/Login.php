<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	require_once 'DBAccess.php';
	
	$user = 'admin'; $pwd = 'admin';
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
	$Logged=$DBAccess->login($user, $pwd);
	$taglist = $DBAccess->getTags($Logged['ID'],0);
	$DBAccess->closeDBConnection();
	
	if(isset($Logged['Admin']) && $Logged['Admin']==1){
		$_SESSION['Admin'] = $Logged['Admin'];
	}
	
	$_SESSION['user_ID'] = $Logged['ID'];
	$_SESSION['user_Status'] = $Logged['Status'];
	$_SESSION['user_Username'] = $Logged['Username'];
	$_SESSION['user_Icon'] = $Logged['Icon'];
	
	
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
	

?>
