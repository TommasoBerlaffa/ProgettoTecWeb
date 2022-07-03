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
		$id=$_SESSION['user_ID'];
		if( isset($_POST["Price"]) )
      $price =  filter_var($_POST["Price"],FILTER_VALIDATE_INT );
    else
      header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&bid=errPrice');    
  
    if( isset($_POST["Description"]) )
      $description =  filter_var($_POST["Description"],FILTER_SANITIZE_STRING );
    else
      header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&bid=errDesc');    
    
		$DBAccess->createBid($id,$_SESSION["Code_job"],$price,$description);
		$DBAccess->closeDBConnection();
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"].'&bid=succ');    
    }
    else{
		$DBAccess->closeDBConnection();
		header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
	}
}
else
	header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
?>