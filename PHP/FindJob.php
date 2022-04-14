<?php
  require_once 'DBAccess.php';

  // Inizio Sessione 
  session_start();

  // Variabili pagina HTML e Switch
  $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html';
  $HTML = file_get_contents($url);
  $HTMLContent ='';

  //Controllo se variabile sessione è presente 
  if(isset($_SESSION['user_Username']))
  {
    $HTML = str_replace('<createjob/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'CreateJob.php">
              <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.svg" class="icons"> Create an Offer </a></li>',$HTML);
    $HTMLContent = '<li class="right"><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';

  }
  else
  {
    $HTML = str_replace('<createjob/>','',$HTML);
    $HTMLContent = '<li class="right"><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Signup.php">
            <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'book.svg" class="icons"> Sign up </a></li>
            <li class="right"><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php">
            <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'login.svg" class="icons"> Login </a></li>';
  }

  // Cambio Pagina
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  /*
  $DBAccess = new DBAccess();
  if( isset($_POST['Min_pay']))
  {
    $P_Min_Value = filter_var($_POST['Min_pay'], FILTER_VALIDATE_INT);
  }
  else
    $P_Min_Value = null;

  echo $P_Min_Value ;

  $result = $DBAccess->getJobs($P_Min_Value);
  
  $HTMLJobs ='';
  
  if($result)
  {
    $HtmlContent .='<div id="content"><table class="content">
            <tr>
              <th> Title </th>
              <th> Status </th>
              <th> Tipology </th>
              <th> Payment </th>
              <th> Min Payment </th>
              <th> Max Payment </th>
            </tr>';
    foreach($result as $row)
    {
      $HtmlContent .='<tr>';
      $HtmlContent .='<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_Job"].'">'.$row["Title"].'</a></td>';
      $HtmlContent .= '<td>'.trim($row["Status"]).'</td>';
      $HtmlContent .= '<td>'.trim($row["Tipology"]).'</td>';
      $HtmlContent .= '<td>'.trim($row["Payment"]).'</td>';
      $HtmlContent .= '<td>'.trim($row["P_min"]).'</td>';
      $HtmlContent .= '<td>'.trim($row["P_max"]).'</td>';
      $HtmlContent .='</tr>';
    }
    $HtmlContent .='</div>';
  }
  else
  {
    echo  'No Jobs Currently Available';
  }
  
*/
  //$HTML = str_replace('<div id="content"></div>',$HTMLJobs,$HTML);
  // Apertura Pagina
  echo $HTML;

?>
