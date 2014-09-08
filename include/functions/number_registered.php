<?php
	function number_registered()
	{
		$bdd = MySQL::getInstance();
		
		$request_registered = $bdd->prepare('SELECT COUNT(*) AS total_registered FROM user');
		$request_registered->execute();
		$request_registered = $request_registered->fetch();
		$number_registered = $request_registered['total_registered'];
		
		echo $number_registered;
	}
?>