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

    $HTML = str_replace('<a href="../PHP/UserProfile.php?section=4">','<a href="../PHP/UserProfile.php?section=4" class="selected">',$HTML);

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();

    // Crea una table da aggiungere al file HTML

    $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableBid.html';
    $HTMLTable ='<div id="content">' . file_get_contents($urlTable);
    $HTMLTable = str_replace('{{ caption }}','The page Bids display all your current Bids.
    Click on a job Title to display more infos!',$HTMLTable);

    $HTMLTable2 = file_get_contents($urlTable);
    $HTMLTable2 = str_replace('{{ caption }}','This table shows all the your current bid to jobs  ',$HTMLTable2);

    if($conn)
    {
      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],false);
      $table = "";
      if($Result) {
        foreach($Result as $row ) {
          $table .= '<tr>
          <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.trim($row['Code']).'">'.trim($row['Title'] ).' </a></td>
          <td>'. trim($row['Status'] ).'</td>
          <td>'. trim($row['Tipology'] ).'</td>
          </tr>';
        }
        $HTMLTable = str_replace('{{ value }}',$table,$HTMLTable);
      }
      else
      {
        $HTMLTable = str_replace('{{ value }}',$table,$HTMLTable);
        $HTMLTable .= '<p>No content to show</p>';
      }


      $Result2 = $DbAccess->getJobListbyCreator($_SESSION['user_ID']);
      $table2 = "";
      if($Result2){
        foreach ($Result2 as $row) {
          $table2 .= '<tr>
            <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
            <td>'.trim($row["Status"]).'</td>
            <td>'.trim($row["C"]).'</td>
          </tr>';
        } 
        $HTMLTable2 = str_replace('{{ value }}',$table2,$HTMLTable2);
      }
      else
      {
        $HTMLTable2 = str_replace('{{ value }}',$table2,$HTMLTable2);
        $HTMLTable2 .= '<p>No content to show</p>';
      }

    }           
    else
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');            
    
    $HTMLTable .= $HTMLTable2;
    $HTMLTable .= '</div>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 4);
?>