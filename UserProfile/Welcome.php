<?php
    // Pagina da aprire quando si entra su UserProfile.php senza aver cliccato buttons //
    session_start();
    
    if(isset($_SESSION['user_Username']))
    {
        $WelcomeMessage= '<div id="content">
                        <h1>'. $_SESSION["user_Username"] . ',  Welcome  to the User Profile page!</h1>
                        <p style="text-align:center">Use the Lateral Navigation bar to check out different informations regarding your account!</p>
                        </div>';
        //".DIRECTORY_SEPARATOR."
        $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
        $HTML = file_get_contents($url);

        $HTML = str_replace('{{ SubPage }}','Welcome Page ',$HTML);

        $HTML = str_replace('<div id="content"></div>',$WelcomeMessage,$HTML);

        echo $HTML;
    }
    else
        header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php');

?>