<?php

    require_once "ConnectionToDatabase.php";
    use _Database\Database;

    // Controllo se sono stati selezionati valori ( Non dovrebbe servire con required )
    /*if(!isset($_POST["Email"], $_POST["Passwd"])) {
        // Errore Login
        header("Location: ../login.php");
        die;
    }*/

    // Ottiene in POST i valori per fare il Login
    $mail = $_POST["Email"];
    $password = $_POST["Passwd"];
    $cipher = md5($password);
    
    // Apre una connessione
    $dbAccess = new Database();
    $Connection = $dbAccess->ConnectToDb();

    // Se la connessione non si apre correttamente, apro Error 500
    if(!$Connection) {
        // Errore connessione non riuscita
        header("Location: ../error_500.php");
        die;
    }

    session_start();

    // Controllo che il Login sia corretto
    $result = $dbAccess->LoginMatch($mail, $cipher);
    // Chiudo la connessione
    $dbAccess->CloseConnection();

    $_SESSION["isValid"] = $result["isValid"];

    /* TO FIX LATER*/
    if($_SESSION["isValid"]) {
        // Se Login effettuato con successo, apro UserProfile.php con il CodeUser del profilo
        $_SESSION["CodeUser"] = $result["CodeUser"];
        // Non devo passare CodeUser perché è dentro a _SESSION
        header("Location: ../UserProfile.php");
    } else {
        // Se Login non effettuato con successo, mostro messaggio di errore
        $error = "Dati non corretti";
        // ../HTML/Login.html
        header("Location: ../mainlogin.php?error=$error");
    }


?>