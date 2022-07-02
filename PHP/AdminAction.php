<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['Admin'])) {
  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  
  if(isset($_POST['comment']))
    $comment = $_POST['comment'];
  else
    $comment = '';
    

  if(isset($_GET['Code_user']))
  {
    $result = $DBAccess->BanUserAdmin($_GET['Code_user'],$_SESSION['user_ID'],$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewUser.php?Code_user=".$_GET['Code_user']);

  }

  if(isset($_GET['unban_Code_user']))
  {
    $result = $DBAccess->UnBanUserAdmin($_GET['unban_Code_user'],$_SESSION['user_ID'],$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewUser.php?Code_user=".$_GET['Code_user']);

  }

  if(isset($_GET['Code_job']))
  {
    $result = $DBAccess->DeleteJobAdmin($_GET['Code_job'],$_SESSION['user_ID'],$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewOffer.php?Code_job=".$_GET['Code_job']);
  }

  if(isset($_GET['Code_pastjob']))
  {
    $result = $DBAccess->DeletePastJobAdmin($_GET['Code_pastjob'],$_SESSION['user_ID'],$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewJobOld.php?Code_job=".$_GET['Code_pastjob']);
  }
  
  
  //header("Location:AdminHistory.php");
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");

?>