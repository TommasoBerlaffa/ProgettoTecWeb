<?php
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])) {
      if( isset($_POST["star"]) )
        $star =  filter_var($_POST["star"],FILTER_VALIDATE_INT );
      else
        header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&feedback=errStar');    
    
      if( isset($_POST["comment"]) )
        $comment =  filter_var($_POST["comment"],FILTER_SANITIZE_STRING );
      else
        header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&feedback=errComm');    
      
      $date = date("Y/m/d");
      $result = $DBAccess->createReview($_SESSION['user_ID'],$_SESSION["Code_job"],$star,$comment,$date);
      $DBAccess->closeDBConnection();
      $result ?  header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&feedback=succ')
      : header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&feedback=fail');
      
    
    }
    else{
		$DBAccess->closeDBConnection();
		header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
	}
}
else
	header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
?>