<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])) {
		if(isset($_SESSION['Code_job']))
		{
			$job = $DBAccess->getJob($_SESSION['Code_job'],true);
			if($job)
			{
			// Controllo che la persona entrata quì sia l'owner del lavoro
				if( trim($job['Code_user']) == $_SESSION['user_ID'])
				{
					// Cambio Status al Lavoro
					if($DBAccess->changeJobStatus($_SESSION['Code_job'],'Deleted',false))
					{
						// Mando l'utente a UserProfile->Work
						header("Location: ..". DIRECTORY_SEPARATOR ."UserProfile". DIRECTORY_SEPARATOR ."Work.php");
					}
					else
						header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error404.html');
				}
			}
			else 
				header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
			$DBAccess->closeDBConnection();
		}
    }
    else
		header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
?>