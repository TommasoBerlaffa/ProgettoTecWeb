<?php
  // Work deve contenere tutti I lavori creati dall'utente ( Passati )
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start(); 

  if(isset($_SESSION['user_Username'])) {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Your Job Offers",$HTML);

    $HTML = str_replace('<a href="../PHP/UserProfile.php?section=2">','<a href="../PHP/UserProfile.php?section=2" class="selected">',$HTML);

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();    
    
    if($conn) {
      // Ottiene Valori da Query - Past Jobs
      $Result = $DbAccess->getPastJobListbyCreator($_SESSION['user_ID']);

      if($Result) {
        $table = "";
        $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
        $HTMLTable ='<div id="content">' . file_get_contents($urlTable);
        $HTMLTable = str_replace('{{ caption }}','The table in page Your Job Offer displays all the job offer you created and are already terminated.
        You can click on a job title to display more informations',$HTMLTable);
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
        $HTMLTable = '<div id="content"><p class="tableEmpty">You currently have no past job offer that are terminated. If you want to create a job offering history, you should start by creating some jobs offer.
        Feel free to check out <a href="">create a job offer</a></p></div>';

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