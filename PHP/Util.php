<?php	
	// Inizio Sessione 
	session_start();
	//$_SESSION['TagList']=array();
	if(!isset($_SESSION['TagList'])){
		$_SESSION['TagList']=array();
	}
	
	$post = json_decode(file_get_contents('php://input'),true);
	if(isset($post['Update'])){
		echo(json_encode($_SESSION['TagList']));
	}
	
	else if(isset($post['Add'])){
		if(isset($post['Name']) and isset($post['Value'])){
			$name=filter_var ( $post['Name'], FILTER_SANITIZE_STRING);
			$val=filter_var ( $post['Value'], FILTER_SANITIZE_NUMBER_INT);
			if(count($_SESSION['TagList'])==20 or isset($_SESSION['TagList'][$name]))
				return;
			$_SESSION['TagList'][$name]=$val;
		}
	}
	
	else if(isset($post['Sub'])){
		if(isset($post['Name'])){
			$name=filter_var ( $post['Name'], FILTER_SANITIZE_STRING);
			if(count($_SESSION['TagList'])==0)
				return;
			if(isset($_SESSION['TagList'][$name])){
				unset($_SESSION['TagList'][$name]);
			}
		}
	}
	
?>