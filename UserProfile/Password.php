<?php
  require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Ottengo Valori da Pagina Statica 
    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Change Password",$HTML);
    $HTMLContent='<form id="changePsw" action="../PHP/ChangePassword.php" method="post">
      <fieldset>
      <legend>Change Password</legend>
      <label for="OldPsw">Old Password : </label>
      <input type="password" name="OldPsw" id="OldPsw" required />
      <label for="Password">New Password :</label>
      <input type="password" name="Password" id="Password" required />
      <label for="Repeat-Password">Repeat New Password :</label>
      <input type="password" name="Repeat-Password" id="Repeat-Password" required />
      <button type="submit" name="ChangePsw">Change Password</button>
      </fieldset>
    </form>';
    $HTML = str_replace('<div id="content"></div>',$HTMLContent,$HTML);
    // Apre file html
    echo $HTML;
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 5);
?>