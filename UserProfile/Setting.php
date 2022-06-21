<?php
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();

  if(isset($_SESSION['user_Username'])) {
    // Non servono controlli su Login perchè vengono fatti da UserProfile.php

    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","User Settings",$HTML);

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=4"><img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span></a></li>',
    '<li class="selected"><img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span></li>',$HTML);
  
    $urlExtra = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'Settings.html';
    // Mettere i valori dentro la Form
    $DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	
    $HTMLExtra = '<div id="content">'.file_get_contents($urlExtra).'<div id="emptyErrorList"></div></div>';


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
    
    $tags = $DBAccess->getTags($_SESSION['user_ID'],0);
    $HTMLTags = '';
    $num = 0;
    if(isset($tags)){
      //$num=count($tags);
      foreach($tags as $name=>$value)
      {
        $HTMLTags .= '<li>'.$name.'</li>';
      }  
    }
    //$HTMLExtra = str_replace('{{num}}',$num,$HTMLExtra);
    $HTMLExtra = str_replace('{{yourTags}}',$HTMLTags,$HTMLExtra);

    
    $HTML = str_replace('<div id="content"></div>',$HTMLExtra,$HTML);
    // Spazione vuoto
    if(isset($_SESSION['error'])) {
      $HTML = str_replace('<div id="emptyErrorList"></div>','<div id="errorList">'.$_SESSION['error'].'</div>',$HTML);
      unset($_SESSION['error']);
    }
    
    // Apre file html
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 5);
?>