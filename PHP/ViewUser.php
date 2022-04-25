<?php

require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
  // Ottengo Valori da Pagina Statica
  $url = '..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'ViewUser.html';
  $HTML = file_get_contents($url);

  $DbAccess = new DBAccess();
  $conn = $DbAccess->openDBConnection();

  if($conn)
  {
    if($_GET['Code_User']){
      $index = filter_var($_GET['Code_User'], FILTER_VALIDATE_INT);
      $row = $DbAccess->getUser($index);
      //Se trova risultato
      if($row)
      {                
        $HTML = str_replace("{{ User }}",trim($row["Nickname"]),$HTML);
        $HTML = str_replace("{{ Name }}",trim($row["Name"]),$HTML); 
        $HTML = str_replace("{{ Surname }}",trim($row["Surname"]),$HTML);
        $HTML = str_replace("{{ Picture }}",trim($row["Picture"]),$HTML);
        $HTML = str_replace("{{ Status }}",trim($row["Status"]),$HTML);
        $HTML = str_replace("{{ Birth }}",trim($row["Birth"]),$HTML);
        $HTML = str_replace("{{ Email }}",trim($row["Email"]),$HTML);
        $HTML = str_replace("{{ Nationality }}",trim($row["Nationality"]),$HTML);
        $HTML = str_replace("{{ City }}",trim($row["City"]),$HTML);
        $HTML = str_replace("{{ Curriculum }}",$row["Curriculum"]?trim($row["Curriculum"]) : "Not Available",$HTML);
        $HTML = str_replace("{{ Description }}",$row["Description"]?trim($row["Description"]) : "Not Available",$HTML);   
        
      } //Se non trova un risultato
      else
      {
        $HTML = str_replace( '{{ User }}', 'Unknown User' ,$HTML);
        // (?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)
        $HTML = preg_replace('/(?<=<div id="userInfo">)((\n|.)*)(?=<\/div>)/',
            ' <div id="content">
                <p> No Info are currently available about this specific User</p>

              </div>',$HTML);
        //$HTML = str_replace('<div id="JobInfo">'.*?.'</div>','<div id="content"><p> There is nothing to be seen here </p></div>',$HTML);
      }
    }
    else {
      header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Index.php");
    }
    
  }
  echo $HTML;    
}
else
  header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR ."Login.php");    

?>