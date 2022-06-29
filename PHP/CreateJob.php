<?php
    require_once "DBAccess.php";

    // Attivo Session
    if(!isset($_SESSION)) 
      session_start();

    // Controllo se il Login è stato effettuato
    if(!isset($_SESSION['user_Username']))
    {
      $_SESSION['Url'] = 'CreateJob';
      header("location: ../PHP/Login.php");
    }

    // Dichiaro tutte le var necessarie
    $Id=$_SESSION['user_ID'];
    $Title = '';
    $Desc = '';
    $Type= '';
    $Min = '';
    $Max = '';
    $Pay = 0;
    $required = false;
    $errorMsg = '<ul id="error">';
    // Creo Var DBAccess
    $DBAccess = new DBAccess();
    if(!($DBAccess->openDBConnection())){
      header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
      exit;
    }

    // Controllo se i campi obbligatori sono stati inseriti
    isset($_POST["Title"]) && filter_var($_POST["Title"], FILTER_SANITIZE_STRING) != '' ? $Title = filter_var($_POST["Title"], FILTER_SANITIZE_STRING) : $errorMsg.='<li>Your job offer need a <a href="#Title">title<a/>.</li>';
    isset($_POST["Description"]) && filter_var($_POST["Description"], FILTER_SANITIZE_STRING) != ''? $Desc = filter_var($_POST["Description"], FILTER_SANITIZE_STRING) : $errorMsg.='<li>Your job offer need a <a href="#Desc">description<a/>.</li>';
    isset($_POST["Type"]) ? $Type= filter_var($_POST["Type"], FILTER_SANITIZE_STRING) : $errorMsg.='<li>Your job offer need a <a href="#Tipology">type<a/>.</li>';
    isset($_POST["Min"]) ? $Min = filter_var($_POST["Min"], FILTER_SANITIZE_NUMBER_INT) : $errorMsg.='<li>Your job offer need a <a href="#MinPay">minimum payment<a/>.</li>';
    isset($_POST["Max"]) ? $Max = filter_var($_POST["Max"], FILTER_SANITIZE_NUMBER_INT) : $errorMsg.='<li>Your job offer need a <a href="#MaxPay">maximum payment<a/>.</li>';
    isset($_POST["PayV"]) ? ( $_POST["PayV"] == 'Pay1' ? $Pay = 0 : $Pay = 1) : $errorMsg.='<li>Your job offer need a <a href="#labelPay">payment type<a/>.</li>';
    isset($_POST["Expiring"]) ? $Expiring= date("Y-m-d h:i:sa", strtotime("+".filter_var($_POST["Expiring"], FILTER_SANITIZE_STRING))) : $errorMsg.='<li>Your job offer need a <a href="#Expiring">expiring date<a/>.</li>';
    if($Min > $Max) {
      $errorMsg .= '<li> The <a href=#MaxPay>maximum price</a> should be higher than the <a href=#MinPay>minimum one</a>. Please, fix this in order to create a job offer.</li>';
    }
    $errorMsg != '<ul id="error">' ? $required =false : $required=true;

    // Altrimenti setti i valori e rimando a createjob.php
    if($required == false)
    {
      $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'CreateJob.html';
      $HTML = file_get_contents($url);
  
      $HTML = str_replace('<subpage/>','<li><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'UserProfile.php">
      <img src="..'. DIRECTORY_SEPARATOR .'IMG'. DIRECTORY_SEPARATOR.'usrprfl'. DIRECTORY_SEPARATOR . $_SESSION['user_Icon'] .'" alt="Profile Picture" id="profilepic" class="icons">User Profile</a></li>',$HTML);
      $select = '
        <option '. ($Type == 'Fulltime' ? 'selected' : ''). '>Fulltime</option>
        <option '. ($Type == 'One Time' ? 'selected' : '').  '>One Time</option>
        <option '. ($Type == 'Urgent' ? 'selected' : '').  '>Urgent</option>
        <option '. ($Type == 'Recruiter' ? 'selected' : '').  '>Recruiter</option>
      ';
      $tmp = filter_var($_POST["Expiring"], FILTER_SANITIZE_STRING);
      $select2 = '
        <option '. ($tmp == '1 day' ? 'selected' : ''). '>1 day</option>
        <option '. ($tmp == '3 days' ? 'selected' : ''). '>3 days</option>
        <option '. ($tmp == '1 week' ? 'selected' : ''). '>1 week</option>
        <option '. ($tmp == '1 month' ? 'selected' : ''). '>1 month</option>
      ';


      $radio = '
          <label id="labelPay">Choose your preferred method of payment *:</label>
          <input type="radio" id="Pay1" name="PayV" value="Pay1"'. ($Pay == 0 ? 'checked' : '') .'>
          <label for="Pay1" id="labelPay1"> I want to pay all at once</label><br>
          <input type="radio" id="Pay2" name="PayV" value="Pay2"'. ($Pay != 0 ? 'checked' : '').'>
          <label for="Pay2" id="labelPay2"> I want to pay by worked hours</label>
      ';
      $HTML = preg_replace('/(?<=<div id="Pay">)((\n|.)*?)(?=<\/div>)/',$radio, $HTML, 1);
      
      $errorMsg .= "</ul>";

      $HTML = preg_replace('/(?<=<select id="Expiring" name="Expiring" required>)((\n|.)*?)(?=<\/select>)/',$select, $HTML);
      $HTML = preg_replace('/(?<=<select id="Tipology" name="Type" required>)((\n|.)*?)(?=<\/select>)/',$select2, $HTML);
      $HTML = str_replace('<title/>',$Title,$HTML);
      $HTML = str_replace('<desc/>',$Desc,$HTML);
      $HTML = str_replace('<min/>',$Min,$HTML);
      $HTML = str_replace('<max/>',$Max,$HTML);
      $HTML = str_replace('<ul id="error"></ul>',$errorMsg,$HTML);
      echo $HTML;
    }
    else
    {

      $Result=$DBAccess->createJob($Id,$Title,$Desc,$Type,$Pay,$Min,$Max,$Expiring);
      $DBAccess->closeDBConnection();
      if($Result) 
        header("Location:UserProfile.php?section=2");
      else {
        header("Location:CreateJob.php?error=1");
      } 
    }
    
    

?>
