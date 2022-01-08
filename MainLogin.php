<?php

    session_start();
    if(isset($_SESSION["isValid"]) && $_SESSION["isValid"]) {
        header("Location: UserProfile.php");
        die;
    }

    $messaggio = isset($_GET["messaggio"]) ? "<p id=\"datiNonCorretti\">" . $_GET["messaggio"] . "</p>" : "";

    $content = array(
        "<msgErrore/>" => $messaggio
    );

    //echo Util::replacer("html/login.html", $content);


?>