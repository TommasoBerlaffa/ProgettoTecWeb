<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
  require_once 'DBAccess.php';

  if(isset($_SESSION['user_Username'])) {

    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","User Settings",$HTML);

    $HTML = str_replace('<li><a href="../PHP/Setting.php"><img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span></a></li>',
    '<li class="selected"><img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span></li>',$HTML);
  
    $urlExtra = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'Settings.html';
    // Mettere i valori dentro la Form
    $DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	
    $HTMLExtra = '<div id="content">'.file_get_contents($urlExtra).'<div id="emptyErrorList"></div>';


    $Result = $DBAccess->getUser($_SESSION['user_ID']);
	$DBAccess->closeDBConnection();
    $HTMLExtra = str_replace('{{Username}}',trim($Result['Nickname']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Name}}',trim($Result['Name']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Surname}}',trim($Result['Surname']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Email}}',trim($Result['Email']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Birth}}',trim($Result['Birth']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Picture}}','..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR .trim($Result['Picture']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Nationality}}',trim($Result['Nationality']),$HTMLExtra);
    $HTMLExtra = str_replace('{{City}}',trim($Result['City']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Address}}',trim($Result['Address']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Tel}}',trim($Result['Phone']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Curr}}',trim($Result['Curriculum']),$HTMLExtra);
    $HTMLExtra = str_replace('{{Desc}}',trim($Result['Description']),$HTMLExtra); 

    $urlTags = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'ChangeTags.html';
    $HTMLExtra .= file_get_contents($urlTags);

    $urlTagElement =  '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TagsSearch.html';
    $TagElements = file_get_contents($urlTagElement);
    $HTMLExtra = str_replace('<TagModule/>',$TagElements,$HTMLExtra);
  
    $HTMLExtra .= '</div>';
    $HTML = str_replace('<div id="content"></div>',$HTMLExtra,$HTML);

    // Spazione vuoto
    if(isset($_SESSION['error'])) {
      $HTML = str_replace('<div id="emptyErrorList"></div>','<div id="errorList">'.$_SESSION['error'].'</div>',$HTML);
      unset($_SESSION['error']);
    }
    
    if(isset($_GET['err'])) {
      $err='';
      if($_GET['err'] == 'tags')
        $err = '<div id="errorList">Please, insert some <a href="#searchTag">new tags</a> or remove old tags to change your tag list.</div>';   
      else if($_GET['err'] == 'err')
        $err = '<div id="errorList">There was an error with the insertion of the tags in the database, please try again later.</div>';   
      else if($_GET['err'] == 'succ')
        $err = '<div id="errorList">The operation was succesaful.</div>';   
  
      $HTML = str_replace('<div id="emptyErrorList"></div>',$err,$HTML);  
    }

    $HTML = str_replace('</javascript>','
    <!-- Javascript per Change User Info -->
    <script type="text/javascript" src="../JS/searchTag.js"></script>',$HTML);  
    // Apre file html
    echo $HTML;
  }
  else {
	$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
	header("Location:Login.php"); 
}
exit();
?>