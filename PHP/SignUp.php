<?php

  require_once 'DBAccess.php';
	
	require_once "Modules". DIRECTORY_SEPARATOR ."Util.php";

  $source_img = 'source.jpg';
  $destination_img = 'destination .jpg';
	
  if(!isset($_SESSION)) 
  { 
    session_start(); 
  } 
  //Controllo se Login è già stato effettuato
	$page=null;
	if(isset($_GET['section'])){
		$page=filter_var($_GET['section'], FILTER_VALIDATE_INT);
	}
	$HTML = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'SignUp.html');
	
	$TagModule = file_get_contents('..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Elements'. DIRECTORY_SEPARATOR .'TagsSearch.html');
	$HTML = str_replace('<TagModule/>',$TagModule,$HTML);
	
	$messaggioErrore = '';
	
	$Username = '';
	$Firstname = '';
	$Lastname = '';
	$Password = '';
	$Birthday = '';
	$Email = '';
	$Country = '';
	$City = '';
	$Address = '';
	$Phone = '';
	$Picture = '';
	$Curriculum = '';
	$Description = '';
	$Skill1 = '';
	$Skill2 = '';
	$Skill3 = '';
	$Skill4 = '';
	$Skill5 = '';
	
	
	
    if(isset($_POST['Sign_Up'])) {
		$DBAccess= new DBAccess();
		if(!($DBAccess->openDBConnection())){
			header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
			exit;
		}
		
		if(!isset($_POST['Username']))
			$messaggioErrore .='<li>Username field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Username field must be filled.</li>';
			else{
				if($DBAccess->usernameTaken($tmp))
					$messaggioErrore .= '<li>This Username is already taken.</li>';
				$Username = $tmp;
			}
		}
		
		if(!isset($_POST['Email']))
			$messaggioErrore .='<li>Email field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
			if(strlen($tmp) == 0)
				$messaggioErrore .= '<li>Email field must be filled.</li>';
			$disposable = file_get_contents('..'. DIRECTORY_SEPARATOR .'Utils'. DIRECTORY_SEPARATOR .'disposable-email-domains.txt');
			$disposable = explode(PHP_EOL,$disposable);
			$i=0;
			$domain=explode('@',$tmp)[1];
			$cond=true;
			while($i!=count($disposable) AND $cond){
				if($disposable[$i]===$domain){
					$messaggioErrore .="<li>Your Domain is in our blacklist. Please choose another Email address.<l/i>";
					$cond=false;
				}
				$i++;
			}
			if($DBAccess->emailTaken($tmp))
				$messaggioErrore .= '<li>This Email is already used.</li>';
			$Email = $tmp;
		}
		
		if(!isset($_POST['Password']))
			$messaggioErrore .='<li>Password field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Password'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Password field must be filled.</li>';
			else
				$Password = $tmp;
		}
		
		if(!isset($_POST['Repeat-Password'])){
			$messaggioErrore .='<li>Please Repeat Password on the second field.</li>';
			$Password = '';
		}
		else{
			if($Password != ''){
				$tmp = filter_var($_POST['Repeat-Password'], FILTER_SANITIZE_STRING);
				if(strlen($tmp) == 0){
					$messaggioErrore .= '<li>Repeat Password field must be filled.</li>';
					$Password = '';
				}
				else if($tmp!=$Password){
					$messaggioErrore .= '<li>Password and Repeat Password do not match.</li>';
					$Password = '';
				}
			}
		}
		
		if(!isset($_POST['Firstname']))
			$messaggioErrore .='<li>Firstname field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Firstname'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Firstname field must be filled.</li>';
			//questa cosa è da valutare. Quali caratteri dovrei filtrare sul nome di una persona?
			//e nel caso che messaggio dovrei stampare?
			else if(preg_match('/[^A-Za-z\']/', $tmp))
				$messaggioErrore .= '<li>Firstname field contains invalid characters.</li>';
			else
				$Firstname = $tmp;
		}
		
		if(!isset($_POST['Lastname']))
			$messaggioErrore .='<li>Surname field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Lastname'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Surname field must be filled.</li>';
			//questa cosa è da valutare. Quali caratteri dovrei filtrare sul nome di una persona?
			//e nel caso che messaggio dovrei stampare?
			else if(preg_match('/[^A-Za-z\']/', $tmp))
				$messaggioErrore .= '<li>Surname field contains invalid characters.</li>';
			else
				$Lastname = $tmp;
		}
		
		if(!isset($_POST['Birthday']))
			$messaggioErrore .='<li>Birthday field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Birthday'], FILTER_SANITIZE_STRING);
            if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Birthday field must be filled.</li>';
			else if(preg_match('/[^0-9-]/', $tmp)){
				$dt = DateTime::createFromFormat("Y-m-d", $tmp);
				if($dt !== false && !array_sum($dt::getLastErrors()))
					$messaggioErrore .= '<li>Birthday field has an invalid date.</li>';
				$messaggioErrore .= '<li>Birthday field should be in fomat YYYY-MM-DD.</li>';
			}
			$birthDate=explode('-',$tmp);
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")? ((date("Y") - $birthDate[0]) - 1): (date("Y") - $birthDate[0]));
			if($age<18)
				$messaggioErrore .= '<li>Your age is less than what required by laws (18).</li>';
			else
				$Birthday = $tmp;
		}
		
		if(!isset($_POST['Country']))
			$messaggioErrore .='<li>Email field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Country'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Country field must be filled.</li>';
			else if(!ctype_alpha($tmp))
				$messaggioErrore .= '<li>Country contains non alphabetcis characters.</li>';
			else
				$Country = $tmp;
		}
		
		if(!isset($_POST['City']))
			$messaggioErrore .='<li>City field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['City'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>City field must be filled.</li>';
			if(!ctype_alpha(str_replace(' ', '', $tmp)))
				$messaggioErrore .= '<li>City contains non alphabetcis characters.</li>';
			else
				$City = $tmp;
		}
		
		
		if(isset($_POST['Address']))
			$tmp = filter_var($_POST['Address'], FILTER_SANITIZE_STRING);
		
		
		if(isset($_POST['Tel']))
			$Phone = filter_var($_POST['Tel'], FILTER_VALIDATE_INT);
		
		
		if(isset($_POST['Curr'])){
			$tmp = filter_var($_POST['Curr'], FILTER_SANITIZE_URL);
			$Curriculum = $tmp;
		}
		
		if(!isset($_POST['Desc']))
			$messaggioErrore .='<li>Description field must be filled.</li>';
		else{
			$tmp = filter_var($_POST['Desc'], FILTER_SANITIZE_STRING);
			if(strlen($tmp) == 0)
                $messaggioErrore .= '<li>Description field must be filled.</li>';
			else
				$Description = $tmp;
		}
		
		if($_SESSION['TagList']==array())
			$messaggioErrore .='<li>Select at least 1 Skill.</li>';
		
		if(!isset($_FILES['Picture']))
			$messaggioErrore .='<li>A profile Picture must be uploaded or choosen between the default ones.</li>';
		else if($messaggioErrore===''){
			//filter Name
			$originalName = filter_var($_FILES['Picture']['name'], FILTER_SANITIZE_STRING);
			if(strlen($originalName) == 0)
                $messaggioErrore .= '<li>Invalid Picture Firstname.</li>';
			else{
				//check size of file
				if ($_FILES["Picture"]["size"] > 16777216)
					$messaggioErrore .= '<li>Picture size exceed limit 16Mb.</li>';
				else{
					//prepare to store the image
					$target_file = '../IMG/UsrPrfl/' . $originalName;
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					//check file extension
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
						$messaggioErrore.= "Only JPG, JPEG, PNG & GIF files are allowed for Picture.";
					else{
						//check it is a real image and not a fake file
						$check = getimagesize($_FILES['Picture']['tmp_name']);
						if($check==false)
							$messaggioErrore.= "File not recognized as a picture, please provide another file.";
						else{
							//assign a big random Firstname
							$randomName=generateRandomString();
							while(file_exists('../IMG/UsrPrfl/' .$randomName. '.jpg'))
								$randomName=generateRandomString();
							compressImage($_FILES['Picture']['tmp_name'], '../IMG/UsrPrfl/'.$randomName.'.jpg', 90);
							if(file_exists('../IMG/UsrPrfl/' .$randomName. '.jpg'))
								$Picture = $randomName. '.jpg';
							else
								$messaggioErrore.= "Failed to process uploaded image.";
						}
					}
				}
			}
		}
		
		
		
		if($messaggioErrore==''){
            $Success=$DBAccess->register_new_user($Password, $Firstname, $Lastname, $Username, $Birthday, $Email, $Country, $City, $Address, $Phone, $Picture, $Curriculum, $Description, $_SESSION['TagList']);
			if(!$Success)
				$messaggioErrore .= '<div id="errorMessages"><ul>Something went wrong while creating your new account.</ul></div>';
			//else
			//	header('Location:Login.php');
			
		}
		else
			$messaggioErrore = '<div id="errorMessages"><ul>' . $messaggioErrore . '</ul></div>';
		$DBAccess->closeDBConnection();
		
	}

	$HTML =  str_replace('<Username />', $Username, $HTML);
	$HTML =  str_replace('<Firstname />', $Firstname, $HTML);
	$HTML =  str_replace('<Lastname />', $Lastname, $HTML);
	$HTML =  str_replace('<Birthday />', $Birthday, $HTML);
	$HTML =  str_replace('<Email />', $Email, $HTML);
	$HTML =  str_replace('<Country />', $Country, $HTML);
	$HTML =  str_replace('<City />', $City, $HTML);
	$HTML =  str_replace('<Address />', $Address, $HTML);
	$HTML =  str_replace('<Phone />', $Phone, $HTML);
	$HTML =  str_replace('<Picture />', $Picture, $HTML);
	$HTML =  str_replace('<Curriculum />', $Curriculum, $HTML);
	$HTML =  str_replace('<Description />', $Description, $HTML);
		
		
    $HTML =  str_replace('<messaggiForm />', $messaggioErrore, $HTML);
    echo $HTML;
?>