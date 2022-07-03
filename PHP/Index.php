<?php
  require_once 'DBAccess.php';
  // Inizio Sessione
  session_start();

  // Variabili pagina HTML e Switch
  $url ='..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Index.html';
  $HTML = file_get_contents($url);
  $HTMLContent='';

  //Controllo se variabile sessione è presente 
  if(isset($_SESSION['user_Username']))
  {
    // <li> <a href="../PHP/CreateJob.php"> <img src="../IMG/Icons/write.svg" class="icons"> Create a Job Offer </a> </li>
    $HTML = str_replace('<createjob/>','
      <li><a href="LoadCreateJob.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.png" class="icons" alt=""> Create an Offer </a></li>',$HTML);
    $HTMLContent = '<li><a href="UserProfile.php">
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

  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  $tags = $DBAccess->getMostPopularJobs();
  $DBAccess->closeDBConnection();
  if($tags)
  {
    $HTMLTags = '<ul id="popularJobsList">';
    foreach( $tags as $T)
    {
      $HTMLTags .= '<li>'.$T["frequency"] .' <a href="findjob.php?tag='.$T["Code_tag"].'">'. $T["Name"] .'</a> offers</li>';
    }
    $HTMLTags .= '</ul>';

    $HTML = str_replace('<popularjobs/>',$HTMLTags,$HTML);
  }
  else
  {
    $HTML = str_replace('<popularjobs/>','There are currently no popular tags available, please check again later',$HTML);
  }

  // Cambio Pagina
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  // Apertura Pagina
  echo $HTML;
?>
