<?php
	include('include.php');
	$table = 'user';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('username', 'email', 'salt', 'password', 'confirmation_token', 'name', 'city');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "user<br/>";
	$table = 'opponent';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('name');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "opponent<br/>";
	$table = 'news';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('title', 'author', 'content');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "news<br/>";
	$table = 'match2';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('matchlink');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "match2<br/>";
	$table = 'competition';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('name');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "competition<br/>";
	$table = 'game';
	$users = $bdd->query("SELECT * FROM $table")->fetchAll();
	$names = array('name');
	foreach($users as $u)
		foreach ($names as $name) {
			$user2 = $bdd->prepare("UPDATE $table SET $name = ? WHERE id = ".$u['id']);
			$user2->bindValue(1, utf8_encode($u[$name]), PDO::PARAM_STR);
			$user2->execute();
		}
echo "game<br/>";

