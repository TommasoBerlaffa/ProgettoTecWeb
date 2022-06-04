<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
  $DBAccess = new DBAccess();
  $conn = $DBAccess->openDBConnection();

  if($conn) {
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])) {
      isset($_POST["star"]) ? $star =  $_POST["star"] : $star=null;
      isset($_POST["comment"]) ? $comment =  $_POST["comment"] : $comment='';
      $date = date("Y/m/d");
      if($DBAccess->createReview($_SESSION['user_ID'],$_SESSION["Code_job"],$star,$comment,$date))
      header('Location:..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'ViewJobOld.php?Code_job='.$_SESSION["Code_job"]);    
    }
    else
      header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
}
else
  header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
?>