<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
	// Ottengo Valori da Pagina Statica
	$url = '..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'ViewOffer.html';
	$HTML = file_get_contents($url);
	// Replacing User Profile
	$HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
	$HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
	$self=true;
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	$index = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
    $_SESSION['Code_job'] = $index;
    $row = $DBAccess->getJob($index,true);
	  $tags = $DBAccess->getTags($index,1);
    //Se trova risultato
    if($row)
    {                 
      $HTML = str_replace('{{ Title }}',trim($row["Title"]),$HTML);
      $HTML = str_replace('{{ Creator }}','<a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.trim($row["Code_user"]).'">Info on the Creator</a>',$HTML);
      $HTML = str_replace('{{ Description }}',trim($row["Description"]),$HTML);
      $pay = trim($row["Payment"]);
      $HTML = $pay==0 ? str_replace('{{ Payment }}','Payment by hour',$HTML) : str_replace('{{ Payment }}','Total Payment at once',$HTML);
      $HTML = str_replace('{{ Min Payment }}',trim($row["P_min"]),$HTML);
      $HTML = str_replace('{{ Max Payment }}',trim($row["P_max"]),$HTML);
      $HTML = str_replace('{{ Status }}',trim($row["Status"]),$HTML);
      $HTML = str_replace('{{ Tipology }}',trim($row["Tipology"]),$HTML);
      $HTML = str_replace('{{ Date }}',trim($row["Date"]),$HTML);
      $HTML = str_replace('{{ Expiring }}',trim($row["Expiring"]),$HTML);
      /// Admin Actions
      $adminActions = '';
      if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) 
      {
        $adminActions .= '<a href="AdminAction.php">Freeze this Offer</a>';  
      }
      else {
        $adminActions .= '';
      }
        
      $HTML = str_replace('<admin/>',$adminActions,$HTML);

      $HTMltags ='';
      if($tags) {
        $HTMltags.='<ul>';
        foreach($tags as $name=>$value) {
          $HTMltags.='<li><a href="FindJob.php?tag='.$value.'">'.$name.'</a></li>';
        }
        $HTMltags.='
          </ul>';
    }
    $HTML = str_replace('<tags/>',$HTMltags,$HTML);

		if(trim($row["Status"])!='Frozen' && trim($row["Status"])!='Expired')
		{
			$bids =$DBAccess->getBids($index);
			if($bids)
			{
				$HTMLBids ='<div id="bids">';
				
				foreach($bids as $B){
				$HTMLBids.= '<div class="bid">
								<p><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></p>
								<p><span>User Price</span> : '.trim($B["Price"]).'</p>
								<p><span>Description</span> : '.trim($B["Description"]).'</p>';
				if($B["Code"]==$_SESSION['user_ID']){
					$self=false;
					$HTMLBids.='<a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'RemoveBid.php?code='. $index .'">delete your bid</a></div>';          
				}
				else
					$HTMLBids.='</div>';            
				}
				$HTMLBids .='</div>';
				$HTML = str_replace('<div id="bids" class="box"></div>',$HTMLBids,$HTML);
			}
			else
				$HTML = preg_replace('/<div id="bids" class="box"><\/div>/','<div id="bids"><p class="error"> No bids are currently up for this job offer! Check again later!</p></div>',$HTML);
		
			if($_SESSION['user_ID']==trim($row["Code_user"]))
			{
				$OwnerActions = '<p id="cancel"> <a href="OfferCancel.php" class="cancel">Cancel this Job Offer</a></p>
				<p id="terminate"><a href="OfferTerminate.php" class="terminate">Terminate this Job Offer</a></p>';
				$HTML = str_replace('{{ owner options }}',$OwnerActions,$HTML); 
			}
      else
        $HTML = str_replace('{{ owner options }}','',$HTML); 
			
			if($_SESSION['user_ID']!=trim($row["Code_user"]) && $self)
			{
				$_SESSION['Code_Job'] = filter_var($_SESSION['Code_job'], FILTER_VALIDATE_INT);
				// Se non sei il creatore del lavoro, puoi aggiungere una bid
				$HTMLFormBid='<form id="addBid" action="../PHP/AddBid.php" method="post">
				<fieldset>
				<legend>Add a new Bid </legend>
				<label for="Price" id="labelPrice">  Offer\'s Price : </label>
				<input type="number" name="Price" id="Price" min="0"/>
				<label for="Description" id="labelDescription">  Bid Description : </label>
				<textarea id="Description" name="Description"></textarea>
				<button type="submit" name="addyourBid" id="addyourBid">Send your Bid</button>
				</fieldset>
				</form>';
				$HTML= str_replace('<form id="addBid"></form>',$HTMLFormBid,$HTML);
			}
			else
				$HTML= str_replace('<form id="addBid"></form>','',$HTML);
		}
		else
			$HTML = preg_replace('/<div id="bids" class="box"><\/div>/','<div id="bids"><p class="error"> This job offer is currently :'.trim($row["Status"]) .'</p></div>',$HTML);
    } //Se non trova un risultato
    else
    {
		$HTML = str_replace( '{{ Title }}', 'No Info Available' ,$HTML);
		$HTML = preg_replace('/(?<=<div id="JobInfo">)((\n|.)*)(?=<\/div>)/','<div id="content"><p> No Info are currently available about this specific Job</p></div>',$HTML);
    }
	$DBAccess->closeDBConnection();
	echo $HTML;    
}
else {
  $value = filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
  header("Location:Login.php?view=ViewOffer&code=".$value);    
}
 

?>