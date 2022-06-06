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

    $HTML = str_replace('<li><a href="../PHP/UserProfile.php?section=4"><img src="../IMG/Icons/history.png" class="icons" alt=""><span class="sidebarText"> Current Bids</span></a></li>',
    '<li class="selected">
      <img src="../IMG/Icons/history.png" class="icons" alt=""><span class="sidebarText"> Current Bids</span>
    </li>',$HTML);
  

    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();

    if($conn)
    {
      $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],false);
      
      if($Result) {
        $table = "";
        $urlTable = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableBid.html';
        $HTMLTable ='<div id="content">
        <div id="intro"><p><em>Current Bids</em> is the place where you can check out both your current bids and the bids on your job offers</p></div>' . file_get_contents($urlTable);
        $HTMLTable = str_replace('{{ caption }}','This table in page Bids displays all your current Bids.
        You can check all the job and bid info by clicking on the job title.',$HTMLTable);
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
        $HTMLTable = '<div id="content"><div id="intro"><p><em>Current Bids</em> is the place where you can check out both your current bids and the bids on your job offers</p></div><p class="tableEmpty">You currently have no active bids. If you want to make a bid for a Job, check <a href="">Find a Job Offer</a> to find a Job Offer</p>';

      $Result2 = $DbAccess->getJobListbyCreator($_SESSION['user_ID']);
     
      if($Result2){
        $table2 = "";
        $urlTable2 = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableJobWBid.html';
        $HTMLTable2 = file_get_contents($urlTable2);
        $HTMLTable2 = str_replace('{{ caption }}','This table shows all your current jobs with the number of bids that are placed on them. 
        You can click on the job title to show more informations.',$HTMLTable2);
        foreach ($Result2 as $row) {
          $table2 .= '<tr>
            <td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>
            <td>'.trim($row["Status"]).'</td>
            <td>'.trim($row["C"]).'</td>
          </tr>';
        } 
        $HTMLTable2 = str_replace('{{ value }}',$table2,$HTMLTable2);
        $HTMLTable .= $HTMLTable2;
      }
      else
        $HTMLTable .= '<div id="content"><p>You currently have no active job. If you want to make a new job offer, feel free to check <a href="">Create a Job Offer</a></p>';

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
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 4);
?>