<?php
  require_once 'DBAccess.php';

  // Inizio Sessione 
  session_start();

  // Variabili pagina HTML e Switch
  $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html';
  $HTML = file_get_contents($url);
  $HTMLContent ='';

  // Controllo se variabile sessione è presente 
  if(isset($_SESSION['user_Username']))
  {
    $HTML = str_replace('<createjob/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'CreateJob.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.svg" class="icons"> Create an Offer </a></li>',$HTML);
    $HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
  }
  else
  {
    $HTML = str_replace('<createjob/>','',$HTML);
    $HTMLContent = '
      <li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'login.svg" class="icons"> Login </a></li>
      <li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Signup.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'book.svg" class="icons"> Sign up </a></li>';
  }

  // Cambio Pagina
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  
  $DBAccess = new DBAccess();
  $conn = $DBAccess->openDBConnection();
  $HtmlContent='<div id="jobList">';

  if($conn)
  {
    isset($_POST["Tipology"]) ? $type =  $_POST["Tipology"] : $type= 'Any';
    isset($_POST["PayMin"]) ? $min =  $_POST["PayMin"] : $min='0';
    isset($_POST["Date"]) ? $date =  $_POST["Date"] : $date ='Any';
    $ndate=null;
    if($date!='Any')
    {
      if($date=="hour")
        $ndate=1;
      else if($date=="day")
        $ndate=24;
      else if($date=="week")
        $ndate=168;
      else if($date=="month")
        $ndate=744;
    }
    
	  $tag='';
    //$tags=$DBAccess->getAllTags();
    //$TagSelectUrl='..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TagSelect.html';
    //$HtmlTags=file_get_contents($TagSelectUrl);
	//
    //if($tags)
    //{
    //  $NewHtml='';
    //  foreach($tags as $t)
    //  {
    //    $NewHtml.= '<option class="tag">'.trim($t["Name"]).'</p>';
    //  }
	//
    //  $HtmlTags = str_replace('{{ Tags }}',$NewHtml,$HtmlTags);;
    //  
    //}
	//
    //$HTML = str_replace('<tags/>',$HtmlTags,$HTML);
    $result = $DBAccess->searchJob($type,$min,$ndate,$tag);
    if($result)
    {
      foreach($result as $row)
      {
        $HtmlContent .='<div class="job">
                      <p class="title"><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></p>
                      <p class="date">Date: '.trim($row["Date"]).'</p>
                      <p class="type">Tipology: '.trim($row["Tipology"]).'</p>
                      <p class="minPay">Minimum Pay: '.trim($row["P_min"]).'</p>
                      </div>';
      }
      $HtmlContent .='</div>';
    }
    else
    {
      $HtmlContent.='<p>No Jobs Currently Available</p></div>';
    }
    $HTML = str_replace('<div id="jobList"></div>',$HtmlContent,$HTML);
  }
 
  // Apertura Pagina
  echo $HTML;

?>
