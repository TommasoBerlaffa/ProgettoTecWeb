<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {

  // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
  if(isset($_SESSION['user_ID'])) {
    if(isset($_SESSION['Code_job']))
	  {
      $DBAccess = new DBAccess();
      if(!($DBAccess->openDBConnection())){
        header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
        exit;
      }
      $result = $DBAccess->deleteJob($_SESSION['user_ID'],$_SESSION['Code_job']);
      $DBAccess->closeDBConnection();
      header("Location:". ($result ? "ViewJobOld.php?Code_job=" : "ViewOffer.php?Code_job=") .  $_SESSION['Code_job']);
	  }
    else
      header("Location:Findjob.php");
  }
  else
	  header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
?>