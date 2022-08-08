<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
  // Pagina da aprire quando si entra su UserProfile.php senza aver cliccato buttons //
  
  if(isset($_SESSION['user_Username'])) {

    $urlWelcome= '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'Welcome.html';;
    $WelcomeMessage = file_get_contents($urlWelcome);
    $WelcomeMessage = str_replace('{{link}}','..' .DIRECTORY_SEPARATOR. 'PHP' .DIRECTORY_SEPARATOR. 'FAQ.php',$WelcomeMessage);
    $WelcomeMessage = str_replace('{{user}}',$_SESSION["user_Username"],$WelcomeMessage);  
    
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);

    $adminActions = '';
    if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) 
    {
      $adminActions .= '<a id="AdminArea" href="..' . DIRECTORY_SEPARATOR . 'PHP'. DIRECTORY_SEPARATOR .'AdminHistory.php">Go to the secret admin page</a>';  
    }
    else {
      $adminActions .= '';
    }

    $WelcomeMessage = str_replace('<admin/>',$adminActions,$WelcomeMessage);

    $HTML = str_replace('{{ SubPage }}','Welcome Page ',$HTML);

    $HTML = str_replace('<div id="content"></div>',$WelcomeMessage,$HTML);

    $HTML = str_replace('</javascript>','',$HTML);  
    
    echo $HTML;
  }
  else{
	header("Location:Login.php");
	exit();
}
exit();
?>