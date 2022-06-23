<?php

  require_once "DBAccess.php";

  require_once "Util.php";
	
  if(!isset($_SESSION)) 
    session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Apro Connessione a DB
    $DbAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  

  
    $DBAccess->closeDBConnection();
	header("Location: ..". DIRECTORY_SEPARATOR ."UserProfile". DIRECTORY_SEPARATOR ."Setting.php");  
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR. "Login.php");
  }

?>


    