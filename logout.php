<?php

	include("vt.php");
	
	//$user = $db->customQuery("SELECT * FROM users WHERE username='".$_SESSION['username']."'")->getRow();
	$db->customQuery('Delete From turib_auth Where user_id='.$_GET['user_id']);
	$db->customQuery('Delete From kriterler Where user_id='.$_GET['user_id']);
	
	//session_destroy();
	unset($_SESSION['login-'.$user->rnd]);
	unset($_SESSION['username-'.$user->rnd]);
	header("Location: index.php");
	
?>