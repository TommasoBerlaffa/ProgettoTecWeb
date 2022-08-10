<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

if(isset($_SESSION['user_Username'])) {

	$code='';
    if(isset($_GET['Code_job']))
		$code = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
	if($code!==false){
		$DBAccess = new DBAccess();
		if(!($DBAccess->openDBConnection())){
			header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
			exit();
		}
		$result = $DBAccess->deleteJob($_SESSION['user_ID'],$code);
		$DBAccess->closeDBConnection();
		header("Location:..". DIRECTORY_SEPARATOR ."ViewJob.php?Code_job=" . $code."&result=".($result? 'cancelTrue' : 'cancelFalse'));
	}
	else
		header("Location:..". DIRECTORY_SEPARATOR ."ViewJob.php?Code_job=". $code."&result=cancelFalse");
}
else {
  if(isset($_GET['Code_job'])){
	$_SESSION['redirect']='ViewJob.php?Code_job='.filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
	header("Location:..". DIRECTORY_SEPARATOR ."Login.php");
  }
  else
	header("Location:..". DIRECTORY_SEPARATOR ."Index.php");   
}
exit();
?>