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
          
      $feedback = $DbAccess->getJobReview($index);
      if(!$feedback) {
        if( isset($_SESSION['user_ID']) && $_SESSION['user_ID'] == $row['Code_user']) {
          $form = '<form class="forInput" action="../PHP/AddFeedback.php" method="post">
            <fieldset>
            <legend> Feedback </legend>
              <label for="star"> Stars :</label>
              <span class="forstars">
                <input type="radio" id="star1" class="stars" name="star" value="1" indeterminate>
                <input type="radio" id="star2" class="stars" name="star" value="2">
                <input type="radio" id="star3" class="stars" name="star" value="3">
                <input type="radio" id="star4" class="stars" name="star" value="4">
                <input type="radio" id="star5" class="stars" name="star" value="5">
              </span>
              <label for="comment"> Comment :  </label>
              <textarea id="comment" name="comment"> </textarea>
              <button type="submit">Add Feedback </button>
            </fieldset>
          </form>';

          $HTML = str_replace( '<div id="feedback"></div>', $form ,$HTML);
          // Aggiungo form per aggiungere feedback
        }
        else // Caso in cui non c'è feedback e non ho l'autorità per aggiungerlo (non sono creatore dell'offerta di lavoro)
          $HTML = preg_replace('/<div id="feedback"><\/div>/','<div id="content"><p> No Info are currently available about this specific Job</p></div>',$HTML);
      }
      else {
        $tableFeedback = '<div id="feedback">';
        foreach($feedback as $F) {
          $tableFeedback.= '<p class="rating">Rating : '. $F->getStars() .' /5</p>
            <p class="date"> Date :'.$F->getDateTime(). '</p>
            <div class="comment">
            <p for="feedbackDesc"> Comment :</p>
            <p id="feedbackDesc">'.$F->getComments().'</p>
          </div>';
        }
        $tableFeedback.='</div>';
        $HTML = str_replace('<div id="feedback"></div>',$tableFeedback,$HTML);
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