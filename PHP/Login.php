<?php

    require_once 'DBAccess.php';

    session_start();
    //Controllo se Login è già stato effettuato
	$page=null;
	if(isset($_GET['section'])){
		$page=filter_var($_GET['section'], FILTER_VALIDATE_INT);
	}
    if(!isset($_SESSION['user_Username']))
    {
        $paginaHTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Login.html');

        $messaggioErrore = '';
        if(isset($_POST['Login'])) {
            $user = filter_var($_POST['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $user = filter_var($user, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            if(strlen($user) == 0)
                $messaggioErrore .= '<li>Username mancante</li>';

            $pwd = filter_var($_POST['Password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $pwd = filter_var($pwd, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            if(strlen($pwd) == 0) 
                $messaggioErrore .= '<li>Password mancante</li>';

            if($messaggioErrore == '') {
                $DBAccess = new DBAccess();
                $Logged=$DBAccess->Login($user, $pwd);
                
                if($Logged != null) {
                    
                    $user = ''; $pwd = '';
                    
                    $_SESSION['user_ID'] = $Logged['ID'];
                    $_SESSION['user_Status'] = $Logged['Status'];
                    $_SESSION['user_Username'] = $Logged['Username'];
                    $_SESSION['user_Icon'] = $Logged['Icon'];

					if(isset($page))
						header('Location:UserProfile.php?section='. $page);
					else
						header('Location:UserProfile.php');
            
                } else
                    $messaggioErrore = '<div id="errorMessages"><p>Username e/o Password non sono corretti.</p></div>';
            } else
                $messaggioErrore = '<div id="errorMessages"><ul>' . $messaggioErrore . '</ul></div>';
        }
        
        $paginaHTML =  str_replace('<messaggiForm />', $messaggioErrore, $paginaHTML);
        $paginaHTML =  str_replace('<sectionRedirect />', "?section=$page", $paginaHTML);
        echo $paginaHTML;
    }
    else
    {
        //Se il Login è già stato effettuato, mando a UserProfile
		if(isset($page))
			header('Location:UserProfile.php?section='. $page);
		else
			header('Location:UserProfile.php');
    }

?>