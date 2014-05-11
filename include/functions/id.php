<?php

	function id($taille)
	{
		$id = '';
		$letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for($i = 0; $i < $taille; $i++)
			$id .= substr($letters, (rand()%(strlen($letters))), 1);
		return $id;
	}

?>