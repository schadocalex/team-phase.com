<?php
	ob_start();

	if(!isset($_SESSION))
		session_start();
	
	include_once("include/class/MySQL.class.php");
	include_once("include/class/Table.class.php");
	include_once("include/class/User.class.php");
	include_once("include/class/Form.class.php");
	
	$bdd = MySQL::getInstance();
	$form = new Form();
	$user = new User();
	
	// on inclut toutes les fonctions
	if($_SERVER['HTTP_HOST'] == 'localhost')
		$_SERVER['DOCUMENT_ROOT'] .=  '/teamphase/';
	else if ($_SERVER['HTTP_HOST'] == '127.0.0.1')
		$_SERVER['DOCUMENT_ROOT'] .=  '/team-phase.com';
	/*else
		$_SERVER['DOCUMENT_ROOT'] .=  '/team-phase';*/

	$name_folder = $_SERVER['DOCUMENT_ROOT'] . '/include/functions';
		

	$folder = opendir($name_folder);
	while($file = readdir($folder))
	{
		if(is_file($name_folder . '/' . $file) AND strrchr($file, '.') == '.php')
			include_once($name_folder . '/' . $file);
	}
	closedir($folder);
	