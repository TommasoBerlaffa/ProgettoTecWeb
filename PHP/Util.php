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
		if(isset($post['Name'])){
			$name=filter_var ( $post['Name'], FILTER_SANITIZE_STRING);
			if(count($_SESSION['TagList'])==20 or in_array($name,$_SESSION['TagList']))
				return;
			$_SESSION['TagList'][$name]=$name;
		}
	}
	
	else if(isset($post['Sub'])){
		if(isset($post['Name'])){
			$name=filter_var ( $post['Name'], FILTER_SANITIZE_STRING);
			$length=count($_SESSION['TagList']);
			if($length==0)
				return;
			if($index=array_search($name,$_SESSION['TagList'])){
				unset($_SESSION['TagList'][$name]);
			}
		}
	}
	
?>