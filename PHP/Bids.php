<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
  // BidHistory deve contenere tutte le Bids dell'utente ( Passate che hanno avuto successo )
  require_once 'DBAccess.php';
  
  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica  
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Your Bids",$HTML);

    $HTML = str_replace('<li><a href="../PHP/Bids.php"><img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Your Bids</span></a></li>',
    '<li class="selected">
    <img src="../IMG/Icons/bid.png" class="icons" alt=""><span class="sidebarText"> Your Bids</span>
    </li>',$HTML);
  
    $DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}

    $HTMLTable ='
    <div id="content">
      <div id="intro">
        <p><em>Your Bids</em> is the place where you can check out all your bids, both past bids on succesfull jobs and current bids.</p>
      </div><a class="goTop" href="#{{value}}">Skip the first table</a>';
    
    $NewBid = $DBAccess->getUserJobs($_SESSION['user_ID'],false);
    
    if($NewBid) {
      $tableNewBid = "";
      $urltableNewBid = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableBid.html';
      $HTMLtableNewBid = file_get_contents($urltableNewBid);
      $HTMLtableNewBid = str_replace('{{ caption }}','This table in page Bids displays all your current Bids.
      You can check all the job and bid info by clicking on the job title.',$HTMLtableNewBid);
      foreach($NewBid as $row ) {
        $date1 = date_create();
        $date2 = date_create($row['Expiring']);
        $tableNewBid .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.trim($row['Code']).'">'.trim($row['Title'] ).' </a></td>
        <td>'.date_diff($date2,$date1)->format('%a <abbr title="days">d</abbr> %h <abbr title="hours">h</abbr> %i <abbr title="minutes">m</abbr>') .'</td>
        <td>'. trim($row['Tipology'] ).'</td>
        </tr>';
      }
      $HTMLtableNewBid = str_replace('{{ value }}',$tableNewBid,$HTMLtableNewBid);
      $HTMLtableNewBid .= '<a href="#" class="goTop">Go back to the top</a>';
    }
    else
      $HTMLtableNewBid = '<p class="tableEmpty">You currently have no active bids. You can check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'FindJob.php">Find a Job Offer</a> and start bidding now</p>';
    
    $HTMLTable .= $HTMLtableNewBid;

    $OldBid = $DBAccess->getUserJobs($_SESSION['user_ID'],true);
    if($OldBid) {
      $TableOldBid = "";
      $urlTableOldBid = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJob.html';
      $HTMLTableOldBid =file_get_contents($urlTableOldBid);
      $HTMLTableOldBid= str_replace('{{id}}','tablePastBids',$HTMLTableOldBid);
      $HTMLTableOldBid = str_replace('{{ caption }}','This table displays all your past bids.
      You can click on a job title to display more information!',$HTMLTableOldBid);
      foreach($OldBid as $row ) {
        trim($row["Payment"]) > 0 ? $res = 'Once' : $res = 'Hourly';
        $TableOldBid .= '<tr>
        <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
        <td>'.trim($row["Status"]).'</td>
        <td>'.trim($row["Tipology"]).'</td>
        <td>'.$res.'</td>
        </tr>';
      } 
      $HTMLTableOldBid = str_replace('{{ value }}',$TableOldBid,$HTMLTableOldBid);
      $HTMLTable = str_replace('{{value}}','tablePastBids',$HTMLTable);
    }
    else {
      $HTMLTableOldBid = '<p class="tableEmpty">You currently have no past bids. If you want to start making your bid history, you should check <a href="..'.DIRECTORY_SEPARATOR.'PHP'. DIRECTORY_SEPARATOR.'FindJob.php">Find a Job Offer</a> and try your first bid</p>';
      $HTMLTable = str_replace('{{value}}','tablePastBids',$HTMLTable);
    }
      
	$DBAccess->closeDBConnection();
	
    $HTMLTable .= $HTMLTableOldBid;

    $HTMLTable .= '</div><a href="#" class="goTop">Go back to the top</a>';
    // Rimpiazza Valori su file html
    $HTML = str_replace('<div id="content"></div>',$HTMLTable,$HTML);

    $HTML = str_replace('</javascript>','',$HTML);  
    
    // Stampo File Modificato
    echo $HTML;
  }
  else{
	$_SESSION['request'] = $_SERVER['REQUEST_URI'];
    header('Location:Login.php');
  }
?>