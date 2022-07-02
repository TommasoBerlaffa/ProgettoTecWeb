<?php
    require_once "DBAccess.php";

    // Attivo Session
    if(!isset($_SESSION)) 
      session_start();

    // Controllo se il Login è stato effettuato
    if(!isset($_SESSION['user_Username']))
    {
      $_SESSION['Url'] = 'CreateJob';
      header("location: ../PHP/Login.php");
    }
  
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'CreateJob.html';
    $HTML = file_get_contents($url);
  
    $HTML = str_replace('<subpage/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
    <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR.'usrprfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>',$HTML);

    $HTML = str_replace('<title/>','',$HTML);
    $HTML = str_replace('<desc/>','',$HTML);
    $HTML = str_replace('<min/>','',$HTML);
    $HTML = str_replace('<max/>','',$HTML);
    echo $HTML;

    

?>
