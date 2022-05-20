<?php

  require_once 'DBAccess.php';

  session_start();
  //Controllo se Login è già stato effettuato
	$section=null;
	if(isset($_GET['section'])){
		$section=filter_var($_GET['section'], FILTER_VALIDATE_INT);
	}
  $page=null;
  if(isset($_GET['url'])){
		$page=filter_var($_GET['url'], FILTER_VALIDATE_INT);
	}

  if(!isset($_SESSION['user_Username']))
  {
    $paginaHTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Login.html');

    $messaggioErrore = '';
    if(isset($_POST['Login'])) {
      $user = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
      if(strlen($user) == 0)
        $messaggioErrore .= '<li>Username mancante</li>';

      $pwd = filter_var($_POST['Password'], FILTER_SANITIZE_STRING);
      if(strlen($pwd) == 0)
        $messaggioErrore .= '<li>Password mancante</li>';

      if($messaggioErrore == '') {
        $DBAccess = new DBAccess();
        $Logged=$DBAccess->login($user, $pwd);

        if($Logged != null) {

          $user = ''; $pwd = '';

          $_SESSION['user_ID'] = $Logged['ID'];
          $_SESSION['user_Status'] = $Logged['Status'];
          $_SESSION['user_Username'] = $Logged['Username'];
          $_SESSION['user_Icon'] = $Logged['Icon'];

          if(isset($page))
            $id=filter_var($_GET['section'], FILTER_VALIDATE_INT);
            // PHP/ViewOffer.php?Code_job=1
            header('Location:..'. DIRECTORY_SEPARATOR .'php'. DIRECTORY_SEPARATOR . $page.'?Code_job='.$id);
					if(isset($section))
						header('Location:UserProfile.php?section='. $section);
					else
						header('Location:UserProfile.php');

        } else
          $messaggioErrore = '<div id="errorMessages"><p>Username e/o Password non sono corretti.</p></div>';
      } else
        $messaggioErrore = '<div id="errorMessages"><ul>' . $messaggioErrore . '</ul></div>';
    }

    $paginaHTML =  str_replace('<messaggiForm />', $messaggioErrore, $paginaHTML);
		$redirect='';
		if($page)
			$redirect="?section=$page";
		$paginaHTML =  str_replace('<sectionRedirect />', $redirect, $paginaHTML);
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
