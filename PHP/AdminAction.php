<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['Admin'])) {
  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  
  if(isset($_GET['Code_user']))
  {
    $comment = $_POST['comment'];
    $result = $DBAccess->BanUserAdmin($_GET['Code_user'],$_SESSION['user_ID'],$comment);
    // ban user

    // Add action to admin history

  }
  
  
  header("Location:AdminHistory.php");
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");

?>