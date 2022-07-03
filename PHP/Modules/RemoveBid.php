<?php
require_once '..' . DIRECTORY_SEPARATOR . 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])){ 
      $result = $DBAccess->removeBid($_GET['code'],$_SESSION['user_ID']);  
      $resultValue = $result ? 'removeTrue' : 'removeFalse';
      if($result)
        header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."ViewJob.php?Code_job=". $_GET['code']."&result=".$resultValue);
    }
    else
      header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
    $DBAccess->closeDBConnection();
}
else
	header("Location:.." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR . "PHP" . DIRECTORY_SEPARATOR . "Login.php");
?>