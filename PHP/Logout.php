<?php
	if (session_status() === PHP_SESSION_ACTIVE) {
		$_SESSION = array();
		session_destroy();
	}
  header('Location:Login.php');
?>