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
	if(!isset($work['Status'])){
		$_SESSION['error']='errNotPast';
		exit();
	}
	if(!$DBAccess->getWinner($job)){
		$_SESSION['error']='errFWinner';
		exit();
	}
	if($DBAccess->getJobReview($job)){
		$_SESSION['error']='errFAlready';
		exit();
	}
		
	$star='';
	$comment='';
	if( isset($_POST["star"]) )
		$star =  filter_var($_POST["star"],FILTER_VALIDATE_INT );
	if( isset($_POST["comment"]) )
		$comment =  filter_var($_POST["comment"],FILTER_SANITIZE_STRING );
	
	if($star==='' OR $comment===''){
		$_SESSION['error']= (!$star)? 'errFStar' : 'errFComm';
		exit();
	}
	
	$result = $DBAccess->createReview($work['Code_winner'],$job,$star,$comment);
	$_SESSION['error']= $result? 'Fsucc' : 'Ffail';
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