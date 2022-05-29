<?php
  // Work deve contenere tutti I lavori creati dall'utente ( Passati )
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start(); 

  if(isset($_SESSION['user_Username'])) {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Work History",$HTML);

    $HTML = str_replace('<a href="../PHP/UserProfile.php?section=2">','<a href="../PHP/UserProfile.php?section=2" class="selected">',$HTML);

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();

    // Crea una table da aggiungere al file HTML

    $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
    $HTMLTable ='<div id="content">' . file_get_contents($urlTable);
    $HTMLTable = str_replace('{{ caption }}','The page work history displays all the job offer you created.
    Click on a job title to display more informations!',$HTMLTable);
    
    if($conn) {
      // Ottiene Valori da Query - Past Jobs
      $Result = $DbAccess->getPastJobListbyCreator($_SESSION['user_ID']);
      $table = "";
      if($Result) {
        foreach ($Result as $row) {
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
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 2);
?>