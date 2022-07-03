<?php

  // Inizio Sessione
  session_start();

  // Variabili pagina HTML e Switch
  $url ='..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FAQ.html';
  $HTML = file_get_contents($url);
  $HTMLContent='';

  //Controllo se variabile sessione è presente 
  if(isset($_SESSION['user_Username']))
  {
    // <li> <a href="../PHP/CreateJob.php"> <img src="../IMG/Icons/write.svg" class="icons"> Create a Job Offer </a> </li>
    $HTML = str_replace('<createjob/>','
      <li><a href="LoadCreateJob.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.png" class="icons" alt=""> Create an Offer </a></li>',$HTML);
    $HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
  }
  else
  {
    $HTML = str_replace('<createjob/>','',$HTML);
      $HTMLContent = '
        <li><a href="Login.php">
        <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'login.png" class="icons" alt=""> Login </a></li>
        <li><a href="Signup.php">
        <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'book.png" class="icons" alt=""> Sign up </a></li>';
  }

  // Cambio Pagina
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  // Apertura Pagina
  echo $HTML;
?>
