<?php
	$url= '../../';
	include($url . 'include.php');
	$Page = @$_GET['page'];
	$PAGE = strtoupper($Page);

	$user->accessRight('ADMIN_'.$PAGE);

	$id = @$_GET['id'];
	$Table = @$_GET['table'];
	$table = strtolower($Table);
	if($table == 'match') $table = 'match2';

	if(MySQL::exist($table, 'id', $id))
	{
		$bdd->query("DELETE FROM $table WHERE id = $id");
		if($table == 'match2') $table = 'match';
		$_SESSION['success'] = "The $table has been deleted.";
	}
	else
		$_SESSION['error'] = "$Table with id $id doesn't exist.";

	$user->redirect('Admin-'.$Page);
?>