<?php
	$url = '../../';
	include($url.'include.php');

	if($_GET['jeton_logout'] == $_SESSION['jeton_logout'])
	{
		//session_destroy();
		session_unset();
		setcookie('username', ''); 
		setcookie('password', '');
	}
	$user = new User();
	$user->redirect('Home');
	
?>