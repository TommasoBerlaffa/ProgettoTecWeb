<?php
  // User contiene dati utente
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();

  if(isset($_SESSION['user_Username'])) {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","User Informations",$HTML);
    // Cambio Colore Sidebar Selected
    $HTML = str_replace('<li><a href="../PHP/User.php"><img src="../IMG/Icons/info.png" class="icons" alt=""><span class="sidebarText"> User Profile</span></a></li>',
      '<li class="selected">
      <img src="../IMG/Icons/info.png" class="icons" alt=""><span class="sidebarText"> User Profile</span>
      </li>',$HTML);
    
    $content='<div id="content"><div id="intro">
      <p><em>User Profile</em> is the place where you can see your own informations and your 3 latest reviews.</p>
    </div>';
    $adminActions = '';
    if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) 
      $adminActions .= '<a id="AdminArea" href="..' . DIRECTORY_SEPARATOR . 'PHP'. DIRECTORY_SEPARATOR .'AdminHistory.php">Go to the secret admin page</a>';  
    else 
      $adminActions .= '';

    $content.=$adminActions;
    // Ottiene Valori Utente da SQL
    // Query del tipo SELECT * FROM users WHERE Code_user = $_SESSION['Code_User'];
    $DBAccess = new DBAccess();
    if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}


    $Result = $DBAccess->getUser($_SESSION['user_ID']) ;

    if($Result) {
      $urlElement =  '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'User.html';
      $content .= file_get_contents($urlElement);
      $content = str_replace("{{Nickname}}",trim($Result["Nickname"]),$content);
      $content = str_replace("{{ProfilePic}}",'..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . trim($Result["Picture"]),$content);
      $content = str_replace("{{Name}}",trim($Result["Name"]),$content);
      $content = str_replace("{{Surname}}",trim($Result["Surname"]),$content);
      $content = str_replace("{{Status}}",trim($Result["Status"]),$content);
      $content = str_replace("{{Birth}}",trim($Result["Birth"]),$content);
      $content = str_replace("{{Email}}",trim($Result["Email"]),$content);
      $content = str_replace("{{Nationality}}",trim($Result["Nationality"]),$content);
      $content = str_replace("{{City}}",trim($Result["City"]),$content);
      $content = str_replace("{{Address}}",($Result["Address"] ? trim($Result["Address"]) : 'Not Available'),$content);
      $content = str_replace("{{Phone}}",($Result["Phone"] ? trim($Result["Phone"]) : 'Not Available'),$content);
      if($Result["Curriculum"])
		$content = str_replace("{{Curriculum}}",'<a href="'.trim($Result["Curriculum"]).'">'.trim($Result["Curriculum"]).'</a>',$content);
	  else
		$content = str_replace("{{Curriculum}}","Not Available",$content);
      $content = str_replace("{{Description}}",trim($Result["Description"]),$content);
      $content = str_replace("{{Creation}}",trim($Result["Creation"]),$content);
      
      $adminActions = '';
      if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) 
      {
        $adminActions .= '<a id="AdminArea" href="..' . DIRECTORY_SEPARATOR . 'PHP'. DIRECTORY_SEPARATOR .'AdminUser.php">Go to the secret admin page</a>';  
      }
      else {
        $adminActions .= '';
      }
      



      $Review = $DBAccess->getUserReviewList($_SESSION['user_ID'],3);
      
      if($Review) {
        $content .= '<div id="feedbacks"><div class="headchapter"><h1 class="chapter"> Your Latest Reviews : </h1></div>';
        
        foreach($Review as $R)
        {
          $User = $DBAccess->getUser(trim($R["JobGiver"]));
          // Replace Review with link to the job info
          $content .= '<div class="review">
            <h2 class="reviewTitle">Review by <a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.$User["Code_user"].'">'.$User["Nickname"].'</a></h2>
            <p class="star">Rating : '.trim($R["Stars"]) .'/5</p>
            <p class="date">Made on date : '.trim($R["Date"]).' </p> 
            <p class="comment">' .trim($R["Comments"]) .' </p>
            
            </div>';
        }
        
        $content .= '</div></div>';
      }
      else
        $content .= '<div id="feedbacks"><h1 class="noReviews">You have no reviews.</h1></div></div>';
    }
    else 
      $content .= '<div><p>There is no content to be shown. </p></div>';
    $DBAccess->closeDBConnection();
  }
  else  
    header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');

  // Rimpiazza Valori su file html
  $HTML = str_replace('<div id="content"></div>',$content,$HTML);

  $HTML = str_replace('</javascript>','',$HTML);  
  
  // Stampo File Modificato
  echo $HTML;
  
  
  ?>