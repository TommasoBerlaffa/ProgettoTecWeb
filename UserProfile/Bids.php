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
    $HTML = str_replace("{{ SubPage }}","Your Bids",$HTML);

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=3"><img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Your Bids</span></a></li>',
    '<li class="selected">
    <img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Your Bids</span>
    </li>',$HTML);
  
    $DbAccess = new DBAccess();

    $HTMLTable ='
    <div id="content">
      <div id="intro">
        <p><em>Your Bids</em> is the place where you can check out all your bids, both past bids on succesfull jobs and current bids.</p>
      </div>';
    
    $NewBid = $DbAccess->getUserJobs($_SESSION['user_ID'],false);
    
    if($NewBid) {
      $tableNewBid = "";
      $urltableNewBid = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableBid.html';
      $HTMLtableNewBid = file_get_contents($urltableNewBid);
      $HTMLtableNewBid = str_replace('{{ caption }}','This table in page Bids displays all your current Bids.
      You can check all the job and bid info by clicking on the job title.',$HTMLtableNewBid);
      foreach($NewBid as $row ) {
        $tableNewBid .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.trim($row['Code']).'">'.trim($row['Title'] ).' </a></td>
        <td>'. trim($row['Status'] ).'</td>
        <td>'. trim($row['Tipology'] ).'</td>
        </tr>';
      }
      $HTMLtableNewBid = str_replace('{{ value }}',$tableNewBid,$HTMLtableNewBid);

    }
    else
      $HTMLtableNewBid = '<p class="tableEmpty">You currently have no active bids. You can check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'FindJob.php">Find a Job Offer</a> and start bidding now</p>';
    
    $HTMLTable .= $HTMLtableNewBid;

    $OldBid = $DbAccess->getUserJobs($_SESSION['user_ID'],true);
    if($OldBid) {
      $TableOldBid = "";
      $urlTableOldBid = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
      $HTMLTableOldBid =file_get_contents($urlTableOldBid);
      $HTMLTableOldBid = str_replace('{{ caption }}','This table displays all your past bids.
      You can click on a job title to display more information!',$HTMLTableOldBid);
      foreach($OldBid as $row ) {
        $TableOldBid .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'.trim($row["Status"]).'</td>
        <td>'.trim($row["Tipology"]).'</td>
        <td>'.trim($row["Payment"]).'</td>
        </tr>';
      } 
      $HTMLTableOldBid = str_replace('{{ value }}',$TableOldBid,$HTMLTableOldBid);
    }
    else
      $HTMLTableOldBid = '<p class="tableEmpty">You currently have no past bids. If you want to start making your bid history, you should check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'FindJob.php">Find a Job Offer</a> and try your first bid</p>';

    $HTMLTable .= $HTMLTableOldBid;

    $HTMLTable .= '</div><a href="#header" class="goTop">Go back to the top</a>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);
    // Stampo File Modificato
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 3);
?>