<?php

    require_once "ConnectionToDatabase.php";
    use _Database\Database;

    // Ottengo Valori da Pagina Statica 
    $url = '../HTML/ViewJobOld.html';
    $HTML = file_get_contents($url);

    $DbAccess = new Database();
    $DbAccess->ConnectToDb();

    session_start();

    $index = $_GET['Code_job'];
    $Query = $DbAccess->getOldJobs($index);
    $row = $Query->fetch_assoc();
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ Title }}",trim($row["Title"]),$HTML);
    $HTML = str_replace("{{ Description }}",trim($row["Description"]),$HTML);
    $HTML = str_replace("{{ Payment }}",trim($row["Payment"]),$HTML);
    $HTML = str_replace("{{ Status }}",trim($row["Status"]),$HTML);
    $HTML = str_replace("{{ Tipology }}",trim($row["Tipology"]),$HTML);
    $HTML = str_replace("{{ Date }}",trim($row["Date"]),$HTML);
    $HTML = str_replace("{{ Expiring }}",trim($row["Expiring_time"]),$HTML);

    if( isset($_SESSION['Code_user']) && $_SESSION['Code_user'] == $row['Code_User'])
    {
        // Aggiungo form per aggiungere feedback
    }

    $feedback = $DbAccess->getFeedback($index);
    // Code_user, Stars, Comments, Date

    $tableFeedback = "<div";

    if($row = $feedback->fetch_assoc() ){
        $tableFeedback .= " id=\"feedback\">";
        // U.Name, R.Stars, R.Comments, R.Date                   
        $tableFeedback .= "<h2> Review from ".trim($row['Name'])."</h2>";
        $tableFeedback .= "<h3> Date : ".trim($row['Date']).trim($row['Stars'])."</h3>";
        $tableFeedback .= "<p>".trim($row['Comments'])."</p>";
    }
    else
    {
        $tableFeedback .= ">";
    }

    $tableFeedback .= "</div>";
    $HTML = str_replace("<div id=\"feedback\"></div>",$tableFeedback,$HTML);

    // Rimpiazza Valori su file html
    //$HTML = str_replace("<div id=\"content\"></div>",$table,$HTML);
    // Stampo File Modificato
    echo $HTML;


?>