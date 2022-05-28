<?php

require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
  // Ottengo Valori da Pagina Statica
  $url = '..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'ViewUser.html';
  $HTML = file_get_contents($url);

  $DbAccess = new DBAccess();
  $conn = $DbAccess->openDBConnection();
  
  $HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
    <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  
  if($conn) {
    if($_GET['Code_User']) {
      $index = filter_var($_GET['Code_User'], FILTER_VALIDATE_INT);
      $row = $DbAccess->getUser($index);
      //Se trova risultato
      if($row) {                
        $HTML = str_replace("{{ User }}",trim($row["Nickname"]),$HTML);
        $HTML = str_replace("{{ Name }}",trim($row["Name"]),$HTML); 
        $HTML = str_replace("{{ Surname }}",trim($row["Surname"]),$HTML);
        $HTML = str_replace("{{ Picture }}",trim($row["Picture"]),$HTML);
        $HTML = str_replace("{{ Status }}",trim($row["Status"]),$HTML);
        $HTML = str_replace("{{ Birth }}",trim($row["Birth"]),$HTML);
        $HTML = str_replace("{{ Email }}",trim($row["Email"]),$HTML);
        $HTML = str_replace("{{ Nationality }}",trim($row["Nationality"]),$HTML);
        $HTML = str_replace("{{ City }}",trim($row["City"]),$HTML);
        $HTML = str_replace("{{ Curriculum }}",$row["Curriculum"]?trim($row["Curriculum"]) : "Not Available",$HTML);
        $HTML = str_replace("{{ Description }}",$row["Description"]?trim($row["Description"]) : "Not Available",$HTML);   
        
        $Review = $DbAccess->getUserReviewList($_SESSION['user_ID']);
        
        if($Review) {
          $content = '<div id="feedbacks"><div class="headchapter"><h2 class="chapter"> Your Reviews : </h2></div>';
          foreach($Review as $R)
          {
            // Replace Review with link to the job info
            $content .= '<div class="review">
              <p class="comment">' .$R->getComments() .' </p>
              <p class="star">Rating :'.$R->getStars() .'/5</p>
              <p class="date">Date : '.$R->getDateTime().' </p> 
              </div>';
          }
          $content .= '</div>';

          $HTML = str_replace('<div id="feedbacks"></div>',$content,$HTML);
        } //Se non trova un risultato
      }
      else
      {
        $HTML = str_replace( '{{ User }}', 'Unknown User' ,$HTML);
        // (?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)
        $HTML = preg_replace('/(?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)/',
            ' <div id="content">
                <p> No Info are currently available about this specific User</p>
              </div>',$HTML);
        //$HTML = str_replace('<div id="JobInfo">'.*?.'</div>','<div id="content"><p> There is nothing to be seen here </p></div>',$HTML);
      }
    }
    else {
      header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Index.php");
    }
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
  echo $HTML;    
}
else
  header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");    

?>