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
	$listaAdminUser = $DBAccess->getAdminUserAction();
	$listaAdminJob = $DBAccess->getAdminJobAction();
	
  $pagina = str_replace('<input type="text" id="search" onkeyup="AdminSearch()" placeholder="Search for {{element}}..">','',$pagina);

  $contenuto ='<table id="AdminTable">
  <caption id="description"> List of Admin Action on Users </caption>
  <thead>
    <tr>
      <th scope="col" > Date </th>
      <th scope="col" > Comment </th>
    </tr>
  </thead>
  <tbody>';
  if(isset($listaAdminUser)){
    foreach($listaAdminUser as $U)
    {
      $contenuto .= '<tr><td>'.trim($U["Date"]).'</td>
      <td>'.trim($U["Comments"]).'</td></tr>';
    }
  }
  $contenuto .= '</tbody></table>';
  
  $contenuto .='<table id="AdminTable">
  <caption id="description"> List of Admin Action on Jobs </caption>
  <thead>
    <tr>
      <th scope="col" > Date </th>
      <th scope="col" > Comment </th>
    </tr>
  </thead>
  <tbody>';
  if(isset($listaAdminJob)){
    foreach($listaAdminJob as $J)
    {
      $contenuto .= '<tr><td>'.trim($J["Date"]).'</td>
      <td>'.trim($J["Comments"]).'</td></tr>';
    }
  }
  $contenuto .= '</tbody></table>';




  $DBAccess->closeDBConnection();
  $pagina = str_replace('<admin>',$contenuto,$pagina);
  $pagina = str_replace('{{element}}','User Nicknames',$pagina);
  $pagina = str_replace('{{Page}}','List of Users',$pagina);
  echo $pagina;
}
else
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");
?>