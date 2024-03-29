<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
  // Work deve contenere tutti I lavori creati dall'utente ( Passati )
  require_once 'DBAccess.php';

  if(isset($_SESSION['user_Username'])) {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Your Job Offers",$HTML);

    $HTML = str_replace('<li><a href="../PHP/Work.php"><img src="../IMG/Icons/work.png" class="icons" alt=""><span class="sidebarText"> Your Job Offers</span></a></li>',
    '<li class="selected">
    <img src="../IMG/Icons/work.png" class="icons" alt=""><span class="sidebarText"> Your Job Offers</span>
    </li>',$HTML);
  

    $DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    
    
    $HTMLTable ='
    <div id="content">
      <div id="intro">
        <p><em>Your Job Offer</em> is the place where you can check out all the Job Offer you created, both past and current.</p>
        </div><a class="goTop" href="#{{value}}">Skip the first table</a>';
    
    // Ottiene Valori da Query - Current Jobs
    $CurrentJob = $DBAccess->getJobListbyCreator($_SESSION['user_ID']);

    if($CurrentJob){
       $tableJob = "";
      $urltableJob = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJobWBid.html';
      $HTMLtableJob = file_get_contents($urltableJob);
      $HTMLtableJob = str_replace('{{ caption }}','This table shows all your current jobs with the number of bids that are placed on them. 
      You can click on the job title to display more informations.',$HTMLtableJob);
      

      foreach ($CurrentJob as $row) {
        $date1 = date_create();
        $date2 = date_create($row['Expiring']);
        $finalDate = $date2>$date1 ? date_diff($date2,$date1)->format('%a <abbr title="days">d</abbr> %h <abbr title="hours">h</abbr> %i <abbr title="minutes">m</abbr>') : 'This offer is over';
        $tableJob .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'. $finalDate  .'</td>
        <td>'.trim($row["C"]).'</td>
        </tr>';
      } 
      $HTMLtableJob = str_replace('{{ value }}',$tableJob,$HTMLtableJob);
      $HTMLTable .= $HTMLtableJob;
    }
    else
      $HTMLTable .= '<p class="tableEmpty">You currently have no active job. If you want to make a new job offer, feel free to check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'LoadCreatejob.php">create a Job Offer</a></p>';


    $HTMLTable .= '<a class="goTop" href="#">Go back to the top</a>';
    // Ottiene Valori da Query - Past Jobs
    $PastJob = $DBAccess->getPastJobListbyCreator($_SESSION['user_ID']);
	$DBAccess->closeDBConnection();

    if($PastJob) {
      $tablePastJob= "";
      $urltablePastJob= '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
      $HTMLtablePastJob= file_get_contents($urltablePastJob);
      $HTMLtablePastJob= str_replace('{{id}}','tableJob',$HTMLtablePastJob);
      $HTMLtablePastJob= str_replace('{{ caption }}','This table displays all the job offer you created and are already terminated.
      You can click on the job title to show more informations.',$HTMLtablePastJob);
      foreach ($PastJob as $row) {
        trim($row["Payment"]) == 0 ? $res = 'Once' : $res = 'Hourly';
        $tablePastJob.= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'.trim($row["Status"]).'</td>
        <td>'.trim($row["Tipology"]).'</td>
        <td>'.$res.'</td>
        </tr>';
      } 
      $HTMLtablePastJob= str_replace('{{ value }}',$tablePastJob,$HTMLtablePastJob);
      $HTMLTable .= $HTMLtablePastJob;
      $HTMLTable = str_replace('{{value}}','tableJob',$HTMLTable);
    }
    else {
      $HTMLTable .= '<p class="tableEmpty">You currently have no past job offer that are terminated. If you want to create a job offering history, you should start by creating some jobs offer.
      Feel free to check out <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'LoadCreatejob.php">create a job offer</a></p>';
      $HTMLTable = str_replace('{{value}}','tableJob',$HTMLTable);
    }


    $HTMLTable .= '</div><a class="goTop" href="#">Go back to the top</a>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);
    $HTML = str_replace('</javascript>','',$HTML);  
    // Stampo File Modificato
    echo $HTML;
  }
  else{
	$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
    header('Location:Login.php');
  }
  exit();
?>