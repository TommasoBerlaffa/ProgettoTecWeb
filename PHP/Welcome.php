<?php
  // Pagina da aprire quando si entra su UserProfile.php senza aver cliccato buttons //
  session_start();
  
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

    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php');

?>