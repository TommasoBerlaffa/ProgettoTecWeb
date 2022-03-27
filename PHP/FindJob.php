<?php
    require_once 'DBAccess.php';

    session_start();

    $url = '..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'FindJob.html';
    $HTML = file_get_contents($url);
    $HTMLSubpage ='';
    if(isset($_SESSION['user_Username']))
    {
      $HTML = str_replace('{{CreateJob}}','<a href="..'.DIRECTORY_SEPARATOR.'HTML'.DIRECTORY_SEPARATOR.'CreateJob.html"> Create a Job Offer </a>',$HTML);
      $HTMLSubpage = '<a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'UserProfile.php"> User Profile </a>
            <img src="..'.DIRECTORY_SEPARATOR.'IMG'.DIRECTORY_SEPARATOR. $_SESSION['user_Icon'] .'" alt="Profile Picture" width="16" height="16">';
    }
    else
    {
      $HTML = str_replace('<li>{{CreateJob}}</li>','',$HTML);
      $HTMLSubpage = '<a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'Login.php"> Login </a>
                        <span> or </span>
                        <a href="..'.DIRECTORY_SEPARATOR.'PHP'.DIRECTORY_SEPARATOR.'Signup.php"> Sign up </a>';
    }

    $HTML = str_replace('{{SubPage}}',$HTMLSubpage,$HTML);
    $DBAccess = new DBAccess();
    if( isset($_POST['Min_pay']))
    {
      $P_Min_Value = filter_var($_POST['Min_pay'], FILTER_VALIDATE_INT);
    }
    else
      $P_Min_Value = null;

    echo $P_Min_Value ;

    $result = $DBAccess->getJobs($P_Min_Value);
    $HtmlContent ='';

    if($result)
    {
      $HtmlContent .='<div id="content"><table class="content">
                        <tr>
                            <th> Title </th>
                            <th> Status </th>
                            <th> Tipology </th>
                            <th> Payment </th>
                            <th> Min Payment </th>
                            <th> Max Payment </th>
                        </tr>';
      foreach($result as $row)
      {
            $HtmlContent .='<tr>';
            $HtmlContent .='<td><a href="..'. DIRECTORY_SEPARATOR .'PHP'. DIRECTORY_SEPARATOR .'ViewJobOld.php?Code_job='.$row["Code_Job"].'">'.$row["Title"].'</a></td>';
            $HtmlContent .= '<td>'.trim($row["Status"]).'</td>';
            $HtmlContent .= '<td>'.trim($row["Tipology"]).'</td>';
            $HtmlContent .= '<td>'.trim($row["Payment"]).'</td>';
            $HtmlContent .= '<td>'.trim($row["P_min"]).'</td>';
            $HtmlContent .= '<td>'.trim($row["P_max"]).'</td>';
            $HtmlContent .='</tr>';
        }
        $HtmlContent .='</div>';
    }
    else
    {
      echo  'No Jobs Currently Available';
    }

    $HTML = str_replace('<div id="content"></div>',$HtmlContent,$HTML);
    echo $HTML;

?>
