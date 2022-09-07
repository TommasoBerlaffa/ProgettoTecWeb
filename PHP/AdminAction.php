<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

require_once 'DBAccess.php';


if(isset($_SESSION['Admin'])) {
  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
  
  if(isset($_POST['comment']))
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
  else
    $comment = '';
    

  if(isset($_GET['Code_user']))
  {
	$user = filter_var($_GET['Code_user'], FILTER_VALIDATE_INT);
    $result = $DBAccess->BanUserAdmin($user,$_SESSION['user_ID'],'Ban reason : '.$comment);
    if($result){
		$works=$DBAccess->getJobListbyCreator($user);
		if($works){
			foreach($works as $w){
				echo($w['Code_job']);
				$DBAccess->DeleteJobAdmin($w['Code_job'],$_SESSION['user_ID'],'The creator of this job got banned.');
			}
		}
      header("Location:AdminHistory.php");
	}
    else
      header("Location:ViewUser.php?Code_user=".$user);
	exit();

  }

  else if(isset($_GET['unban_Code_user']))
  {
	$user = filter_var($_GET['unban_Code_user'], FILTER_VALIDATE_INT);
    $result = $DBAccess->UnBanUserAdmin($user,$_SESSION['user_ID'],'Unban reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewUser.php?Code_user=".$user);
	exit();
  }

  else if(isset($_GET['Code_job']))
  {
	$job = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
    $result = $DBAccess->DeleteJobAdmin($job,$_SESSION['user_ID'],'Offer delete reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewJob.php?Code_job=".$job);
    exit();
  }

  else if(isset($_GET['Code_pastjob']))
  {
	$job = filter_var($_GET['Code_pastjob'], FILTER_VALIDATE_INT);
    $result = $DBAccess->DeletePastJobAdmin($job,$_SESSION['user_ID'],'Job delete reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewJob.php?Code_job=".$job);
    exit();
  }
  
  
  //header("Location:AdminHistory.php");
}
else
	header("Location:Welcome.php");

exit();
?>