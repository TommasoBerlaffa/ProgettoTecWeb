<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
  // Ottengo Valori da Pagina Statica
  $url = '..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'ViewOffer.html';
  $HTML = file_get_contents($url);

  $DbAccess = new DBAccess();
  $conn = $DbAccess->openDBConnection();

  if($conn)
  {
    $index = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
    $row = $DbAccess->getJob($index,false);
    //Se trova risultato
    if($row)
    {                 
      $HTML = str_replace('{{ Title }}',trim($row["Title"]),$HTML);
      $HTML = str_replace('{{ Description }}',trim($row["Description"]),$HTML);
      $HTML = str_replace('{{ Payment }}',trim($row["Payment"]),$HTML);
      $HTML = str_replace('{{ Status }}',trim($row["Status"]),$HTML);
      $HTML = str_replace('{{ Tipology }}',trim($row["Tipology"]),$HTML);
      $HTML = str_replace('{{ Date }}',trim($row["Date"]),$HTML);
      $HTML = str_replace('{{ Expiring }}',trim($row["Expiring_time"]),$HTML);

      $bids =$DbAccess->getBids($index);
      if($bids)
      {
        $HTMLBids ='<div id="bids">';
        foreach($bids as $B){
          $HTMLBids.= '<div class="bid">
                        <p><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></p>
                        <p>User Price: '.trim($B["Price"]).'</p>
                        <p>Description: '.trim($B["Price"]).'</p>
                      </div>';
        }
        $HTMLBids .='</div>';
        $HTML= str_replace('<div id="bids"></div>',$HTMLBids,$HTML);
      }
      else
      {
        $HTML = preg_replace('/<div id="bids"><\/div>/','<div id="bids"><p> No bids are currently up for this job offer! Check again later!</p></div>',$HTML);
      }

    } //Se non trova un risultato
    else
    {
      $HTML = str_replace( '{{ Title }}', 'No Info Available' ,$HTML);
      $HTML = preg_replace('/<div id="JobInfo">.*?.<\/div><\/div>/','<div id="content"><p> No Info are currently available about this specific Job</p></div>',$HTML);
      //$HTML = str_replace('<div id="viewOffer">'.*?.'</div>','<div id="content"><p> There is nothing to be seen here </p></div>',$HTML);
    }
  }
  echo $HTML;    
}
else
  header("Location:..".DIRECTORY_SEPARATOR."PHP".DIRECTORY_SEPARATOR."Login.php");    

?>