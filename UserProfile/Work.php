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

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=2"><img src="../IMG/Icons/work.png" class="icons" alt=""><span class="sidebarText"> Your Job Offers</span></a></li>',
    '<li class="selected">
    <img src="../IMG/Icons/work.png" class="icons" alt=""><span class="sidebarText"> Your Job Offers</span>
    </li>',$HTML);
  

    $DbAccess = new DBAccess();

    
    
    $HTMLTable ='
    <div id="content">
      <div id="intro">
        <p><em>Your Job Offer</em> is the place where you can check out all the Job Offer you created, both past and current.</p>
      </div>';
    
    // Ottiene Valori da Query - Current Jobs
    $CurrentJob = $DbAccess->getJobListbyCreator($_SESSION['user_ID']);

    if($CurrentJob){

      $tableJob = "";
      $urltableJob = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJobWBid.html';
      $HTMLtableJob = file_get_contents($urltableJob);
      $HTMLtableJob = str_replace('{{ caption }}','This table shows all your current jobs with the number of bids that are placed on them. 
      You can click on the job title to display more informations.',$HTMLtableJob);
      foreach ($CurrentJob as $row) {
        $tableJob .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'.trim($row["Status"]).'</td>
        <td>'.trim($row["C"]).'</td>
        </tr>';
      } 
      $HTMLtableJob = str_replace('{{ value }}',$tableJob,$HTMLtableJob);
      $HTMLTable .= $HTMLtableJob;
    }
    else
      $HTMLTable .= '<p>You currently have no active job. If you want to make a new job offer, feel free to check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'Createjob.php">Create a Job Offer</a></p>';

    // Ottiene Valori da Query - Past Jobs
    $PastJob = $DbAccess->getPastJobListbyCreator($_SESSION['user_ID']);

    if($PastJob) {
      $tablePastJob= "";
      $urltablePastJob= '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
      $HTMLtablePastJob= file_get_contents($urltablePastJob);
      $HTMLtablePastJob= str_replace('{{ caption }}','This table displays all the job offer you created and are already terminated.
      You can click on the job title to show more informations.',$HTMLtablePastJob);
      foreach ($PastJob as $row) {
        $tablePastJob.= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'.trim($row["Status"]).'</td>
        <td>'.trim($row["Tipology"]).'</td>
        <td>'.trim($row["Payment"]).'</td>
        </tr>';
      } 
      $HTMLtablePastJob= str_replace('{{ value }}',$tablePastJob,$HTMLtablePastJob);
      $HTMLTable .= $HTMLtablePastJob;
    }
    else 
      $HTMLTable .= '<p class="tableEmpty">You currently have no past job offer that are terminated. If you want to create a job offering history, you should start by creating some jobs offer.
      Feel free to check out <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'Createjob.php">create a job offer</a></p>';

    $HTMLTable .= '</div>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 2);
?>