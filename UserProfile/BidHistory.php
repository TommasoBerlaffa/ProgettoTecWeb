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

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=3"><img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Bids History</span></a></li>',
    '<li class="selected">
      <img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Bids History</span>
    </li>',$HTML);
  
    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();

    if($conn) {
      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],true);
      if($Result) {
        $table = "";
        $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
        $HTMLTable ='<div id="content"><div id="intro"><p><em>Bid History</em> is the place where you can check out all the old bids you placed on job offers</p></div>' . file_get_contents($urlTable);
        $HTMLTable = str_replace('{{ caption }}','The table in page Bid History display all your past bids.
        You can click on a job title to display more information!',$HTMLTable);
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
        $HTMLTable = '<div id="content"><p class="tableEmpty">You currently have no past bids. If you want to start making your bid history, you should check <a href="">Find a Job Offer</a> and try your first bid</p>';


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