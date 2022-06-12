<?php

	require_once 'DBAccess.php';

    session_start();
	
	const cap = 5;
	
	$stamp_init = date("Y-m-d H:i:s");
    if( !isset( $_SESSION['FIRST_REQUEST_TIME'] ) ){
            $_SESSION['FIRST_REQUEST_TIME'] = $stamp_init;
    }
    $first_request_time = $_SESSION['FIRST_REQUEST_TIME'];
    $stamp_expire = date( "Y-m-d H:i:s", strtotime( $first_request_time )+( 5 ) );
    if( !isset( $_SESSION['REQ_COUNT'] ) ){
            $_SESSION['REQ_COUNT'] = 0;
    }
    $req_count = $_SESSION['REQ_COUNT'];
    $req_count++;
    if( $stamp_init > $stamp_expire ){//Expired
            $req_count = 1;
            $first_request_time = $stamp_init;
    }
    $_SESSION['REQ_COUNT'] = $req_count;
    $_SESSION['FIRST_REQUEST_TIME'] = $first_request_time;
    header('X-RateLimit-Limit: '.cap);
    header('X-RateLimit-Remaining: ' . ( cap-$req_count ) );
    if( $req_count > cap){//Too many requests
            http_response_code( 429 );
            exit();
    }
	
	$post = json_decode(file_get_contents('php://input'),true);

	if(isset($post['Username'])) {
        $user = filter_var($post['Username'], FILTER_SANITIZE_STRING);
        if(strlen($user) == 0)
            echo("Empty field");
		$DBAccess= new DBAccess();
		if(!($DBAccess->openDBConnection())){
			header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
			exit;
		}
		if($DBAccess->UsernameTaken($user))
			echo("Username already taken.");
		else
			echo('OK');
		$DBAccess->closeDBConnection();
	}
	else if(isset($post['Email'])) {
        $user = filter_var($post['Email'], FILTER_SANITIZE_STRING);
        if(strlen($user) == 0)
            echo("Empty field");
		$DBAccess= new DBAccess();
		if(!($DBAccess->openDBConnection())){
			header('Location:..'. DIRECTORY_SEPARATOR .'HTML'. DIRECTORY_SEPARATOR .'Error500.html');
			exit;
		}
		if($DBAccess->EmailTaken($user))
			echo("Email already taken.");
		else
			echo('OK');
		$DBAccess->closeDBConnection();
	}
?>