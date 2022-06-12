<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])){ 
      $result = $DBAccess->removeBid($_GET['code'],$_SESSION['user_ID']);  
      if($result)
        header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."ViewOffer.php?Code_job=". $_GET['code']);
    }
    else
      header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
    $DBAccess->closeDBConnection();
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
?>