<?php
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
// Inizio Sessione 
	require_once 'Modules'. DIRECTORY_SEPARATOR .'Util.php';
	require_once 'DBAccess.php';
	
	// Variabili pagina HTML e Switch
	$HTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html');
	$HTMLContent ='';
	
	$TagModule = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TagsSearch.html');
	$HTML = str_replace('<TagModule/>',$TagModule,$HTML);
	
	// Controllo se variabile sessione è presente 
	if(isset($_SESSION['user_Username']))
	{
		$HTML = str_replace('<createjob/>','<li><a href="CreateJob.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'write.png" class="icons" alt=""> Create an Offer </a></li>',$HTML);
		$HTMLContent = '<li><a href="Welcome.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'UsrPrfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Your Profile Picture" id="profilepic" class="icons">User Profile</a></li>';
	}
	else
	{
		$HTML = str_replace('<createjob/>','',$HTML);
		$HTMLContent = '
		<li><a href="Login.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'login.png" class="icons" alt=""> Login </a></li>
		<li><a href="Signup.php">
		<img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR .'Icons'. DIRECTORY_SEPARATOR .'book.png" class="icons" alt=""> Sign up </a></li>';
	}
	
	// Cambio Pagina
	$HTML = str_replace('<subpage/>',$HTMLContent,$HTML);

	$HtmlContent='<ul id="jobList">';
	$pageHTML='';


	$type= 'Any';
	$min=0;
	$Pay=0;
	$date=9999999;
	$tag='';
    $tagName ='';
	$page=1;
	
	if(!isset($_SESSION['TagList'])){
		$_SESSION['TagList']=array();
	}
	
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
	if(isset($_GET["Pay"]))
    $Pay=filter_var ( $_GET['Pay'], FILTER_SANITIZE_NUMBER_INT);    
	
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
    <label id="labelPayment">Choose which method of payment :</label>
    <input type="checkbox" id="Pay1" name="Pay1" value="Pay1" '. (($Pay==1 OR $Pay==0) ? 'checked' : '').'>
    <label for="Pay1" id="labelPay1"> All at once</label><br>
    <input type="checkbox" id="Pay2" name="Pay2" value="Pay2" '. (($Pay==2 OR $Pay==0) ? 'checked' : '').'>
    <label for="Pay2" id="labelPay2"> By worked hours</label>';
	
	$DBAccess = new DBAccess();
	if(!($DBAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
	
	$NumberPages=0;
	
	if(isset($_GET['tag'])){
		$tagName = $DBAccess->searchTagName($tag);
		addTag($tagName,$tag);
	}
	
	$NumberPages = $DBAccess->searchJob(true,$type,$min,$date,$page,$Pay);
	$result = $DBAccess->searchJob(false,$type,$min,$date,$page,$Pay);
	
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
			//						<h2 class="title"><strong>'.$row["Title"].'</strong></h2>
			$HtmlContent .='
		<li class="job">
      <div class="jobTitle">
        <a class="title" href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJob.php?Code_job='.$row["Code_job"].'">'.$row["Title"].'</a>
			</div>
      <div class="jobDescription">
				<p class="description"><span>Description</span> : <br>'.trim(preg_replace('/\s+/', ' ', $desc)).'</p>
			</div>
			<div class="info">
				<p class="date">'.$leftString.' left</p>
				<p class="type">'.trim($row["Tipology"]).'</p>
				<p class="minPay">$'.trim($row["P_min"]). (trim($row["Payment"])==0? '' : ' / hr') .'</p>
				<p class="bids">'.($bids? ($bids>1? ($bids.' Bids') : ($bids.' Bid')) : 'No Bids').'</p>
			</div>';
			
			$jobTags=$DBAccess->getTags($row['Code_job'],1);
			if($jobTags) {
				$HtmlContent .='
			<ul aria-label="list of tags" class="tags">';
				foreach($jobTags as $name=>$value){
					$HtmlContent .='
				<li><a href="?tag='.$value.'">'.$name.'</a></li>';
				}
				$HtmlContent .='
			</ul>';
			}
			$HtmlContent .='
		</li>';
		}
		$HtmlContent .='
	</ul>';
	}
	else
		$HtmlContent.='<p>No Jobs Currently Available. Try to change your filters in the <a href="#formFilter">filtering form</a> to find more Jobs.</p><img id="NoAvailableJob" src="../IMG/Tumbleweed.gif" alt="Animated image of a rolling tumbleweed across the desert" id="NoAvailableJob"></div>';
	
	$DBAccess->closeDBConnection();
	
	if($result){
		$pageHTML ='';
		$urlType=($type)? '&amp;Tipology='.$type : '';
		$urlMin=($min>0)? '&amp;PayMin='.$min : '';
		$urlDate=($date!=9999999)? '&amp;Date='.$date : '';
		$urlPay=($Pay)? '&amp;pay='.$Pay :'';
		$get=$urlType . $urlMin . $urlDate . $urlPay;
		
		if($page>1)
			$pageHTML ='<li title="first page" ><a href="FindJob.php?Page=1' . $get .'">|&lt;</a></li>
		<li title="previous page" ><a href="FindJob.php?Page='. ($page - 1) . $get .'">&lt;</a></li>';
		else
			$pageHTML ='<li title="first page">|&lt;</li>
		<li title="previous page">&lt;</li>';
		
		if($page-3>0) $pageHTML .='<li title="page '.($page - 3).'"><a href="FindJob.php?Page='. ($page - 3) . $get .'">'. ($page - 3) .'</a></li>';
		if($page-2>0) $pageHTML .='<li title="page '.($page - 2).'"><a href="FindJob.php?Page='. ($page - 2) . $get .'">'. ($page - 2) .'</a></li>';
		if($page-1>0) $pageHTML .='<li title="page '.($page - 1).'"><a href="FindJob.php?Page='. ($page - 1) . $get .'">'. ($page - 1) .'</a></li>';
		$pageHTML .='<li title="current page">'.$page.'</li>';
		if($NumberPages-$page>0) $pageHTML .='<li title="page '.($page + 1).'"><a href="FindJob.php?Page='. ($page + 1) . $get .'">'. ($page + 1) .'</a></li>';
		if($NumberPages-$page>1) $pageHTML .='<li title="page '.($page + 2).'"><a href="FindJob.php?Page='. ($page + 2) . $get .'">'. ($page + 2) .'</a></li>';
		if($NumberPages-$page>2) $pageHTML .='<li title="page '.($page + 3).'"><a href="FindJob.php?Page='. ($page + 3) . $get .'">'. ($page + 3) .'</a></li>';
		
		if($page<$NumberPages)
			$pageHTML .='<li title="next page"><a href="FindJob.php?Page='. ($page + 1) . $get .'">&gt;</a></li>
		<li title="last page"><a href="FindJob.php?Page=' . $NumberPages .$get .'">&gt;|</a></li>';
		else
			$pageHTML .='<li title="next page">&gt;</li>
		<li title="last page">&gt;|</li>';
	
	}
	
	
	$HTML = str_replace('<ul id="jobList"></ul>',$HtmlContent,$HTML);
	$HTML = str_replace('<paging/>', $pageHTML, $HTML);
	$HTML = str_replace('<TipologySelect/>',$HtmlTypologySelect,$HTML);
	$HTML = str_replace('<paytype/>',$HtmlCheckboxPay,$HTML);
	$HTML = str_replace('[payval]','value="'.$min.'"',$HTML);
	$HTML = str_replace('<DateSelect/>',$HtmlDateSelect,$HTML);

 
  // Apertura Pagina
  echo $HTML;

?>
