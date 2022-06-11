<?php	
	// Inizio Sessione 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
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
	
	// Call this at each point of interest, passing a descriptive string
	function prof_flag($str)
	{
		global $prof_timing, $prof_names;
		$prof_timing[] = microtime(true);
		$prof_names[] = $str;
	}
	
	// Call this when you're done and want to see the results
	function prof_print()
	{
		global $prof_timing, $prof_names;
		$size = count($prof_timing);
		for($i=0;$i<$size - 1; $i++)
		{
			echo "<b>{$prof_names[$i]}</b><br>";
			echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
		}
		echo "<b>{$prof_names[$size-1]}</b><br>";
	}
	
	
	
	
	if(!isset($_SESSION['TagList'])){
		$_SESSION['TagList']=array();
	}
	$data=file_get_contents('php://input');
	if($data){
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
	}
	
?>