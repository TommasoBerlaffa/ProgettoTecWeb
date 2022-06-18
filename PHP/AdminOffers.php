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
	$listaOffers = $DBAccess->getOffers();
	

  $contenuto ='<table id="AdminTable">
  <caption id="description"> List of Offers </caption>
  <thead>
    <tr>
      <th scope="col" > User </th>
      <th scope="col" > Status </th>
    </tr>
  </thead>
  <tbody>';
  if(isset($listaOffers)){
    foreach($listaOffers as $U)
    {
      $contenuto .= '<tr><td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.$U["Code_job"].'">'.$U["Title"].'</a></td>
      <td>'.trim($U["Status"]).'</td></tr>';
    }
  }
  $contenuto .= '</tbody></table>';
  $DBAccess->closeDBConnection();
  $pagina = str_replace('<admin>',$contenuto,$pagina);
  $pagina = str_replace('{{element}}','Offers Title',$pagina);
  $pagina = str_replace('{{Page}}','List of Offers',$pagina);
  echo $pagina;
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");
?>