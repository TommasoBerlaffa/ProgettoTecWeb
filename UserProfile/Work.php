<?php
    // Work deve contenere tutti I lavori creati dall'utente ( Passati )
    require_once "../PHP/ConnectionToDatabase.php";
    use _Database\Database;

    // Non servono controlli su Login perchè vengono fatti da UserProfile.php
    
    // Ottengo Valori da Pagina Statica 
    $url = '../HTML/UserProfile.html';
    $HTML = file_get_contents($url);

    // Cambio Valore BreadCrumb
    $HTML = str_replace("{{ SubPage }}","Work History",$HTML);
    
    $DbAccess = new Database();
    $DbAccess->ConnectToDb();

    session_start();

    // Crea una table da aggiungere al file HTML
    $table = "<div id=\"content\">
                <p>The page Work History display all the Job offer you created.
                    Click on a job Title to display more infos! </p>
                <table class=\"content\">
                    <tr>
                        <th> Title </th>
                        <th> Status </th>
                        <th> Tipology </th>
                        <th> Payment </th>
                        <th> Expiring Time </th>
                    </tr>";

    // Ottiene Valori da Query - Past Jobs
    // Query : SELECT * FROM past_jobs WHERE Code_user = $_SESSION['Code_User'];
    $Query = $DbAccess->getWork($_SESSION['myValue']);
    $QueryResult = $Query;
  
    // Riempo "Manualmente" perhcé voglio che l'ultimo result sia un link e non un semplice value
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
?>