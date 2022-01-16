<?php

    // Bids deve contenere tutte le Bids correnti 
    require_once "../PHP/ConnectionToDatabase.php";
    use _Database\Database;

    // Non servono controlli su Login perchè vengono fatti da UserProfile.php

    // Ottengo Valori da Pagina Statica  
    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);
    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Current Bids",$HTML);

    $DbAccess = new Database();
    $DbAccess->ConnectToDb();
    
    session_start();
    $Query = $DbAccess->getBids($_SESSION['myValue']);
    $QueryResult = $Query;

    $table = "<div id=\"content\">
                <table>
                    <tr>
                        <th> Title </th>
                        <th> Status </th>
                        <th> Tipology </th>
                        <th> Expiring Time </th>
                    </tr>";
    // Rimpiazza Valori su file html    
    while($row = $QueryResult->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td><a href=\"../PHP/ViewOffer.php?Code_job=\"".$row['Code_job']."\">".trim($row['Title'] )." </a></td>";
        $table .= "<td>". trim($row['Status'] )."</td>";
        $table .= "<td>". trim($row['Tipology'] )."</td>";
        $table .= "<td>". trim($row['Expiring'] )."</td>";
        $table .= "</tr>";
    }
                                        
    $table .="</table></div>";

    $HTML = str_replace("<div id=\"content\"></div>",$table,$HTML);
  
    // Stampo File Modificato
    echo $HTML;
    

?>