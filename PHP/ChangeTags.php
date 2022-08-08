<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

  require_once "DBAccess.php";

  require_once "Modules" . DIRECTORY_SEPARATOR . "Util.php";

  if(!isset($_SESSION['TagList']))
		$_SESSION['TagList']=array();

  if(isset($_SESSION['user_Username']))
  {
    if($_SESSION['TagList']!=array())
    {
      // Apro Connessione a DB
      $DBAccess = new DBAccess();
      if(!($DBAccess->openDBConnection())){
        header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
        exit;
      }
      $result = $DBAccess->changeUserTags($_SESSION['user_ID'],$_SESSION['TagList']);
    
      $DBAccess->closeDBConnection();
      header("Location:Setting.php?err=". ($result ? 'succ' : 'err'));  
    }
    else
      header("Location:Setting.php?err=tags");
  }
  else
  {
	$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
    header("Location:Login.php");
  }

	exit();
?>


    