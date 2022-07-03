<?php
require_once "..". DIRECTORY_SEPARATOR .'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    $error= '<ul id="errors">';
    if(isset($_SESSION['user_ID'])) {
		
      // Winner, Job, ID
      $Winner='';$job='';
      if(isset($_POST['winner']))
        $Winner=$_POST['winner'];
      else
        $error.='<li>Cannot set the winner, try again by changing the winner</li>';
      if(isset($_SESSION['Code_job']))
        $job=$_SESSION['Code_job'];
      else
        $error.='<li>Cannot find the job, please try again later</li>';
      
      if($Winner!='' && $job!='')
        $DBAccess->setWinner($Winner,$job,$_SESSION['user_ID']);
			$DBAccess->closeDBConnection();
			header('Location:..'. DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$_SESSION["Code_job"]);
		}
    
    else{
		$DBAccess->closeDBConnection();
		header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
	}
}
else
	header("Location:..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");
?>