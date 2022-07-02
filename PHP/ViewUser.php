<?php

require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
  // Ottengo Valori da Pagina Statica
  $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'ViewUser.html';
  $HTML = file_get_contents($url);

  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
  	header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
  	exit;
  }
	
  $HTMLContent = '<li><a href="UserProfile.php">
  <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
  $HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
  
  if($_GET['Code_User']) {
    $index = filter_var($_GET['Code_User'], FILTER_VALIDATE_INT);
    $row = $DBAccess->getUser($index);
    //Se trova risultato
    if($row) {        
      $HTML = str_replace("{{ Nickname }}",trim($row["Nickname"]),$HTML);
      $HTML = str_replace("{{ Name }}",trim($row["Name"]),$HTML); 
      $HTML = str_replace("{{ Surname }}",trim($row["Surname"]),$HTML);
      $HTML = str_replace("{{ Picture }}",trim($row["Picture"]),$HTML);
      $HTML = str_replace("{{ Status }}",trim($row["Status"]),$HTML);
      $HTML = str_replace("{{ Phone }}",($row["Phone"] ? trim($row["Phone"]) : 'Not Available'),$HTML);
      $HTML = str_replace("{{ Email }}",trim($row["Email"]),$HTML);
      $HTML = str_replace("{{ Curriculum }}",$row["Curriculum"]?trim($row["Curriculum"]) : "Not Available",$HTML);
      $HTML = str_replace("{{ Description }}",$row["Description"]?trim($row["Description"]) : "Not Available",$HTML);   
    
      $Review = $DBAccess->getUserReviewList($index,5);
      
      if($Review) {
        $content = '<div id="viewUserFeedBack" class="box"><div class="headchapter">
            <h1 class="chapter">'.trim($row["Nickname"]). '\'s Reviews : </h1></div>';
        // Average Rating
        $average = $DBAccess->getUserReview($index);
        $content .= '<p>'.trim($row["Nickname"]).' average rating :'.trim($average["AvgStar"]) .'</p>';
        // Lista Rating
        foreach($Review as $R) {
          $User = $DBAccess->getUser(trim($R["JobGiver"]));
          // Replace Review with link to the job info
          $content .= '<div class="review">
            <h2 class="reviewTitle">Review by <a href="ViewUser.php?Code_User='.$User["Code_user"].'">'.$User["Nickname"].'</a></h2>
            <p class="star"><span>Date</span> : '.trim($R["Date"]) .'</p>
            <p class="date"><span>Rating</span> : '.trim($R["Stars"]).'/5 </p> 
          </div>';
        }
      $content .= '</div>';

      $HTML = str_replace('<div id="viewUserFeedBack" class="box"></div>',$content,$HTML);
      }
      else {
        $content = '<div id="viewUserFeedBack" class="box">This user has no reviews.</div>';
        $HTML = str_replace('<div id="viewUserFeedBack" class="box"></div>',$content,$HTML);
      } 

      $adminActions = '';
      if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) 
      {
        $urlContent = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormAdminUser.html';
        $adminActions .= file_get_contents($urlContent);  
        $adminActions = str_replace('<code/>',$index, $adminActions);
        $adminActions = str_replace('{{Ban}}',trim($row["Status"])=='Banned'? 'unban':'ban',$adminActions);
        $adminActions = str_replace('<ban/>',trim($row["Status"])=='Banned'? 'unban_':'',$adminActions);
        
      }
      else {
        $adminActions .= '';
      }
      
      $HTML = str_replace('<admin/>',$adminActions,$HTML);
      
    }
    else
    {
    $HTML = str_replace( '{{ User }}', 'Unknown User' ,$HTML);
    // (?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)
    $HTML = preg_replace('/(?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)/',
      '<div id="content">
        <p> No Info are currently available about this specific User</p>
      </div>',$HTML);
    //$HTML = str_replace('<div id="JobInfo">'.*?.'</div>','<div id="content"><p> There is nothing to be seen here </p></div>',$HTML);
    }
    echo $HTML;
  }
  else {
    header("Location:Index.php");
  }
  $DBAccess->closeDBConnection();
}
else
{
  $value = filter_var($_GET['Code_User'],FILTER_SANITIZE_NUMBER_INT);
  header("Location:Login.php?view=ViewUser&code=".$value);  
}
?>