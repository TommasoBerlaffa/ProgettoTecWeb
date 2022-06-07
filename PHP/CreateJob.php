<?php
    require_once "DBAccess.php";

    session_start();
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'CreateJob.html';
    $HTML = file_get_contents($url);

    if(isset($_SESSION['user_Username']))
    {
      $HTML = str_replace('<subpage/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>',$HTML);

    }
    else
    {
      $_SESSION['Url'] = 'CreateJob';
      header("location: ../PHP/Login.php");
    }
  /*


    //ID da ottenere tramite session
    //$ID=$_SESSION['user_ID']; $
    $Title='';    $Desc="";    $Tipology="";    $MinPay="";    $MaxPay="";    $Skills="";

    if(isset($_SESSION['user_ID']))
      $ID=$_SESSION('user_ID');
    else
      header("location: ../PHP/Login.php");


    if(isset($_POST['Title']))
      $Title=htmlentities(trim($_POST["Title"]));
    if(isset($_POST['Desc']))
      $Desc=htmlentities(trim($_POST["Desc"]));

    if(isset($_POST['Tipology']))
      $Tipology=htmlentities(trim($_POST["Tipology"]));

    $Payment="0"; //????

    if( isset($_POST['MinPay']))
      $MinPay = filter_var($_POST['MinPay'], FILTER_VALIDATE_INT);

    if( isset($_POST['MinPay']))
      $MaxPay = filter_var($_POST['MaxPay'], FILTER_VALIDATE_INT);

    if( isset($_POST['Skills']))
      $Skills = $_POST['Skills']; //non così ma ok
    $Skills="test";
    $Expiring=date('Y-m-d H:i:s', strtotime('+7 days'));
    $Result;



    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'CreateJob.html';
    $HTML = file_get_contents($url);

    //Valutare di invertire i 2 rami then/else
    if(isset($ID) && isset($Title) && isset($Desc) && isset($Tipology) && isset($MinPay) && isset($MaxPay) && isset($Skills) && isset($Expiring)){
      if($MaxPay>=$MinPay){
        $DBAccess = new DBAccess();
        $Result=$DBAccess->createJob($ID, $Title,$Desc,$Tipology,$Payment,$MinPay,$MaxPay,$Expiring); //Id e Payment???
        if($Result)
          $HTML = str_replace('<caricato/>','Job offer created!',$HTML); //Se si aggiorna la pagina dopo, si continua a caricare più volte lo stesso lavoro : Da fixare
        else
          $HTML = str_replace('<caricato/>','Error creating the job offer!!',$HTML);

      }else{
        $HTML = str_replace('<caricato/>','Max Pay must be greater than or equal to the Min Pay !!',$HTML);
      }
    }else{
      //Qualche variabile non è settata, ritorna errore (?)
      //$HTML = str_replace('<caricato/>','Error creating the job offer!!',$HTML);
    }*/


    echo $HTML;


?>
