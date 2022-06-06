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

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=5"><img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span></a></li>',
    '<li class="selected">
      <img src="../IMG/Icons/setting.png" class="icons" alt=""><span class="sidebarText"> User Setting</span>
    </li>',$HTML);
  

    $urlExtra = '..'. DIRECTORY_SEPARATOR .'UserProfile'. DIRECTORY_SEPARATOR .'Settings.html';
    // Mettere i valori dentro la Form
    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();
    $HTMLExtra = '<div id="content">'.file_get_contents($urlExtra).'<div id="errorList"></div></div>';

    if($conn) {
      $Result = $DbAccess->getUser($_SESSION['user_ID']);
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
    }
    else
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');

    $HTML = str_replace('<div id="content"></div>',$HTMLExtra,$HTML);
    // Spazione vuoto
    if(isset($_SESSION['error'])) {
      $HTML = preg_replace('/(?<=<div id=\"errorList\">)((\n|.)*)(?=<\/div>)/',$_SESSION['error'],$HTML);
      unset($_SESSION['error']);
    }
    
    // Apre file html
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 5);
?>