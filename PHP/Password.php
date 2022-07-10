<?php
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Change Password",$HTML);

    $HTML = str_replace('<li><a href="../PHP/Password.php"><img src="../IMG/Icons/sidebar.png" class="icons" alt=""><span class="sidebarText"> Change Password</span></a></li>',
    '<li class="selected">
      <img src="../IMG/Icons/sidebar.png" class="icons" alt=""><span class="sidebarText"> Change Password</span>
    </li>',$HTML);
  
    $urlContent = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'ChangePassword.html';
    $HTMLContent=file_get_contents($urlContent);
    $HTML = str_replace('<div id="content"></div>',$HTMLContent,$HTML);
    
    $HTML = str_replace('<error/>',isset($_SESSION['error'])?$_SESSION['error']:'',$HTML);
	$_SESSION['error']='';
    // Apre file html
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 5);
?>