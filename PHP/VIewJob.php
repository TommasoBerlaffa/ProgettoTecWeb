<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
require_once 'DBAccess.php';
require_once 'Modules'. DIRECTORY_SEPARATOR .'ErrorMessages.php';

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
	$row ='';
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit();
	}
	
	if(isset($_GET['Code_job'])){
		$index = filter_var($_GET['Code_job'], FILTER_VALIDATE_INT);
		if($index!==false)
			$row = $DBAccess->getJob($index,true);
	}
  //Se trova risultato
    if($row)
    {   
		$creator = $DBAccess->getUser($row['Code_user']);
		if($creator['Status']=='Active' OR (isset($_SESSION['Admin']) && $_SESSION['Admin']==1) OR $creator['Code_user']=$_SESSION['user_ID']){
			$review = $DBAccess->getUserReview($row['Code_user'])["AvgStar"];
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
			$HTML = str_replace('{{ Creator }}','<a href="ViewUser.php?Code_User='.trim($row["Code_user"]).'">'.$creator['Nickname'].'</a>    '.$creator['Nationality'].', '.$creator['City'].'      '.($review==0? '(this user has 0 reviews)': (round($review,1).' points review')),$HTML);
			$HTML = str_replace('{{ Description }}',trim($row["Description"]),$HTML);
			
			$HTML = str_replace('{{ Payment }}','$ '.trim($row["P_min"]).' - '.trim($row["P_max"]).($row["Payment"]==0? '':' /hr'),$HTML);
			
			$HTML = str_replace('{{ Status }}',$status,$HTML);
			$HTML = str_replace('{{ Tipology }}',trim($row["Tipology"]),$HTML);
			$HTML = str_replace('{{ Date }}',$row["Date"],$HTML);
			$HTML = str_replace('{{ Expiring }}',trim($row["Expiring"]),$HTML);
			$winner='';
			$winnerInfos='';
			if(array_key_exists('Code_winner',$row) AND isset($row["Code_winner"])) {
				$wInfos = $DBAccess->getUser(trim($row["Code_winner"]));
				$wbid = $DBAccess->getWinnerBid($index);
		
				$winner = '<span>Winner of this offer :</span><a href="ViewUser.php?Code_User='.trim($row["Code_winner"]).'">'.trim($wInfos["Nickname"]).'</a>';
				$winnerInfos = '<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . trim($wInfos['Picture']) .
				'" alt="Profile Picture of '. trim($wInfos["Nickname"]).'" id="winnerPic"><p id="winnerInfos">
				<span>Name & Surname :</span> '. trim($wInfos["Name"]).' '.trim($wInfos["Surname"]).'<br>
				<span>Email :</span> '. trim($wInfos["Email"]).'<br>
				<span>Phone Number :</span> '.trim($wInfos["Phone"]).'</p>
				<p id="winnerBid"><span>User price:</span> $'.(($wbid!=null)? trim($wbid["User_price"]):' - ').'<br><span>Bid description: </span>'.(($wbid!=null)? trim($wbid["Bid_selfdescription"]):' - ').'</p>';
				
			}
	
			$HTML = str_replace('{{ Winner }}',$winner,$HTML);
			$HTML = ($winnerInfos !='' ) ? str_replace('{{ WinnerInfo }}',$winnerInfos,$HTML) : preg_replace('/<div id="winnerwrapper">((\n|.)*)<\/div>/',$winnerInfos,$HTML);
			
			// Tags
			if($tags) {
				$HTMltags ='';
				foreach($tags as $name=>$value) {
					$HTMltags.='<a href="FindJob.php?tag='.$value.'">'.$name.'</a>,';
				}
				$HTMltags= rtrim($HTMltags,',');
			}
			else
			{
				$HTML = str_replace('<p><span>Job Tags :</span></p>','<p><span>Job Tags : This Job has no Tags</span></p>',$HTML);
				$HTMltags = '';
			}

			$HTML = str_replace('<tags/>',$HTMltags,$HTML);
		
		
			if(isset($_SESSION['error']))
				$HTML = str_replace('<result/>',ErrorMessage(),$HTML);
			else
				$HTML = str_replace('<result/>','',$HTML);
			unset($_SESSION['error']);
			
		
			// Controllo se Utente è Owner ed è PastJob
			if($_SESSION['user_ID']==trim($row["Code_user"]) && !$PJob) {
				$OwnerActions = '<p id="cancel"> <a href="Modules'. DIRECTORY_SEPARATOR .'OfferCancel.php?Code_job='. $index .'" class="cancel">Cancel this Job Offer</a></p>
				<p id="terminate">'.($status!= 'Terminated' ? '<a href="Modules'. DIRECTORY_SEPARATOR .'OfferTerminate.php?Code_job='. $index .'" class="terminate">Terminate this Job Offer</a>': 'Offer is already terminated').'</p>';
				$HTML = str_replace('{{ owner options }}',$OwnerActions,$HTML); 
			}
			else
				$HTML = str_replace('{{ owner options }}','',$HTML); 
	
			//se l'offerta è attiva oppure è scaduta
			if($status=='Active' OR ($status=='Terminated' AND $end==true)) {
				$bids =$DBAccess->getBids($index);
				if($bids)
				{
					//scaduta e utente è creatore => scegli vincitore
					if($end == true && $_SESSION['user_ID']==trim($row["Code_user"]))
					{
						$HTMLChooseWinner = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormChooseWinner.html');
						$offerers = '';
						foreach($bids as $B){
							$offerers.='<div class="bid">
							<label><input type="radio" name="winner" value="'.$B["Code"] .'" required>
							<img class="icons" src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR .$B["PFP"].'" alt="profile picture of user '.$B["Nickname"].'">
							<a href="ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></label>
							<p><span>User Price : </span> $ '.trim($B["Price"]).'</p>
							<p><span>Description : </span> '.trim($B["Description"]).'</p></div>';
						}
						$HTMLChooseWinner = str_replace('<offerers/>',$offerers,$HTMLChooseWinner);
						
						$HTML = str_replace('<bids/>',$HTMLChooseWinner,$HTML);
					}
					//altrimenti lista normale delle offerte
					else
					{
						$HTMLBids ='<div id="bids">';
								
						foreach($bids as $B){
							$review = $DBAccess->getUserReview($B["Code"]);
							$HTMLBids.= '<div class="bid">
							<img class="icons" src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR .$B["PFP"].'" alt="profile picture of user '.$B["Nickname"].'">
									<div class="bidData"><p><a href="ViewUser.php?Code_User='.$B["Code"].'">'.$B["Nickname"].'</a></p>
									<p><span>User Price : </span> $ '.trim($B["Price"]).'</p>
									<p><span>Description : </span> '.trim($B["Description"]).'</p>
									<p><span>Average Review Rating : </span>'.($review==0? 'No review average available' : round($review['AvgStar'],1) ).'</p></div>';
							//se questa offerta è dell'utente corrente può sceglere di cancellarla
							if($B["Code"]==$_SESSION['user_ID']){
								$self=false;
								$HTMLBids.='<a class="deleteBid" href="Modules'. DIRECTORY_SEPARATOR .'RemoveBid.php?Code_job='. $index .'">delete your bid</a></div>';          
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
				
				// Se non sei il creatore del lavoro, puoi aggiungere una bid
				if($_SESSION['user_ID']!=trim($row["Code_user"]) && $self AND $status=='Active')
				{
					$HTMLFormBid=file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'AddBid.html');
					$HTML= str_replace('<addBid/>',$HTMLFormBid,$HTML);
					$left=DateTime::createFromFormat('Y-m-d H:i:s', $row["Expiring"])->diff(new DateTime());
					$leftString='';
					if($left->m)
						$leftString.=$left->m.' Months<br>'.$left->d.' Days';
					else if($left->d>2)
						$leftString.=$left->d.' Days';
					else if($left->d>0 and $left->d<3)
						$leftString.=$left->d.' Days<br>'.$left->h.' Hours';
					else
						$leftString.=$left->i.' Minutes';
					$HTML= str_replace('<time/>',$leftString,$HTML);
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
			$HTML = str_replace('<codejob/>',$index,$HTML);
			
			
			if(($status=='Terminated' AND (isset($row['Status']) && $row['Status']==='Deleted')) AND ((isset($_SESSION['Admin']) && $_SESSION['Admin']==1) OR $creator['Code_user']==$_SESSION['user_ID'])){
				$res=$DBAccess->getJobDelete($index);
				if($res)
					$HTML = str_replace('<deletereason/>','<p class="resultfail"> This job has been deleted by: '.$res['Nickname'].' on: '.$res['Date'].'   '.$res['Comments'].'</p>',$HTML);
			}
		
		}
		else{
			$HTML = str_replace("{{ Title }}",trim($row["Title"]),$HTML);
			$HTML = preg_replace('/<div id="JobInfo" class="box">((\n|.)*)<\/div>/',
			'<div id="NoJob"><p> The creator of this job is Banned.</p></div>',$HTML);
			$HTML = str_replace('<bids/>','',$HTML);
			$HTML = str_replace('<addBid/>','',$HTML);
			$HTML = str_replace('<feedback/>','',$HTML);
		}
		
		// Admin Actions
		$adminActions = '';
		if ( $status != 'Deleted' AND $status != 'Terminated'){
			if(isset($_SESSION['Admin']) && $_SESSION['Admin']==1) {
				$urlContent = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'FormAdminJob.html';
				$adminActions .= file_get_contents($urlContent);  
				$adminActions = str_replace('<code/>',$index, $adminActions);
				$adminActions = str_replace('<job/>',($PJob ? 'pastjob' : 'job'), $adminActions);
				$adminActions = str_replace('{{job}}',($PJob ? 'job' : 'offer'),$adminActions);
			}
		}
		$HTML = str_replace('<admin/>',$adminActions,$HTML);
	} //Se non trova un risultato
	else {
		$HTML = str_replace( '{{ Title }}', 'No Info Available' ,$HTML);
		$HTML = str_replace( '<result/>', '' ,$HTML);
		$HTML = preg_replace('/<div id="JobInfo" class="box">((\n|.)*)<\/div>/','<div id="NoJob"><p> It seems like this job doesn\'t exists. If you want to find more jobs, you can search in <a href="findjob.php">find job</a>.</p></div>',$HTML);
	}
	$HTML = str_replace('<deletereason/>','',$HTML);
	$DBAccess->closeDBConnection();
	echo $HTML;    
}
else {
  if(isset($_GET['Code_job'])){
	$_SESSION['redirect']='ViewJob.php?Code_job='.filter_var($_GET['Code_job'],FILTER_SANITIZE_NUMBER_INT);
	header("Location:Login.php");
  }
  else
	header("Location:Index.php");   
}
 
exit();
?>