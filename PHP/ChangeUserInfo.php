<?php

  require_once "DBAccess.php";

  session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Da fare quando c'è il metodo specifico in DBAccess
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR. "Login.php");
  }

?>


    