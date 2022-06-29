<?php

  require_once "DBAccess.php";

  if(!isset($_SESSION)) 
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
          $DBAccess = new DBAccess();
          if(!($DBAccess->openDBConnection())){
            header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
            exit;
          }
          $DBAccess->changePassword($password,$Newpassword);
		      $DBAccess->closeDBConnection();
        }
        else //Errore password non coincidono 
          $errorList.='<li>Your new password doesn\'t match</li>';
      }
      else //Errore password vecchia uguale a nuova
        $errorList.='<li>Your old and new password can\'t be the same</li>';

      if($errorList!='')
        $_SESSION['error'] = $errorList;
      header("Location: ..". DIRECTORY_SEPARATOR ."UserProfile". DIRECTORY_SEPARATOR ."Password.php");
    }
    else
      header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."UserProfile.php");
  }
  else
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
  
?>

 


  