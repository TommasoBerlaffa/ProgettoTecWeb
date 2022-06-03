<?php

require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
  // Ottengo Valori da Pagina Statica
  $url = '..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'ViewJobOld.html';
  $HTML = file_get_contents($url);

  $DbAccess = new DBAccess();
  $conn = $DbAccess->openDBConnection();

  $HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
    <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);

  if($conn) {
    $index = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
    $row = $DbAccess->getJob($index,false);
    //Se trova risultato
    if($row) {              
      $HTML = str_replace("{{ Title }}",trim($row["Title"]),$HTML);
      $HTML = str_replace("{{ Description }}",trim($row["Description"]),$HTML);
      $HTML = str_replace("{{ Payment }}",trim($row["Payment"]),$HTML);
      $HTML = str_replace("{{ Status }}",trim($row["Status"]),$HTML);
      $HTML = str_replace("{{ Tipology }}",trim($row["Tipology"]),$HTML);
      $HTML = str_replace("{{ Date }}",trim($row["Date"]),$HTML);
      $HTML = str_replace("{{ Expiring }}",trim($row["Expiring_time"]),$HTML);
      $HTML = str_replace('{{ Creator }}','<a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.trim($row["Code_user"]).'"><abbr title="Information">Info</abbr> on the Creator</a>',$HTML);
      
      $feedback = $DbAccess->getJobReview($index);
      if(!$feedback) {
        if( isset($_SESSION['user_ID']) && $_SESSION['user_ID'] == $row['Code_user']) {
          $urlForm = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormFeedback.html';
          $HTMLForm = file_get_contents($urlForm);

          $HTML = str_replace( '<div id="feedback"></div>', $HTMLForm ,$HTML);
          // Aggiungo form per aggiungere feedback
        }
        else // Caso in cui non c'è feedback e non ho l'autorità per aggiungerlo (non sono creatore dell'offerta di lavoro)
          $HTML = preg_replace('/<div id="feedback"><\/div>/','<div id="content"><p> No Info are currently available about this specific Job</p></div>',$HTML);
      }
      else {
        $User = $DbAccess->getUser(trim($feedback["C_Rew"]));
        $HTMLFeedback = '<div id="feedback">
          <h1 class="reviewUser">Review by <a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.$User["Code_user"].'">'.$User["Nickname"].'</a></h1>
          <p class="rating">Rating : '. trim($feedback["Stars"]) .' /5</p>
          <p class="date"> Date :'.trim($feedback["Date"]). '</p>
          <div class="comment">
          <p> Comment :</p>
          <p id="feedbackDesc">'.trim($feedback["Comments"]).'</p>
          </div>';
        
        $HTMLFeedback.='</div>';
        $HTML = str_replace('<div id="feedback"></div>',$HTMLFeedback,$HTML);
      }
    } //Se non trova un risultato
    else {
      $HTML = str_replace( '{{ Title }}', 'No Info Available' ,$HTML);
      $HTML = preg_replace('/<div id="JobInfo">.*?.<\/div><\/div><div id="feedback"><\/div>/','<div id="content"><p> No Info are currently available about this specific Job</p></div>',$HTML);
      //$HTML = str_replace('<div id="JobInfo">'.*?.'</div>','<div id="content"><p> There is nothing to be seen here </p></div>',$HTML);
    }
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
  
  echo $HTML;    
}
else
  header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");    

?>