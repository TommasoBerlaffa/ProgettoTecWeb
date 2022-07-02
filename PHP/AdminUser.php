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
	$listaUser = $DBAccess->getUsers();
	
  $urltabella = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR.'Elements'. DIRECTORY_SEPARATOR .'TableAdminUser.html';
  
  $tabella = file_get_contents($urltabella);

  $contenuto ='';
  if(isset($listaUser)){
    foreach($listaUser as $U)
    {
      $contenuto .= '<tr><td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.trim($U["Code_User"]).'">'.trim($U["Nickname"]).'</a></td>
      <td>'.trim($U["Status"]).'</td></tr>';
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
	header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."UserProfile.php");
?>