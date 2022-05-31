<?php

  require_once "DBAccess.php";

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    if(isset($_POST['ChangePsw']))
    {
      $errorList ='';
      $password = filter_var($_POST["OldPsw"], FILTER_SANITIZE_STRING);
      $Newpassword = filter_var($_POST["Password"], FILTER_SANITIZE_STRING);
      $NewpasswordCheck = filter_var($_POST["Repeat-Password"], FILTER_SANITIZE_STRING);
    
      if($password != $Newpassword)
      {  
        if($Newpassword == $NewpasswordCheck)
        {
          $User = $_SESSION['user_Username'];
          $User = $_SESSION['user_ID'];
          $DbAccess = new DBAccess();
          $conn = $DbAccess->openDBConnection();
          $DbAccess->changePassword();
        }
        else //Errore password non coincidono 
        {
          
        }

      }
      else //Errore password vecchia uguale a nuova
      {
        header("Location: ..". DIRECTORY_SEPARATOR ."UserProfile". DIRECTORY_SEPARATOR ."Password.php");
      }
    }
    else
      header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."UserProfile.php");
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
  }
?>

 


  