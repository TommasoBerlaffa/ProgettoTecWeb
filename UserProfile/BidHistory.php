<?php

  // BidHistory deve contenere tutte le Bids dell'utente ( Passate che hanno avuto successo )
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();
  
  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica  
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Bids History",$HTML);

    $HTML = str_replace('<a href="../PHP/UserProfile.php?section=3">','<a href="../PHP/UserProfile.php?section=3" class="selected">',$HTML);

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();
    
    $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
    $HTMLTable ='<div id="content">' . file_get_contents($urlTable);
    $HTMLTable = str_replace('{{ caption }}','The page Bid History display all your successful Bids.
    Click on a job Title to display more infos!',$HTMLTable);

    if($conn) {
    
      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],true);
      $table = "";
      if($Result) {
        foreach($Result as $row ) {
          $table .= '<tr>
            <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
            <td>'.trim($row["Status"]).'</td>
            <td>'.trim($row["Tipology"]).'</td>
            <td>'.trim($row["Payment"]).'</td>
            </tr>';
        } 
        $HTMLTable = str_replace('{{ value }}',$table,$HTMLTable);
      }
      else
      {
        $HTMLTable = str_replace('{{ value }}',$table,$HTMLTable);  
        $HTMLTable .= '<p>No content to show</p>';
      }

    }    
    else
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');               

    $HTMLTable .= '</div>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 3);
?>