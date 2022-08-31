<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR . 'DBAccess.php';

if(isset($_SESSION['user_Username']))
{
	
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
	if(isset($work['Status'])){
		$_SESSION['error']='errNotPresent';
		exit();
	}
	$work = $DBAccess->getJob($job);
	if($work['Code_user']==$_SESSION['user_ID']){
		$_SESSION['error']='errINVOP';
		exit();
	}
	
	$bids=$DBAccess->getBids($job);
	$OK=false;
	foreach($bids as $bid){
		if($bid['Code']==$_SESSION['user_ID'])
			$OK=true;
	}
	if(!$OK){
		$_SESSION['error']='errRBNoBid';
		exit();
	}
	
	$result = $DBAccess->removeBid($code,$_SESSION['user_ID']);  
	$_SESSION['error'] = $result ? 'RBsucc' : 'RBfail';
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