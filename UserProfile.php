<?php

    session_start();
    
    if(!isset($_SESSION["isValid"]) || !$_SESSION["isValid"]) {
        header("Location: index.php");
    }

    // Riempire il Content con PHP

    header ( "/HTML/UserProfile.html");
?>