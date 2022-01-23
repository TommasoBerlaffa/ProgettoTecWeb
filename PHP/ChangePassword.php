<?php

  require_once "DBAccess.php";

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    if(isset($_POST['ChangePsw']))
    {
      $password = filter_var($_POST["OldPsw"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
      $Newpassword = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      $Newpassword = filter_var($Newpassword, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
      $NewpasswordCheck = filter_var($_POST["Repeat-Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      $NewpasswordCheck = filter_var($NewpasswordCheck, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    
      if($password != $Newpassword)
      {  
        if($Newpassword == $NewpasswordCheck)
        {
          $User = $_SESSION['user_Username'];
          // Faccio il cambio effettivo
        }
        else
        {
          //Errore password non coincidono 
        }

      }
      else
      {
        //Errore password vecchia uguale a nuova
      }
    }
    
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
  }
?>

 


  