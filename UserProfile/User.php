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
                    <form class=\"forOutput\">
                        <fieldset>
                            <legend> User Info </legend>
                            <label> Status : ". $QueryResult["Status"] ." </label>
                            <label> Name : ". $QueryResult["Name"] ." </label>
                            <label> Surname : ". $QueryResult["Surname"] ." </label>
                            <label> Nickname : ". $QueryResult["Nickname"] ." </label>
                            <label> Birthday : ". $QueryResult["Birth"] ." </label>
                            <label> Email : ". $QueryResult["Email"] ." </label>
                            <label> Nationality : ". $QueryResult["Nationality"] ." </label>
                            <label> City : ". $QueryResult["City"] ." </label>
                            <label> Address : ". $QueryResult["Address"] ." </label>
                            <label> Phone Number : ". $QueryResult["Phone"] ." </label>
                            <label> Picture : ". $QueryResult["Picture"] ." </label>
                            <label> Curriculum : ". $QueryResult["Curriculum"] ." </label>
                            <label> Description : ". $QueryResult["Description"] ." </label>
                            <label> Creaction Day : ". $QueryResult["Creation"] ." </label>
                        </fieldset>
                    </form>
                </div>";
    // 
    // Rimpiazza Valori su file html
    $HTML = str_replace("<div id=\"content\"></div>",$content,$HTML);
    // Stampo File Modificato
    echo $HTML;
?>