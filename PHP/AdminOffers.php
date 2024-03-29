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
	$listaOffers = $DBAccess->getOffers();
	
  $urltabella = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableAdminJob.html';
  
  $tabella = file_get_contents($urltabella);

  $contenuto ='';
  if(isset($listaOffers)){
    foreach($listaOffers as $U)
    {
      $contenuto .= '<tr>
        <td><a href="ViewJob.php?Code_job='.trim($U["Code_job"]).'">'.trim($U["Title"]).'</a></td>
        <td>'. (trim($U["Expiring"]) > date("Y-m-d h:i:sa") ? 'Active' : 'Terminated') .'</td>
      </tr>';
    }
  }
  $tabella = str_replace('{{value}}',$contenuto,$tabella);
  $tabella = str_replace('{{elem}}','offer',$tabella);

  $DBAccess->closeDBConnection();
  $pagina = str_replace('<admin>',$tabella,$pagina);
  $pagina = str_replace('{{element}}','Offers Title',$pagina);
  $pagina = str_replace('{{Page}}','List of Offers',$pagina);
  echo $pagina;
}
else
	header("Location:Welcome.php");
?>