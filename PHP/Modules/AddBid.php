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
	if($work['Code_user']==$_SESSION['user_ID']){
		$_SESSION['error']='errABCreaor';
		exit();
	}
	if(isset($work['Status']) OR strtotime((new DateTime())->format("Y-m-d H:i:s")) > strtotime($work['Expiring'])){
		$_SESSION['error']='errNotPresent';
		exit();
	}
	$bids=$DBAccess->getBids($job);
	foreach($bids as $bid){
		if($bid['Code']==$_SESSION['user_ID']){
			$_SESSION['error']='errABAlready';
			exit();
		}
	}
	
	$price='';
	$description='';
	if( isset($_POST["Price"]) )
        $price =  filter_var($_POST["Price"],FILTER_VALIDATE_INT );
	if( isset($_POST["DescriptionBid"]) )
        $description =  filter_var($_POST["DescriptionBid"],FILTER_SANITIZE_STRING );
	if($price===''){
			$_SESSION['error']='errABPrice';
		exit();
	}
		
	$result = $DBAccess->createBid($_SESSION["user_ID"],$job,$price,$description);
    $DBAccess->closeDBConnection();
	$_SESSION['error']=($result? 'ABsucc' : 'ABfail');
	
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