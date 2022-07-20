<?php
require_once 'DBAccess.php';

session_start();

if(isset($_SESSION['user_Username']))
{
	// Ottengo Valori da Pagina Statica
	$url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'ViewJob.html';
	$HTML = file_get_contents($url);
	
  // Replacing User Profile
	$HTMLContent = '<li><a href="Welcome.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
	$HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
	
  $self=true;
	$PJob = false;
  $DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	
  $index = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
    $_SESSION['Code_job'] = $index;
    $row = $DBAccess->getJob($index,true);

  
  //Se trova risultato
    if($row)
    {   

		if(array_key_exists('Status',$row))
		$tags = $DBAccess->getTags($index,2);
		else
		$tags = $DBAccess->getTags($index,1);
			$end=false;
			// Past Job
		if(array_key_exists('Status',$row)){
			$PJob = true;
			$status=$row["Status"];
			if($status!='Deleted')
			$status='Terminated';
		} // Current Job Terminated
		else if (strtotime((new DateTime())->format("Y-m-d H:i:s")) > strtotime($row['Expiring'])){
			$status='Terminated';
			$end=true;
		}
		else // Current Job Active
			$status='Active';

    // Carico i risultati da DB
		$HTML = str_replace('{{ Title }}',trim($row["Title"]),$HTML);
		$HTML = str_replace('{{ Creator }}','<a href="ViewUser.php?Code_User='.trim($row["Code_user"]).'"><abbr title="informations">Info</abbr> on the Creator</a>',$HTML);
		$HTML = str_replace('{{ Description }}',trim($row["Description"]),$HTML);
		$pay = trim($row["Payment"]);
		$HTML = $pay==0 ? str_replace('{{ Payment }}','Total Payment at once',$HTML) : str_replace('{{ Payment }}','Payment per hour',$HTML);
		$HTML = str_replace('{{ Min Payment }}',trim($row["P_min"]),$HTML);
		$HTML = str_replace('{{ Max Payment }}',trim($row["P_max"]),$HTML);
		$HTML = str_replace('{{ Status }}',$status,$HTML);
		$HTML = str_replace('{{ Tipology }}',trim($row["Tipology"]),$HTML);
		$HTML = str_replace('{{ Date }}',trim($row["Date"]),$HTML);
		$HTML = str_replace('{{ Expiring }}',trim($row["Expiring"]),$HTML);
		$winner='';
		if(array_key_exists('Code_winner',$row) AND isset($row["Code_winner"]))
			$winner = '<a href="ViewUser.php?Code_User='.trim($row["Code_winner"]).'">More informations on the Winner</a>';
		$HTML = str_replace('{{ Winner }}',$winner,$HTML);
		
    // Admin Actions
		$adminActions = '';
		if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) {
			$urlContent = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormAdminJob.html';
			$adminActions .= file_get_contents($urlContent);  
			$adminActions = str_replace('<code/>',$index, $adminActions);
			$adminActions = str_replace('<job/>',($PJob ? 'pastjob' : 'offer'), $adminActions);
			$adminActions = str_replace('{{job}}',($PJob ? 'job' : 'offer'),$adminActions);
		}
			
		$HTML = str_replace('<admin/>',$adminActions,$HTML);
	
    // Tags
		if($tags) {
			$HTMltags ='<ul>';
			foreach($tags as $name=>$value) {
				$HTMltags.='<li><a href="FindJob.php?tag='.$value.'">'.$name.'</a></li>';
			}
		$HTMltags.='</ul>';
		}
		else
			$HTMltags = 'This job has no tags';

		$HTML = str_replace('<tags/>',$HTMltags,$HTML);
    
		if(isset($_GET['result']))
		{
			$tmp='';
			$getValue=trim($_GET['result']);
			if( $getValue == 'cancelTrue')
				$tmp='<p class="result"> Job successfully cancelled</p>';
			else if( $getValue == 'cancelFalse')
				$tmp='<p class="result"> There was an error with the cancellation of the job, please try again later</p>';
			else if( $getValue == 'terminateTrue')
				$tmp='<p class="result"> Job termination was successful </p>';
			else if( $getValue == 'terminateFalse')
				$tmp='<p class="result"> There was an error with the termination of the job, please try again later</p>';
			else if( $getValue == 'removeTrue')
				$tmp='<p class="result"> Bid removed successfully </p>';
			else if( $getValue == 'removeFalse')
				$tmp='<p class="result"> There was an error with the deletion of the bid, please try again later</p>';
			$HTML = str_replace('<result/>',$tmp,$HTML);
		}
		else if (isset($_GET['winner']) ) {
			$tmp='';
			$getValue=trim($_GET['winner']);
			if( $getValue == 'errwin')
				$tmp='<p class="result"> There was an error with the code of the winner, please try to select <a href="#winner">the winner</a> again.</p>';
			else if( $getValue == 'errcode')
				$tmp='<p class="result"> There was an error with the code of the winner, please try to select the winner again.</p>';
			else if( $getValue == 'wsuccess')
				$tmp='<p class="result"> The winner was selected successfully. </p>';
			else if( $getValue == 'wfail')
				$tmp='<p class="result"> There was an error with the selection of the winner, please try again. </p>';
			$HTML = str_replace('<result/>',$tmp,$HTML);
		}
		else if (isset($_GET['bid']) ) {
			$tmp='';
			$getValue=trim($_GET['bid']);
			if( $getValue == 'errPrice')
				$tmp='<p class="result"> There was an error with the price of the bid, please try to select <a href="#Price">the price</a> again.</p>';
			else if( $getValue == 'errDesc')
				$tmp='<p class="result"> There was an error with the description of the bid, please try to select <a href="#Description">the description</a> again.</p>';
			else if( $getValue == 'succ')
				$tmp='<p class="result"> The bid was created successfully. </p>';
			$HTML = str_replace('<result/>',$tmp,$HTML);
		}
		else if (isset($_GET['feedback']) ) {
			$tmp='';
			$getValue=trim($_GET['feedback']);
			if( $getValue == 'errStar')
				$tmp='<p class="result"> There was an error with stars, please insert them again.</p>';
			else if( $getValue == 'errComm')
				$tmp='<p class="result"> There was an error with the comment, please try to insert it again.</p>';
			else if( $getValue == 'succ')
				$tmp='<p class="result"> The feedback was created successfully. </p>';
			else if( $getValue == 'fail')
				$tmp='<p class="result"> There was an error with the creation of the feedback, please try again. </p>';
			$HTML = str_replace('<result/>',$tmp,$HTML);
		}
		else
			$HTML = str_replace('<result/>','',$HTML);
	
		// Controllo se Utente è Owner ed è PastJob
		if($_SESSION['user_ID']==trim($row["Code_user"]) && !$PJob) {
			$OwnerActions = '<p id="cancel"> <a href="Modules'. DIRECTORY_SEPARATOR .'OfferCancel.php" class="cancel">Cancel this Job Offer</a></p>
			<p id="terminate">'.($status!= 'Terminated' ? '<a href="Modules'. DIRECTORY_SEPARATOR .'OfferTerminate.php" class="terminate">Terminate this Job Offer</a>': 'Offer is already terminated').'</p>';
			$HTML = str_replace('{{ owner options }}',$OwnerActions,$HTML); 
		}
		else
			$HTML = str_replace('{{ owner options }}','',$HTML); 

		if($status=='Active' OR ($status=='Terminated' AND $end==true)) {
			$bids =$DBAccess->getBids($index);
			if($bids)
			{
				if($end == true && $_SESSION['user_ID']==trim($row["Code_user"]))
				{
					$HTMLChooseWinner = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormChooseWinner.html');
					$offerers = '';
					foreach($bids as $B){
						$offerers.='<div>
						<label><input type="radio" name="winner" value="'.$B["Code"] .'" required>
						<img class="icons" src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR .$B["PFP"].'" alt="profile picture of user '.$B["Nickname"].'"><a href="ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></label>
						<p><span>User Price</span> : '.trim($B["Price"]).'</p>
						<p><span>Description</span> : '.trim($B["Description"]).'</p></div>';
					}
					$HTMLChooseWinner = str_replace('<offerers/>',$offerers,$HTMLChooseWinner);
					
					$HTML = str_replace('<bids/>',$HTMLChooseWinner,$HTML);
				}
				else
				{
					$HTMLBids ='<div id="bids">';
							
					foreach($bids as $B){
						$review = $DBAccess->getUserReview($B["Code"]);
						$HTMLBids.= '<div class="bid">
						<img class="icons" src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR .$B["PFP"].'" alt="profile picture of user '.$B["Nickname"].'">
								<div class="bidData"><p><a href="ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></p>
								<p><span>User Price</span> : '.trim($B["Price"]).'</p>
								<p><span>Description</span> : '.trim($B["Description"]).'</p>
								<p><span>Average Review Rating</span> : '.($review? $review['AvgStar'] : 'No review average available').'</p></div>';
						if($B["Code"]==$_SESSION['user_ID']){
							$self=false;
							$HTMLBids.='<a href="Modules'. DIRECTORY_SEPARATOR .'RemoveBid.php?code='. $index .'">delete your bid</a></div>';          
						}
						else
							$HTMLBids.='</div>';            
					}
					$HTMLBids .='</div>';
					$HTML = str_replace('<bids/>',$HTMLBids,$HTML);
					
				}
			}
			else
				$HTML = str_replace('<bids/>','<div id="bids"><p class="error"> No bids are currently up for this job offer. Please, check again later.</p></div>',$HTML);
		
			if($_SESSION['user_ID']!=trim($row["Code_user"]) && $self AND $status=='Active')
			{
				$_SESSION['Code_Job'] = filter_var($_SESSION['Code_job'], FILTER_VALIDATE_INT);
				// Se non sei il creatore del lavoro, puoi aggiungere una bid
				$HTMLFormBid=file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormAddBid.html');
				$HTML= str_replace('<addBid/>',$HTMLFormBid,$HTML);
			}
			else
				$HTML= str_replace('<addBid/>','',$HTML);

			$HTML = str_replace('<feedback/>','',$HTML);
		}
		else if($status=='Terminated' AND $end==false) {
			$HTML = str_replace('<bids/>','',$HTML);
			$HTML = str_replace('<addBid/>','',$HTML);
			$feedback = $DBAccess->getJobReview($index);
			if(!$feedback) {
				if( isset($_SESSION['user_ID']) && $_SESSION['user_ID'] == $row['Code_user']) {
					$_SESSION['Code_job'] = $_GET['Code_job'];
					$urlForm = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormFeedback.html';
					$HTMLForm = file_get_contents($urlForm);
					$HTML = str_replace( '<feedback/>', $HTMLForm ,$HTML);
					// Aggiungo form per aggiungere feedback
				}
				else // Caso in cui non c'è feedback e non ho l'autorità per aggiungerlo (non sono creatore dell'offerta di lavoro)
					$HTML = str_replace('<feedback/>','',$HTML);
			}
			else {
				$User = $DBAccess->getUser(trim($feedback["C_Rew"]));
				$urlFeed = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'Feedback.html';
				$HTMLFeed = file_get_contents($urlFeed);
				$HTMLFeed = str_replace("{{Nickname}}",$User["Nickname"],$HTMLFeed);
				$HTMLFeed = str_replace("{{Stars}}",trim($feedback["Stars"]),$HTMLFeed);
				$HTMLFeed = str_replace("{{Date}}",trim($feedback["Date"]),$HTMLFeed);
				$HTMLFeed = str_replace("{{Comments}}",trim($feedback["Comments"]),$HTMLFeed);
        
				$HTML = str_replace('<feedback/>',$HTMLFeed,$HTML);
			}
		}
		else if($status=='Terminated'){
			$HTML = str_replace('<bids/>','<div id="bids"><p class="error"> This job offer is currently :'.$status .'</p></div>',$HTML);
			$HTML = str_replace('<form id="addBid"></form>','',$HTML);
			$HTML = str_replace('<feedback/>','',$HTML);
		}
		else 
		{
			$HTML = str_replace('<bids/>','',$HTML);
			$HTML = str_replace('<addBid/>','',$HTML);
			$HTML = str_replace('<feedback/>','',$HTML);
		}
	} //Se non trova un risultato
  else {
		$HTML = str_replace( '{{ Title }}', 'No Info Available' ,$HTML);
		$HTML = str_replace( '<result/>', '' ,$HTML);
		$HTML = preg_replace('/<div id="JobInfo" class="box">((\n|.)*)<\/div>/','<div id="NoJob"><p> It seems like this job doesn\'t exists. If you want to find more jobs, you can search in <a href="findjob.php">find job</a>.</p></div>',$HTML);
  }
	$DBAccess->closeDBConnection();
	echo $HTML;    
}
else {
  $value = filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
  header("Location:Login.php?view=ViewJob&code=".$value);    
}
 

?>