<?php
  // Work deve contenere tutti I lavori creati dall'utente ( Passati )
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start(); 

  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Work History",$HTML);
    
    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();

    // Crea una table da aggiungere al file HTML
    $table = '<div id="content">
          <table class="content">
          <caption>The page Work History display all the Job offer you created.
            Click on a job Title to display more infos! </caption>
            <thead><tr>
              <th> Title </th>
              <th> Status </th>
              <th> Tipology </th>
              <th> Payment </th>
            </tr></thead><tbody>';

    if($conn){
      // Ottiene Valori da Query - Past Jobs
      // Query : SELECT * FROM past_jobs WHERE Code_user = $_SESSION['Code_User'];
      $Result = $DbAccess->getPastJobListbyCreator($_SESSION['user_ID']);
      if($Result){
        foreach ($Result as $row) {
          $table .= '<tr>';
          $table .= '<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>';
          $table .= '<td>'.trim($row["Status"]).'</td>';
          $table .= '<td>'.trim($row["Tipology"]).'</td>';
          $table .= '<td>'.trim($row["Payment"]).'</td>';
          $table .= '</tr>';
        } 
        $table .='</tbody></table></div>';
      }
      else
      {
        $table .='</tbody></table><p>No Data Currently Available</p></div>';
      }
    }    
    else
    {
      $table .='</tbody></table><p>Cannot Connect Correctly</p></div>';
    }  

    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$table,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 2);
?>