<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username'])) {
  $DBAccess = new DBAccess();
  $conn = $DBAccess->openDBConnection();

  if($conn) {
    // Controllo se nella sessione c'é User ID (dovrebbe esserci per il controllo di User Username ma è meglio fare 2 controlli)
    if(isset($_SESSION['user_ID'])) {
      $id=$_SESSION['user_ID'];
      isset($_POST["Price"]) ? $price =  $_POST["Price"] : $price=0;
      isset($_POST["Description"]) ? $description =  $_POST["Description"] : $description='';
      $DBAccess->createBid($id,$_SESSION["Code_Job"],$price,$description);
      header('Location:..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'ViewOffer.php?Code_job='.$_SESSION["Code_Job"]);    
    }
    else
      header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
  }
  else
    header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
}
else
  header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");
?>