<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
  require_once 'DBAccess.php';

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
    $HTMLContent='<div id="content">'.file_get_contents($urlContent).'</div>';
    $HTML = str_replace('<div id="content"></div>',$HTMLContent,$HTML);
    
    $HTML = str_replace('<error/>',isset($_SESSION['error'])?$_SESSION['error']:'',$HTML);
	$_SESSION['error']='';
    // Apre file html
    $HTML = str_replace('</javascript>','
    <!-- Javascript per Change User Info -->
    <script type="text/javascript" src="../JS/ChangePassword.js"></script>',$HTML);  

    echo $HTML;
  }
  else {
	$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
	header("Location:Login.php");  
}
exit();
?>