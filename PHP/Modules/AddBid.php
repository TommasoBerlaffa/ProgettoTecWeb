<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

if(isset($_SESSION['user_Username'])) {
	
	$price='';
	$description='';
	$job='';
	if( isset($_POST["Price"]) )
        $star =  filter_var($_POST["Price"],FILTER_VALIDATE_INT );
	if( isset($_POST["Description"]) )
        $comment =  filter_var($_POST["Description"],FILTER_SANITIZE_STRING );
	if(isset($_GET['Code_job']))
		$job=filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
	if($price==='' OR $job===''){
		header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result=bfail');
		if(!$price)
			header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result=errPrice');
		exit();
	}
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
	$result = $DBAccess->createBid($_SESSION["user_ID"],$job,$price,$description);
    $DBAccess->closeDBConnection();
	header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result='. ($result? 'bsucc' : 'bfail'));
	
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