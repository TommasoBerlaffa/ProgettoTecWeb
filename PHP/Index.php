<?php

  // Get HTML
  // HTML/Index.html
  $url ='..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'Index.html';
  $HTML = file_get_contents($url);
  session_start();
  $HTMLContent='';
  if(isset($_SESSION['user_Username']))
  {
    $HTML = str_replace('{{CreateJob}}','<a href="..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'CreateJob.html"> Create a Job Offer </a>',$HTML);
      $HTMLContent = '<a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'UserProfile.php"> User Profile </a>
        <img src="..'.DIRECTORY_SEPARATOR.'IMG'.DIRECTORY_SEPARATOR. $_SESSION['user_Icon'] .'" alt="Profile Picture" id="icon">';
   
  }
  else
  {
    $HTML = str_replace('<li>{{CreateJob}}</li>','',$HTML);
      $HTMLContent = '<a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'Signup.php"> Sign up </a></li>
                      <li class="right"><a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'Login.php"> Login </a>'; 
  }

  $HTML = str_replace('{{SubPage}}',$HTMLContent,$HTML);
  // Apertura Pagina
  echo $HTML;
?>