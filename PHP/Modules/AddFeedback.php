<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

if(isset($_SESSION['user_Username'])) {
	
	$star='';
	$comment='';
	$job='';
	if( isset($_POST["star"]) )
        $star =  filter_var($_POST["star"],FILTER_VALIDATE_INT );
	if( isset($_POST["comment"]) )
        $comment =  filter_var($_POST["comment"],FILTER_SANITIZE_STRING );
	if(isset($_GET['Code_job']))
		$job=filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
	if($star==='' OR $comment==='' OR $job===''){
		if(!$star)
			header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result=errStar');
		else if(!$comment)
			header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result=errComm');
		else
			header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result=fail');
		exit();
	}
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
	$result = $DBAccess->createReview($_SESSION['user_ID'],$job,$star,$comment);
    $DBAccess->closeDBConnection();
	header('Location:..'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$job.'&result='. ($result? 'succ' : 'fail'));
	
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