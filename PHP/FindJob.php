<?php
	// Inizio Sessione 
	session_start();
	require_once 'Util.php';
	require_once 'DBAccess.php';
	
	// Variabili pagina HTML e Switch
	$url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html';
	$HTML = file_get_contents($url);
	$HTMLContent ='';
	
	// Controllo se variabile sessione è presente 
	if(isset($_SESSION['user_Username']))
	{
		$HTML = str_replace('<createjob/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'LoadCreateJob.php">
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
	$Pay=0;
	$date=9999999;
	$tag=array();
    $tagName ='';
	$page=1;
	
	if(isset($_GET['Tipology']))
		$type=filter_var ( $_GET['Tipology'], FILTER_SANITIZE_STRING);
	if(isset($_GET['PayMin']))
		$min=filter_var ( $_GET['PayMin'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_GET['Date']))
		$date=filter_var ( $_GET['Date'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_GET['Page']))
		$page=filter_var ( $_GET['Page'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_GET['tag']))
		$tag=filter_var ( $_GET['tag'], FILTER_SANITIZE_NUMBER_INT);
	else{
		if(!isset($_SESSION['TagList'])){
			$_SESSION['TagList']=array();
		}
		$tag=$_SESSION['TagList'];
	}
  if(isset($_GET["Pay"]))
    $Pay=filter_var ( $_GET['tag'], FILTER_SANITIZE_NUMBER_INT);    
	
	//if there is a post from the filter form than override whatever got from the GET 
	if(isset($_POST['filter'])){
		if(isset($_POST["Tipology"]))
			$type =  filter_var ( $_POST["Tipology"], FILTER_SANITIZE_STRING);
    if(isset($_POST["Pay1"]) && !isset($_POST["Pay2"]))
      $Pay=1;
    else if( !isset($_POST["Pay1"]) && isset($_POST["Pay2"]))
      $Pay=2;
    else 
      $Pay=0;
    if(isset($_POST["PayMin"]))
			$min =  intval(filter_var ( $_POST["PayMin"], FILTER_SANITIZE_STRING));
		if(isset($_POST["Date"]))
			$date =  intval(filter_var ( $_POST["Date"], FILTER_SANITIZE_STRING));
		$page=1;
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
  
  $HtmlCheckboxPay='
    <label id="labelPay">Choose which method of payment :</label>
    <input type="checkbox" id="Pay1" name="Pay1" value="Pay1" '. (($Pay==1 OR $Pay==0) ? 'checked' : '').'>
    <label for="Pay1" id="labelPay1"> All at once</label><br>
    <input type="checkbox" id="Pay2" name="Pay2" value="Pay2"'. (($Pay==2 OR $Pay==0) ? 'checked' : '').'>
    <label for="Pay2" id="labelPay2"> By worked hours</label>';
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	
	$NumberPages=0;
	
	if(isset($_GET['tag'])){
		$tagName = $DBAccess->searchTagName($tag);
		prof_flag("search Job algor");
		$NumberPages = $DBAccess->searchJob(true,$type,$min,$date,$page,array($tag),$Pay);
		$result = $DBAccess->searchJob(false,$type,$min,$date,$page,array($tag),$Pay);
	}
	else{
		$NumberPages = $DBAccess->searchJob(true,$type,$min,$date,$page,$tag,$Pay);
		$result = $DBAccess->searchJob(false,$type,$min,$date,$page,$tag,$Pay);
	}
	$NumberPages=ceil($NumberPages / 5);
	
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
						<p class="date"><span>Date</span> : '.explode(' ',$row["Date"])[0].'</p>
						<p class="type"><span>Tipology</span> : '.trim($row["Tipology"]).'</p>
            <p class="Pay"><span>Payment Type</span> : '.(trim($row["Payment"])==0? 'Payment by Hour' : 'All at once').'</p>
						<p class="minPay"><span><abbr title="minimum">Min</abbr> Pay</span> : $'.trim($row["P_min"]).'</p>
						<p class="bids"><span>Bids</span> : '.$bids.'</p>
						<p class="description"><span>Description</span> : <br>'.$desc.'</p>';
			
			$jobTags=$DBAccess->getTags($row['Code_job'],1);
			if($jobTags) {
				$HtmlContent .='<ul class="tags">';
				foreach($jobTags as $name=>$value){
					$HtmlContent .='
					<li><a href="?tag='.$value.'">'.$name.'</a></li>';
				}
				$HtmlContent .='</ul>';
			}
			$HtmlContent .='</div>';
		}
		$HtmlContent .='<paging/></div>';
	}
	else
		$HtmlContent.='<p>No Jobs Currently Available. Try to change your filters in the <a href="#formFilter">filtering form</a> to find more Jobs.</p><img src="../IMG/Tumbleweed.gif" alt="Animated image of a rolling tumbleweed across the desert" id="NoAvailableJob"></div>';
	
	$DBAccess->closeDBConnection();
	
	
	$urlType=($type)? '&Tipology='.$type : '';
	$urlMin=($min>0)? '&PayMin='.$min : '';
	$urlDate=($date!=9999999)? '&Date='.$date : '';
	$urlTag=(is_string($tag))? '&tag='.$tag : '';
	$urlPay=($Pay)? '&pay='.$Pay :'';

	$pageHTML = '<div id="pages">';
    if($page > 1)
      $pageHTML .= '<a title="first page" href="FindJob.php?Page=1' . $urlType . $urlMin . $urlDate . $urlTag . $urlPay .'">1</a>';
    if($page > 2)
      $pageHTML .= '<a title="previous page" href="FindJob.php?Page=' . ($page - 1) . $urlType . $urlMin . $urlDate . $urlTag .  $urlPay . '"><<</a>';
    $pageHTML .= '<span>' . $page . '</span>';
    if($page < $NumberPages-1)
      $pageHTML .= '<a title="next page" href="FindJob.php?Page=' . ($page + 1) . $urlType . $urlMin . $urlDate . $urlTag . $urlPay . '">>></a>';
    if($page < $NumberPages)
      $pageHTML .= '<a title="last page" href="FindJob.php?Page=' . $NumberPages . $urlType . $urlMin . $urlDate . $urlTag . $urlPay . '">' . $NumberPages . '</a>';
    $pageHTML .= '</div>';
	
	
	
	
	$HTML = str_replace('<div id="jobList"><h1>List of job offers<Title_complete/></h1><paging/></div>',$HtmlContent,$HTML);
	$HTML = str_replace('<paging/>', $pageHTML, $HTML);
	$HTML = str_replace('<TipologySelect/>',$HtmlTypologySelect,$HTML);
	$HTML = str_replace('<paytype/>',$HtmlCheckboxPay,$HTML);
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
