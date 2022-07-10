<?php

  require_once "DBAccess.php";

  require_once "Modules" . DIRECTORY_SEPARATOR . "Util.php";
	
  if(!isset($_SESSION)) 
    session_start();

  if(isset($_SESSION['user_Username']))
  {
    // Apro Connessione a DB
    $DbAccess = new DBAccess();
	if(!($DbAccess->openDBConnection())){
		header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
		exit;
	}
    // Ottengo i valori dello User
    $Result = $DbAccess->getUser($_SESSION['user_ID']);
    // Da riempire e riportare in caso di errori
    $errorReport='<h1>You made some mistakes :</h1>';
    // Controllo se i valori in POST sono set, se sì controllo se sono diversi e modifico il valore
    // Controllo su Username se è già in uso
    if(isset($_POST["Username"]) ) {
      $postNick = $_POST["Username"];
      // Da segnalare errore
      if($postNick!= $Result["Nickname"])
        if($DbAccess->usernameTaken($postNick))
        {
          $errorReport.='<p>This Username is already taken. Please try again with a <a href="#Username">different username<a></p>';
          $Username=$Result["Nickname"];
        }
        else 
          $Username = $postNick;
      else
        $Username=$Result["Nickname"];
    }
    else
      $Username=$Result["Username"];
    // Nome & Cognome
    (isset($_POST["Name"]) && $_POST["Name"]!=$Result["Name"]) ? $Name = filter_var($_POST["Name"], FILTER_SANITIZE_STRING) : $Name=$Result["Name"];
    (isset($_POST["Surname"]) && $_POST["Surname"]!=$Result["Surname"]) ? $Surname = filter_var($_POST["Surname"], FILTER_SANITIZE_STRING) : $Surname=$Result["Surname"];
    // Data di Nascita
    if(isset($_POST["Birth"]) && $_POST["Birth"]!=$Result["Birth"] && $_POST["Birth"]!='') {
      // Copiato da codice Sign Up
      $birthDate=explode('-',$_POST['Birth']);
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")? ((date("Y") - $birthDate[0]) - 1): (date("Y") - $birthDate[0]));
			// Controllo se Nuova Data corrisponda ad un utente Minorenne
      if($age<18)
      {
        $errorReport .= '<p>Your age is less than what required by laws (18). If your age is above 18, please try inserting your <a href="#Birth">birthday date</a> again</p>';
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
          $errorReport .= '<p>Picture size exceed limit 16Mb. Please try again with a <a href=\"#pfp\">smaller image<a></p>';
          $Picture = $Result["Picture"];
        }
        else {
          //prepare to store the image
          $target_file = '../IMG/UsrPrfl/' . $originalName;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
          //check file extension
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $errorReport.= "<p>Only JPG, JPEG, PNG & GIF files are allowed for Picture. Please try again with a <a href=\"#pfp\">image with the correct format<a></p>";
            $Picture = $Result["Picture"];
          }
          else {
            //check it is a real image and not a fake file
            $check = getimagesize($_FILES['pfp']['tmp_name']);
            if($check==false) {
              $errorReport.= "<p>File not recognized as a picture. Please try again with a <a href=\"#pfp\">different image<a></p>";
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
                $errorReport.= "<p>Failed to process uploaded image. Please try again with a <a href=\"#pfp\">different image<a><p>";
              }
            }
          }
        }
      }
    }


    if($errorReport != '<h1>You made some mistakes :</h1>')
      $_SESSION['error'] = $errorReport;
    else
      $_SESSION['error'] = '<p> The operation was successful. You can check your updated info in 
        <a href="User.php"> User Info</a></p>'
        ;

    $DbAccess->changeUserInfo($_SESSION['user_ID'],$Name,$Surname,$Username,$Birth,$Email,$Nationality,$City,$Address,$Phone,$Picture,$Curriculum,$Description);
    $DbAccess->closeDBConnection();
	header("Location:Setting.php");  
  }
  else
  {
    header("Location:..". DIRECTORY_SEPARATOR ."PHP". DIRECTORY_SEPARATOR. "Login.php");
  }

?>


    