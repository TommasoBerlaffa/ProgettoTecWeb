<?php

    // Non servono controlli su Login perchè vengono fatti da UserProfile.php

    // Ottengo Valori da Pagina Statica 
    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}"," User Settings",$HTML);

    $urlExtra = '../UserProfile/Settings.html';
    $HTMLExtra = "<div id=\"content\">".file_get_contents($urlExtra)."</div>";

    $HTML = str_replace("<div id=\"content\"></div>",$HTMLExtra,$HTML);
    // Apre file html
    echo $HTML;
?>