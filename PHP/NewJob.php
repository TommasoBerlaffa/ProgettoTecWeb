<?php
    require_once "ConnectionToDatabase.php";
    use _Database\Database;
    session_start();

    //ID da ottenere tramite session
    $ID=$_SESSION("ID");
      //Trim per togliere spazi e htmlentities per assicurarci che gli utenti non inseriscano tag html
    $Title=htmlentities(trim($_POST["Title"]));
    $Desc=htmlentities(trim($_POST["Desc"]));
    $Tipology=htmlentities(trim($_POST["Tipology"]));
    $Payment;
    filter_var(INPUT_GET,'MaxPay',FILTER_SANITIZE_NUMBER_FLOAT);
    filter_var(INPUT_GET,'MinPay',FILTER_SANITIZE_NUMBER_FLOAT);
    //(l'utente può aver cambiato l'html quindi potrebbero non esserci floats)
    $MinPay=htmlentities(trim($_POST["MinPay"]));
    $MaxPay=htmlentities(trim($_POST["MaxPay"]));
    $Skills=htmlentities(trim($_POST["Skills"])); //Questo non così ma ok
    $Expiring=date('Y-m-d H:i:s', strtotime('+7 days')); //Da Controllare se funzia

    $Result;


    //Vari check che le variabili non abbiano valori strani/non validi etc
    //Valutare di invertire i 2 rami then/else
    if(!isset($ID) || !isset($Title) || !isset($Desc) || !isset($Tipology) || !isset($MinPay) || !isset($MaxPay) || !isset($Skills) || !isset($Expiring)){
      //Qualche variabile non è settata, ritorna errore (?)
    }else{
      if($MaxPay<$MinPay) {
        //Ritorna errore su questo
        //header();
      }

    }
    //Apertura, caricamento e chiusura su db

    $Result=createJob($ID, $Title,$Desc,$Tipology,$Payment,$MinPay,$MaxPay,$Expiring); //Id e Payment???
    if($Result) {
      //Tutto ok, Si conferma il successo
    }
    else {
      //Errore, si dice che qualcosa non è andato come doveva
    }


?>
