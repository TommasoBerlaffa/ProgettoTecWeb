<?php
	require_once 'DBAccess.php';
	
	// Inizio Sessione 
	session_start();
	
	// Variabili pagina HTML e Switch
	$url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html';
	$HTML = file_get_contents($url);
	$HTMLContent ='';
	
	// Controllo se variabile sessione è presente 
	if(isset($_SESSION['user_Username']))
	{
		$HTML = str_replace('<createjob/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'CreateJob.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.png" class="icons"> Create an Offer </a></li>',$HTML);
		$HTMLContent = '<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
	}
	else
	{
		$HTML = str_replace('<createjob/>','',$HTML);
		$HTMLContent = '
		<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Login.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'login.png" class="icons"> Login </a></li>
		<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'Signup.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'book.png" class="icons"> Sign up </a></li>';
	}
	
	// Cambio Pagina
	$HTML = str_replace('<subpage/>',$HTMLContent,$HTML);
	
	$HtmlContent='<div id="jobList"><h1>List of job offers<Title_complete/></h1>';


	$type= 'Any';
	$min=0;
	$date=9999999;
	$tag=array();
    $tagName ='';
	
	if(isset($_GET['Tipology']))
		$type=filter_var ( $_GET['Tipology'], FILTER_SANITIZE_STRING);
	if(isset($_GET['PayMin']))
		$min=filter_var ( $_GET['PayMin'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_GET['Date']))
		$date=filter_var ( $_GET['Date'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_GET['tag']))
		$tag=filter_var ( $_GET['tag'], FILTER_SANITIZE_NUMBER_INT);
	else{
		if(!isset($_SESSION['TagList'])){
			$_SESSION['TagList']=array();
		}
		$tag=$_SESSION['TagList'];
	}
	
	//if there is a post from the filter form than override whatever got from the GET 
	if(isset($_POST['filter'])){
		if(isset($_POST["Tipology"]))
			$type =  filter_var ( $_POST["Tipology"], FILTER_SANITIZE_STRING);
		if(isset($_POST["PayMin"]))
			$min =  intval(filter_var ( $_POST["PayMin"], FILTER_SANITIZE_STRING));
		if(isset($_POST["Date"]))
			$date =  intval(filter_var ( $_POST["Date"], FILTER_SANITIZE_STRING));
	}
	
	$HtmlTypologySelect='
		<option value="Any" '.($type=='Any'? 'selected':'').'>Any</option>
		<option value="Fulltime" '.($type=='Fulltime'? 'selected':'').'>Fulltime</option>
		<option value="Onetime" '.($type=='Onetime'? 'selected':'').'>Onetime</option>
		<option value="Urgent" '.($type=='Urgent'? 'selected':'').'>Urgent</option>
		<option value="Recruiter" '.($type=='Recruiter'? 'selected':'').'>Recruiter</option>
		';
	$HtmlDateSelect='
		<option value="9999999" '.($date==9999999? 'selected':'').'>Any</option>
		<option value="1" '.($date==1? 'selected':'').'>Last Hour</option>
        <option value="24" '.($date==24? 'selected':'').'>Last Day</option>
        <option value="168" '.($date==168? 'selected':'').'>Last Week</option>
        <option value="744" '.($date==744? 'selected':'').'>Last Month</option>
		';
	
	$DBAccess = new DBAccess();

	if(isset($_GET['tag'])){
		$tagName = $DBAccess->searchTagName($tag);
		$result = $DBAccess->searchJob($type,$min,$date,array($tag));
	}
	else
	  $result = $DBAccess->searchJob($type,$min,$date,$tag);
	
	if($result){
		foreach($result as $row)
		{
			$desc=$row["Description"];
			if(strlen($desc)>512){
				$desc=substr($desc,0,511);
				$desc=substr($desc,0,strrpos($desc, ' ') + 1).'...';
			}
			$bids= $DBAccess->getBids($row['Code_job']);
			if($bids)
				$bids=count($bids);
			else
				$bids=0;
			$HtmlContent .='<div class="job">
						<p class="title"><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewOffer.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a></p>
						<p class="date">Date: '.trim($row["Date"]).'</p>
						<p class="type">Tipology: '.trim($row["Tipology"]).'</p>
						<p class="minPay">Minimum Pay: $'.trim($row["P_min"]).'</p>
						<p class="bids">Bids: '.$bids.'</p>
						<p class="description">Description: '.$desc.'</p>';

			$jobTags=$DBAccess->getTags($row['Code_job'],1);
      if($jobTags) {
        $HtmlContent .='<ul>';
        foreach($jobTags as $name=>$value){
          $HtmlContent .='
                <li><a href="?tag='.$value.'">'.$name.'</a></li>';
        }
        $HtmlContent .='</ul>';
			}
      $HtmlContent .='</div>';

		}
		$HtmlContent .='</div>';
	}
	else
		$HtmlContent.='<p>No Jobs Currently Available</p></div>';
	
	
	$HTML = str_replace('<div id="jobList"><h1>List of job offers<Title_complete/></h1></div>',$HtmlContent,$HTML);
	$HTML = str_replace('<TipologySelect/>',$HtmlTypologySelect,$HTML);
	$HTML = str_replace('[payval]','value="'.$min.'"',$HTML);
	$HTML = str_replace('<DateSelect/>',$HtmlDateSelect,$HTML);
	$HTML = str_replace('<div id="jobList"></div>',$HtmlContent,$HTML);
	if(isset($_GET['tag'])){
		$HTML = str_replace('<GETsearch/>','?tag='.$tag,$HTML);
		$HTML = str_replace('<Title_complete/>',' matching '. $tagName .' tag',$HTML);
	}
  else
  {
    $HTML = str_replace('<GETsearch/>','',$HTML);
		$HTML = str_replace('<Title_complete/>','',$HTML);
  }
 
  // Apertura Pagina
  echo $HTML;

?>
