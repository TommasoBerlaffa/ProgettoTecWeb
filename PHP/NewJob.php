<?php
    require_once "DBAccess.php";

    session_start();

    if( !isset($_SESSION["user_Username"]))
      header("Location:index.php");

    //ID da ottenere tramite session
    $Id=$_SESSION['user_ID'];

    $DBAccess = new DBAccess();
    if(!($DBAccess->openDBConnection())){
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
      exit;
    }

    if( (isset($_POST["Title"]) && isset($_POST["Type"]) && isset($_POST["PayV"]) && isset($_POST["Description"]) && isset($_POST["Min"])) && isset($_POST["Max"]) )
    {
      $Title = filter_var($_POST["Title"], FILTER_SANITIZE_STRING);
      $Desc = filter_var($_POST["Description"], FILTER_SANITIZE_STRING);
      $Type= filter_var($_POST["Type"], FILTER_SANITIZE_STRING);
      $Min = filter_var($_POST["Min"], FILTER_SANITIZE_NUMBER_INT);
      $Max = filter_var($_POST["Max"], FILTER_SANITIZE_NUMBER_INT);
      $Expiring = date("Y-m-d", strtotime("+1 week"));
      $_POST["Max"] == 'Pay2' ? $Pay = 0 : $Pay = 1;
    }
    else
      header("Location:CreateJob.php");
    
    $Result=$DBAccess->createJob($Id,$Title,$Desc,$Type,$Pay,$Min,$Max,$Expiring);
    $DBAccess->closeDBConnection();
    if($Result) 
      header("Location:UserProfile.php?section=2");
    else {
      header("Location:CreateJob.php?error=1");
    }
      
    
    



?>
