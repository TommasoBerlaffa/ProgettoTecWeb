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
    $comment = $_POST['comment'];
  else
    $comment = '';
    

  if(isset($_GET['Code_user']))
  {
    $result = $DBAccess->BanUserAdmin($_GET['Code_user'],$_SESSION['user_ID'],'Ban reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewUser.php?Code_user=".$_GET['Code_user']);

  }

  if(isset($_GET['unban_Code_user']))
  {
    $result = $DBAccess->UnBanUserAdmin($_GET['unban_Code_user'],$_SESSION['user_ID'],'Unban reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewUser.php?Code_user=".$_GET['Code_user']);

  }

  if(isset($_GET['Code_job']))
  {
    $result = $DBAccess->DeleteJobAdmin($_GET['Code_job'],$_SESSION['user_ID'],'Offer delete reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewJob.php?Code_job=".$_GET['Code_job']);
  }

  if(isset($_GET['Code_pastjob']))
  {
    $result = $DBAccess->DeletePastJobAdmin($_GET['Code_pastjob'],$_SESSION['user_ID'],'Job delete reason : '.$comment);
    if($result)
      header("Location:AdminHistory.php");
    else
      header("Location:ViewJob.php?Code_job=".$_GET['Code_pastjob']);
  }
  
  
  //header("Location:AdminHistory.php");
}
else
	header("Location:Welcome.php");

exit();
?>