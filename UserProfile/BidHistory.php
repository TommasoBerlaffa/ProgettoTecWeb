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

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();
    
    if($conn) {
    
      $table = '<div id="content">
        <table class="content">
          <caption>The page Bid History display all your successful Bids.
          Click on a job Title to display more infos! </caption>
          <thead>
            <tr>
              <th scope="col"> Title </th>
              <th scope="col"> Status </th>
              <th scope="col"> Tipology </th>
              <th scope="col"> Payment </th>
            </tr>
          </thead>
          <tbody>';  

      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],true);
      if($Result) {
        foreach($Result as $row ) {
          $table .= '<tr>
            <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
            <td>'.trim($row["Status"]).'</td>
            <td>'.trim($row["Tipology"]).'</td>
            <td>'.trim($row["Payment"]).'</td>
            </tr>';
        } 
        $table .='</tbody></table></div>';
      }
      else
        $table .='</tbody></table><p>No Data Currently Available</p></div>';
    }    
    else
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');               

    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$table,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 3);
?>