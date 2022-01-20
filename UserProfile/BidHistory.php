<?php

    // BidHistory deve contenere tutte le Bids dell'utente ( Passate che hanno avuto successo )
    require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

    session_start();
    
    if(isset($_SESSION['user_Username']))
    {
        // Ottengo Valori da Pagina Statica  
        $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
        $HTML = file_get_contents($url);

        // Cambio Valore BreadCrumb
        $HTML = str_replace("{{ SubPage }}","Bids History",$HTML);

        $DbAccess = new DBAccess();
        $conn = $DbAccess->openDBConnection();
        
        $table = '<div id="content">
                    <p>The page Bid History display all your successful Bids.
                    Click on a job Title to display more infos! </p>
                    <table class="content">
                        <tr>
                            <th> Title </th>
                            <th> Status </th>
                            <th> Tipology </th>
                            <th> Payment </th>
                            <th> Min Payment </th>
                            <th> Max Payment </th>
                        </tr>';
        
        if($conn) {
        
            // Ottiene Valori Utente da SQL
            // Query del tipo SELECT * FROM users WHERE Code_user = $_SESSION['Code_User'];
            $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],true);
            if($Result) {
                foreach($Result as $row ) {
                    $table .= '<tr>';
                    $table .= '<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></td>';
                    $table .= '<td>'.trim($row["Status"]).'</td>';
                    $table .= '<td>'.trim($row["Tipology"]).'</td>';
                    $table .= '<td>'.trim($row["Payment"]).'</td>';
                    $table .= '<td>'.trim($row["P_min"]).'</td>';                    
                    $table .= '<td>'.trim($row["P_max"]).'</td>';
                    $table .= '</tr>';
                } 
                $table .='</table></div>';
            }
            else
            {
                $table .='</table><p>No Data Currently Available</p></div>';
            }
        }        
        else
        {
            $table .='</table><p>Cannot Connect Correctly</p></div>';
        }                                    

        // Rimpiazza Valori su file html
        $HTML = str_replace('<div id="content"></div>',$table,$HTML);
        // Stampo File Modificato
        echo $HTML;
    }
    else
        header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 3);



    // Crea una table e la aggiunge al file HTML
    // Info table : Numero di Column uguale al numero di campi
    // Info table : Numero di Row uguale al numero di risultati della query + 1 (header)
?>