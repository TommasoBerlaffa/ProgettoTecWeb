<?php
    session_start();

    unset($_SESSION['user_ID']);
    unset($_SESSION['user_Status']);
    unset($_SESSION['user_Username']);
    unset($_SESSION['user_Icon']);

    header('Location:'. DIRECTORY_SEPARATOR .'Login.php');
?>