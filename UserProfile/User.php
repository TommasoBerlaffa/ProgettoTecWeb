<?php
    // User contiene dati utente

    require_once "../PHP/ConnectionToDatabase.php";
    use _Database\Database;
    // Non servono controlli su Login perchè vengono fatti da UserProfile.php

    // Ottengo Valori da Pagina Statica 
    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);
    
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}"," User Informations",$HTML);
    
    // Ottiene Valori Utente da SQL
    // Query del tipo SELECT * FROM users WHERE Code_user = $_SESSION['Code_User'];
    $DbAccess = new Database();
    $DbAccess->ConnectToDb();
    session_start();
    //$QueryResult =$DbAccess->getUser($_SESSION['Code_User']);
    $Query = $DbAccess->getUser($_SESSION['myValue']) ;
    $QueryResult = $Query->fetch_assoc();
    // Creo Content (da riempire con SQL)
    // Da sistemare con CSS, Picture deve essere un'immagine
    // <img src="..\IMG\Value" alt="Profile Picture" width="256" height="256">
    $content = "<div id=\"content\">
                        <h2> User : ". $QueryResult["Nickname"] ."</h2>
                        <p> Name & Surname : ". $QueryResult["Name"] ."  ". $QueryResult["Surname"] ." </p>
                        <p> Status : ". $QueryResult["Status"] ." </p>
                        <p> Birthday : ". $QueryResult["Birth"] ." </p>
                        <p> Email : ". $QueryResult["Email"] ." </p>
                        <p> Nationality : ". $QueryResult["Nationality"] ." </p>
                        <p> City : ". $QueryResult["City"] ." </p>
                        <p> Address : ". $QueryResult["Address"] ." </p>
                        <p> Phone Number : ". $QueryResult["Phone"] ." </p>
                        <p> Picture : ". $QueryResult["Picture"] ." </p>
                        <p> Link to a Curriculum : ". $QueryResult["Curriculum"] ." </p>
                        <p> Description : ". $QueryResult["Description"] ." </p>
                        <p> Creaction Day : ". $QueryResult["Creation"] ." </p>
                </div>";
    // 
    // Rimpiazza Valori su file html
    $HTML = str_replace("<div id=\"content\"></div>",$content,$HTML);
    // Stampo File Modificato
    echo $HTML;
?>