<?php
    require_once "ConnectionToDatabase.php";
    use _Database\Database;

    // CONTROLLI SE LOGIN EFFETTUATO

    
    $index = $_GET['section'];
    
    session_start();
    $_SESSION['myValue']=19;
    $_SESSION['Name']="Tommaso";

    switch ($index) {
        case 1:
            header("Location: ../UserProfile/User.php"); 
        break;
        case 2:
            header("Location: ../UserProfile/Work.php");
        break;
        case 3:
            header("Location: ../UserProfile/BidHistory.php");
        break;
        case 4:
            header("Location: ../UserProfile/Bids.php");
        break;
        case 5:
            header("Location: ../UserProfile/Setting.php");
        break;
        default :
            header("Location: ../UserProfile/Welcome.php");
        break;
    }

?>