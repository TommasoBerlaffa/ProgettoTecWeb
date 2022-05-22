<?php

  require_once "DBAccess.php";

  function generateRandomString($length = 25) {
		$char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++)
			$randomString .= $char[rand(0, strlen($char) - 1)];
		return $randomString;
	}

  function compressImage($source, $destination, $quality) {
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);
			
		$old_x=imageSX($image);
		$old_y=imageSY($image);
		$res=0;
		if($old_x>$old_y)
			$res=450/$old_x;
		else
			$res=450/$old_y;
	
		$thumb=ImageCreateTrueColor($old_x*$res, $old_y*$res);
		imagecopyresized($thumb,$image, 0,0,0,0, $old_x*$res,$old_y*$res, $old_x,$old_y);

		imagejpeg($thumb, $destination, $quality);
		imagedestroy($thumb);
		return $destination;
	}
	
  session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Apro Connessione a DB
    $DbAccess = new DBAccess();
    $conn = $DbAccess->openDBConnection();
    // Ottengo i valori dello User
    $Result = $DbAccess->getUser($_SESSION['user_ID']);
    // Da riempire e riportare in caso di errori
    $errorReport='<h3>List of Errors</h3>';
    // Controllo se i valori in POST sono set, se sì controllo se sono diversi e modifico il valore
    // Controllo su nickname se è già in uso
    if(isset($_POST["Nickname"]) ) {
      $postNick = $_POST["Nickname"];
      // Da segnalare errore
      if($DBAccess->usernameTaken($postNick))
      {
        $errorReport='<p>This Username is already taken.</p>';
        $postNick=$Result["Nickname"];
      }
      else
        $postNick!=$Result["Nickname"]  ? $postNick = $postName : $postNick=$Result["Nickname"];
    }
    else
      $Nickname=$Result["Nickname"];
    // Nome & Cognome
    (isset($_POST["Name"]) && $_POST["Name"]!=$Result["Name"]) ? $Name = filter_var($_POST["Name"], FILTER_SANITIZE_STRING) : $Name=$Result["Name"];
    (isset($_POST["Surname"]) && $_POST["Surname"]!=$Result["Surname"]) ? $Surname = filter_var($_POST["Surname"], FILTER_SANITIZE_STRING) : $Surname=$Result["Surname"];
    // Data di Nascita
    if(isset($_POST["Birth"]) && $_POST["Birth"]!=$Result["Birth"]) {
      // Copiato da codice Sign Up
      $birthDate=explode('-',$_POST['Birth']);
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")? ((date("Y") - $birthDate[0]) - 1): (date("Y") - $birthDate[0]));
			// Controllo se Nuova Data corrisponda ad un utente Minorenne
      if($age<18)
      {
        $errorReport .= '<p>Your age is less than what required by laws (18).</p>';
        $Birth = $Result["Birth"];
      }
			else
				$Birth = $_POST['Birth'];
    }
    else  
      $Birth = $Result["Birth"];
    // Nazionalità, Città e Indirizzo ( Non dovrebbero servire controlli particolari )
    (isset($_POST["Nationality"]) && $_POST["Nationality"]!=$Result["Nationality"]) ? $Nationality = filter_var($_POST["Nationality"], FILTER_SANITIZE_STRING) : $Nationality=$Result["Nationality"];
    (isset($_POST["City"]) && $_POST["City"]!=$Result["City"]) ? $City = filter_var($_POST["City"], FILTER_SANITIZE_STRING) : $City=$Result["City"];
    (isset($_POST["Address"]) && $_POST["Address"]!=$Result["Address"]) ? $Address = filter_var($_POST["Address"], FILTER_SANITIZE_STRING) : $Address=$Result["Address"];
    // Boh che controlli metto per la mail?
    (isset($_POST["Email"]) && $_POST["Email"]!=$Result["Email"]) ? $Email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL) : $Email=$Result["Email"];
    // Curriculum e Description No Problem
    (isset($_POST["Curriculum"]) && $_POST["Curriculum"]!=$Result["Curriculum"]) ? $Curriculum = filter_var($_POST["Curriculum"], FILTER_SANITIZE_STRING) : $Curriculum=$Result["Curriculum"];
    (isset($_POST["Description"]) && $_POST["Description"]!=$Result["Description"]) ? $Description = filter_var($_POST["Description"], FILTER_SANITIZE_STRING) : $Description=$Result["Description"];
    // Numero di Telefono 
    (isset($_POST["Phone"]) && $_POST["Phone"]!=$Result["Phone"]) ? $Phone = filter_var($_POST["Phone"], FILTER_VALIDATE_INT) : $Phone=$Result["Phone"];
    
    if(!isset($_FILES['pfp'])) {
      $Picture = $Result["Picture"];
    }
    else{
      $originalName = $_FILES['pfp']['name'];
      if(strlen($originalName) == 0) {
        $Picture = $Result["Picture"];
      }
			else {
        if ($_FILES["pfp"]["size"] > 16777216) {
          $errorReport .= '<p>Picture size exceed limit 16Mb.</p>';
          $Picture = $Result["Picture"];
        }
        else {
          //prepare to store the image
          $target_file = '../IMG/UsrPrfl/' . $originalName;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
          //check file extension
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $errorReport.= "<p>Only JPG, JPEG, PNG & GIF files are allowed for Picture.<p>";
            $Picture = $Result["Picture"];
          }
          else {
            //check it is a real image and not a fake file
            $check = getimagesize($_FILES['pfp']['tmp_name']);
            if($check==false) {
              $errorReport.= "<p>File not recognized as a picture, please provide another file.<p>";
              $Picture = $Result["Picture"];
            }
            else {
              //assign a big random Firstname
              $randomName=generateRandomString();
              while(file_exists('../IMG/UsrPrfl/' .$randomName. '.jpg'))
                $randomName=generateRandomString();
              compressImage($_FILES['pfp']['tmp_name'], '../IMG/UsrPrfl/'.$randomName.'.jpg', 90);
              if(file_exists('../IMG/UsrPrfl/' .$randomName. '.jpg')){
                $Picture = $randomName. '.jpg';
              }
              else {
                $Picture = $Result["Picture"];
                $errorReport.= "<p>Failed to process uploaded image.<p>";
              }
            }
          }
        }
      }
    }


    if($errorReport != '<h3>List of Errors</h3> ')
      $_SESSION['error'] = $errorReport;

    $DbAccess->changeUserInfo($_SESSION['user_ID'],$Name,$Surname,$Nickname,$Birth,$Email,$Nationality,$City,$Address,$Phone,$Picture,$Curriculum,$Description);
    header("Location: ..". DIRECTORY_SEPARATOR ."UserProfile". DIRECTORY_SEPARATOR ."Setting.php");  
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR. "Login.php");
  }

?>


    