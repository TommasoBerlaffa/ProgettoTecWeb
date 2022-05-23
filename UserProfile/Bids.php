<?php

  // Bids deve contenere tutte le Bids correnti 
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica  
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Current Bids",$HTML);

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();
    
    $table = '<div id="content">
            <table class="content">
            <caption>The page Bids display all your current Bids.
            Click on a job Title to display more infos! </caption>
              <thead><tr>
                <th> Title </th>
                <th> Status </th>
                <th> Tipology </th>
              </tr></thead><tbody>';
      

    if($conn)
    {
      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],false);
      if($Result) {
        
        // Rimpiazza Valori su file html  
        foreach($Result as $row ) {
          $table .= '<tr>
          <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.trim($row['Code']).'">'.trim($row['Title'] ).' </a></td>
          <td>'. trim($row['Status'] ).'</td>
          <td>'. trim($row['Tipology'] ).'</td>
          </tr>';
        }
        $table .='</tbody></table>';
      }
      else
      {
        $table .='</tbody></table><p>No Data Currently Available</p>';
      }

      $table.='<table class="content">
          <caption>This table shows all the your current jobs  </caption>
            <thead><tr>
              <th> Title </th>
              <th> Status </th>
              <th> Bids </th>
            </tr></thead><tbody>';
      $Result = $DbAccess->getJobListbyCreator($_SESSION['user_ID']);
      if($Result){
        foreach ($Result as $row) {
          $table .= '<tr>';
          $table .= '<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>';
          $table .= '<td>'.trim($row["Status"]).'</td>';
          $table .= '<td>'.trim($row["C"]).'</td>';
          $table .= '</tr>';
        } 
        $table .='</tbody></table></div>';
      }
      else
      {
        $table .='</tbody></table><p>No Data Currently Available</p></div>';
      }

    }                      
    

    $HTML = str_replace('<div id="content"></div>',$table,$HTML);
  
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 4);

?>