<?php
    require_once "DBAccess.php";

    session_start();
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'CreateJob.html';
    $HTML = file_get_contents($url);

    if(isset($_SESSION['user_Username']))
    {
      $HTML = str_replace('<subpage/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR.'usrprfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>',$HTML);
    }
    else
    {
      $_SESSION['Url'] = 'CreateJob';
      header("location: ../PHP/Login.php");
    }

    echo $HTML;

?>
