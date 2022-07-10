<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['Admin'])) {
  $DBAccess = new DBAccess();
  if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
  // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
  $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Admin.html';
  $pagina = file_get_contents($url);
	$listaJobs = $DBAccess->getPastJobs();
	
  $urltabella = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TableAdminJob.html';
  
  $tabella = file_get_contents($urltabella);

  $contenuto ='';
  if(isset($listaJobs)){
    foreach($listaJobs as $U)
    {
      $contenuto .= '<tr><td><a href="ViewJob.php?Code_job='.trim($U["Code_job"]).'">'.trim($U["Title"]).'</a></td>
      <td>'.trim($U["Status"]).'</td></tr>';
    }
  }

  $tabella = str_replace('{{value}}',$contenuto,$tabella);
  $tabella = str_replace('{{elem}}','past jobs',$tabella);
  
  $DBAccess->closeDBConnection();
  $pagina = str_replace('{{Page}}','List of Jobs',$pagina);
  $pagina = str_replace('{{element}}','Job Title',$pagina);
  $pagina = str_replace('<admin>',$tabella,$pagina);
  echo $pagina;
}
else
	header("Location:Welcome.php");
?>