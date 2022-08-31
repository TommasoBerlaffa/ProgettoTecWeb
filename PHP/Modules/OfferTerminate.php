<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

if(isset($_SESSION['user_Username'])) {
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
	
	$job='';
	if(isset($_GET['Code_job']))
		$job=filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
	header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job);
	if($job===''){
		$_SESSION['error']='errCodeJob';
		exit();
	}
	
	$work = $DBAccess->getJob($job);
	if($work['Code_user']!=$_SESSION['user_ID']){
		$_SESSION['error']='errINVOP';
		exit();
	}
	if(isset($work['Status']) OR strtotime((new DateTime())->format("Y-m-d H:i:s")) > strtotime($row['Expiring'])){
		$_SESSION['error']='errNotPresent';
		exit();
	}
	
	$result = $DBAccess->terminateJob($_SESSION['user_ID'],$code);
	$_SESSION['error']= $result? 'OTsucc' : 'OTfail';
    $DBAccess->closeDBConnection();
	
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