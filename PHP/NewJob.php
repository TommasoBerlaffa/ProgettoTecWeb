<?php
    require_once "DBAccess.php";

    session_start();

    if( !isset($_SESSION["user_Username"]))
      header("Location:index.php");

    //ID da ottenere tramite session
    $Id=$_SESSION['user_ID'];

    isset($_POST["Title"]) ? $Title = filter_var($_POST["Title"], FILTER_SANITIZE_STRING) : $Title="";
    
    isset($_POST["Description"]) ? $Desc = filter_var($_POST["Description"], FILTER_SANITIZE_STRING) : $Desc="";
    
    isset($_POST["Type"]) ? $Type= filter_var($_POST["Type"], FILTER_SANITIZE_STRING) : $Type="";
    
    isset($_POST["Min"]) ? $Min = filter_var($_POST["Min"], FILTER_SANITIZE_NUMBER_INT) : $Min="";
    
    isset($_POST["Max"]) ? $Max = filter_var($_POST["Max"], FILTER_SANITIZE_NUMBER_INT) : $Max="";
    
    isset($_POST["Pay"]) ? $Pay = filter_var($_POST["Pay"], FILTER_SANITIZE_NUMBER_INT) : $Pay="";
    
    $Expiring = date("Y-m-d", strtotime("+1 week"));

    $DBAccess = new DBAccess();
    if(!($DBAccess->openDBConnection())){
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
      exit;
    }
    $Result=$DBAccess->createJob($Id, $Title,$Desc,$Type,$Pay,$Min,$Max,$Expiring); //Id e Payment???
    $DBAccess->closeDBConnection();
    if($Result) {
      //Tutto ok, Si conferma il successo
    }
    else {
      //Errore, si dice che qualcosa non è andato come doveva
    }
    



?>
