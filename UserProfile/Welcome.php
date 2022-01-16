<?php
    // Pagina da aprire quando si entra su UserProfile.php senza aver cliccato buttons //
    session_start();


    $WelcomeMessage= "<div id=\"content\">
                    <h1>Welcome ". $_SESSION["Name"] . " to the User Profile page!</h1>
                    <p style=\"text-align:center\">Use the Lateral Navigation bar to check out different informations regarding your account!</p>
                    </div>";

    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);

    $HTML = str_replace("{{ SubPage }}","Welcome Page ",$HTML);

    $HTML = str_replace("<div id=\"content\"></div>",$WelcomeMessage,$HTML);

    echo $HTML;

?>