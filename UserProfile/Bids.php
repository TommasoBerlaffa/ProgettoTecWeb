<?php

    // Bids deve contenere tutte le Bids correnti 
    require_once '..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'DBAccess.php';

    session_start();

    if(isset($_SESSION['user_Username']))
    {
        // Ottengo Valori da Pagina Statica  
        $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'UserProfile.html';
        $HTML = file_get_contents($url);
        // Cambio Valore BreadCrumb
        $HTML = str_replace("{{ SubPage }}","Current Bids",$HTML);

        $DbAccess = new DBAccess();
        $conn = $DbAccess->openDBConnection();
        
        $table = '<div id="content">
                        <p>The page Bids display all your current Bids.
                        Click on a job Title to display more infos! </p>
                        <table class=\"content\">
                            <tr>
                                <th> Title </th>
                                <th> Status </th>
                                <th> Tipology </th>
                            </tr>';
            

        if($conn)
        {
            $Result = $DbAccess->getUserJobs($_SESSION['user_ID'],false);
            if($Result) {
                
                // Rimpiazza Valori su file html    
                foreach($Result as $row ) {
                    $table .= '<tr>';
                    $table .= '<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job="'.$row['Code_job'].'">'.trim($row['Title'] ).' </a></td>';
                    $table .= '<td>'. trim($row['Status'] ).'</td>';
                    $table .= '<td>'. trim($row['Tipology'] ).'</td>';
                    $table .= '</tr>';
                }
                $table .='</table></div>';
            }
            else
            {
                $table .='</table><p>No Data Currently Available</p></div>';
            }
        }                                            
        

        $HTML = str_replace('<div id="content"></div>',$table,$HTML);
    
        // Stampo File Modificato
        echo $HTML;
    }
    else
        header('Location:..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php?section='. 4);

?>