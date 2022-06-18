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
	

  $contenuto ='<table id="AdminTable">
  <caption id="description"> List of Past Jobs </caption>
  <thead>
    <tr>
      <th scope="col" > Jobs </th>
      <th scope="col" > Status </th>
    </tr>
  </thead>
  <tbody>';
  if(isset($listaJobs)){
    foreach($listaJobs as $U)
    {
      $contenuto .= '<tr><td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$U["Code_job"].'">'.$U["Title"].'</a></td>
      <td>'.trim($U["Status"]).'</td></tr>';
    }
  }
  $contenuto .= '</tbody></table>';
  $DBAccess->closeDBConnection();
  $pagina = str_replace('{{Page}}','List of Jobs',$pagina);
  $pagina = str_replace('{{element}}','Job Title',$pagina);
  $pagina = str_replace('<admin>',$contenuto,$pagina);
  echo $pagina;
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");
?>