<?php

    // BidHistory deve contenere tutte le Bids dell'utente ( Passate che hanno avuto successo )
    require_once "../PHP/ConnectionToDatabase.php";
    use _Database\Database;

    // Non servono controlli su Login perchè vengono fatti da UserProfile.php

    // Ottengo Valori da Pagina Statica  
    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Bids History",$HTML);

    $DbAccess = new Database();
    $DbAccess->ConnectToDb();
    
    session_start();
    // Ottiene Valori Utente da SQL
    // Query del tipo SELECT * FROM users WHERE Code_user = $_SESSION['Code_User'];
    $Query = $DbAccess->getOldBids($_SESSION['myValue']);
    $QueryResult = $Query;
    
    $table = "<div id=\"content\">
                <table class=\"content\">
                    <tr>
                        <th> Title </th>
                        <th> Status </th>
                        <th> Tipology </th>
                        <th> Payment </th>
                        <th> Expiring Time </th>
                    </tr>";
    
    while($row = $QueryResult->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td><a href=\"../PHP/ViewJobOld.php?Code_job=".$row["Code_job"]."\">".$row["Title"]."</a></td>";
        $table .= "<td>".$row["Status"]."</td>";
        $table .= "<td>".$row["Tipology"]."</td>";
        $table .= "<td>".$row["Payment"]."</td>";
        $table .= "<td>".$row["Expiring_time"]."</td>";
        $table .= "</tr>";
    }
                                        
    $table .="</table></div>";
    // Rimpiazza Valori su file html
    $HTML = str_replace("<div id=\"content\"></div>",$table,$HTML);
    // Stampo File Modificato
    echo $HTML;
    



    // Crea una table e la aggiunge al file HTML
    // Info table : Numero di Column uguale al numero di campi
    // Info table : Numero di Row uguale al numero di risultati della query + 1 (header)
?>