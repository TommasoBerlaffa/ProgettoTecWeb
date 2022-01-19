    <?php
    require_once "DBAccess.php";

    // CONTROLLI SE LOGIN EFFETTUATO
    session_start();

    if(isset($_SESSION['user_Username']))
    {
        $index = $_GET['section'];
    
        switch ($index) {
            case 1:
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."User.php"); 
            break;
            case 2:
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."Work.php");
            break;
            case 3:
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."BidHistory.php");
            break;
            case 4:
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."Bids.php");
            break;
            case 5:
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."Setting.php");
            break;
            default :
                header("Location: ..".DIRECTORY_SEPARATOR."UserProfile".DIRECTORY_SEPARATOR."Welcome.php");
            break;
        }
    }
    else 
        header("Location:..".DIRECTORY_SEPARATOR."HTML".DIRECTORY_SEPARATOR."Login.html");
    
    
?>