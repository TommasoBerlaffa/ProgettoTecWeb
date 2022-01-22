<?php

	require_once 'DBAccess.php';

    session_start();
	
	$post = json_decode(file_get_contents('php://input'),true);

	if(isset($post['Username'])) {
        $user = filter_var($post['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $user = filter_var($user, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        if(strlen($user) == 0)
            echo("Empty field");
		$DBAccess= new DBAccess();
		if($DBAccess->UsernameTaken($user))
			echo("Username already taken.");
		else
			echo('');
	}
	else if(isset($post['Email'])) {
        $user = filter_var($post['Email'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $user = filter_var($user, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        if(strlen($user) == 0)
            echo("Empty field");
		$DBAccess= new DBAccess();
		if($DBAccess->EmailTaken($user))
			echo("Email already taken.");
		else
			echo('');
	}
?>