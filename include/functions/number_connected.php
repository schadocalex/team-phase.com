<?php
	function number_connected()
	{
		$bdd = MySQL::getInstance();
		
		// on compte le nombre de membres actifs
		$request_connected = $bdd->prepare('SELECT COUNT(*) AS total_connected FROM user WHERE last_visit > DATE_SUB(NOW(), INTERVAL 5 MINUTE)');
		$request_connected->execute();
		$request_connected = $request_connected->fetch();
		$number_connected = $request_connected['total_connected'];
		
		echo $number_connected;
	}
?>