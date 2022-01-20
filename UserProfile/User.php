<?php
    // User contiene dati utente

    require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

    session_start();

    if(isset($_SESSION['user_Username']))
    {
        // Ottengo Valori da Pagina Statica 
        $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
        $HTML = file_get_contents($url);
        
        // Cambio Valore BreadCrumb
        $HTML = str_replace("{{ SubPage }}"," User Informations",$HTML);
        $content = '<div id="content">';
        // Ottiene Valori Utente da SQL
        // Query del tipo SELECT * FROM users WHERE Code_user = $_SESSION['Code_User'];
        $DbAccess = new DBAccess();
        $conn = $DbAccess->openDBConnection();
        if($conn){
            //$QueryResult =$DbAccess->getUser($_SESSION['Code_User']);
            $Result = $DbAccess->getUser($_SESSION['user_ID']) ;

            if($Result) {
                $content .= '<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR . trim($Result["Picture"]) .'" alt="Profile Picture" width="32" height="32"> 
                        <h2> User : '. trim($Result["Nickname"]) .'</h2>
                        <p> Name & Surname : '. trim($Result["Name"]) ."  ". trim($Result["Surname"]) .' </p>
                        <p> Status : '. trim($Result["Status"]) .' </p>
                        <p> Birthday : '. trim($Result["Birth"]) .' </p>
                        <p> Email : '. trim($Result["Email"]) .' </p>
                        <p> Nationality : '. trim($Result["Nationality"]) .' </p>
                        <p> City : '. trim($Result["City"]) .' </p>
                        <p> Address : '. ($Result["Address"] ? trim($Result["Address"]) : 'Not Available') .' </p> 
                        <p> Phone Number : '. ($Result["Phone"] ? trim($Result["Phone"]) : 'Not Available') .' </p>
                        <p> Link to a Curriculum : '. ($Result["Curriculum"] ? trim($Result["Curriculum"]) : 'Not Available') .' </p>
                        <p> Description : '. trim($Result["Description"]) .' </p>
                        <p> Creation Date : '. trim($Result["Creation"]) .' </p>
                        </div>';
            }
            else 
                $content .= '<p>There is no content to be shown. </p></div>';
            /* DA AGGIUNGERE PARTE SU REVIEW
            $Review = $DbAccess->getUserReviewList($_SESSION['user_ID']);
            if($Review)
            {
                foreach($Review as $R)
                {
                    $content .= 'Review : '.$R['Stars'];
                }
            }
            */
        }
        else
        {
            $content -= ' There was an error with the Database. Try again later! </div>';
        }
        // Rimpiazza Valori su file html
        $HTML = str_replace('<div id="content"></div>',$content,$HTML);
        // Stampo File Modificato
        echo $HTML;
    }
    else
        header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 1);
?>