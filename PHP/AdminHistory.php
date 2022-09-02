<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

require_once 'DBAccess.php';

if(isset($_SESSION['Admin'])) {
  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
  $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Admin.html';
  $pagina = file_get_contents($url);
	$listaAdminUser = $DBAccess->getAdminUserAction();
	$listaAdminJob = $DBAccess->getAdminJobAction();
	
  $pagina = str_replace('<input type="text" id="search" placeholder="Search for {{element}}..">','',$pagina);

  $urltabella = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableAdminHistoryUser.html';
  
  $tabella = file_get_contents($urltabella);
  $contenuto='';
  if(isset($listaAdminUser)){
    foreach($listaAdminUser as $U)
    {
      $contenuto .= '<tr>
      <td><a href="ViewUser.php?Code_User='.trim($U["Code"]).'">'. trim($U["Nick"]) .'</a></td>
      <td>'. trim($U["Stat"]) .'</td>
      <td>'.trim($U["Date"]).'</td>
      <td>'.trim($U["Comments"]).'</td>
      </tr>';
    }
  }
  $tabella = str_replace('{{value}}',$contenuto,$tabella);

  $urltabella = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableAdminHistoryJobs.html';
  
  $tabella .= file_get_contents($urltabella);
  
  
  $contenuto ='';
  if(isset($listaAdminJob)){
    foreach($listaAdminJob as $J)
    {
      $contenuto .= '<tr>
      <td><a href="ViewJob.php?Code_job='.trim($J["Code"]).'">'. trim($J["Title"]) .'</a></td>
      <td>'.trim($J["Date"]).'</td>
      <td>'.trim($J["Comments"]).'</td></tr>';
    }
  }

  $tabella = str_replace('{{value}}',$contenuto,$tabella);
  
  

  $DBAccess->closeDBConnection();
  $pagina = str_replace('<admin>',$tabella,$pagina);
  $pagina = str_replace('{{element}}','User Nicknames',$pagina);
  $pagina = str_replace('{{Page}}','List of Users',$pagina);
  echo $pagina;
}
else
	header("Location:Welcome.php");
?>