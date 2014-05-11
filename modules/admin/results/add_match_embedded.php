<?php
	$form->initializeImg('add_game', '', 'Admin-Results-Add-Match');
	$form->select_table('game', 'name', 'Game:', 'game');
	$form->input('date', 'Date:', 'date');
	$form->select_table('competition', 'name', 'Game:', 'competition');
	$form->select_table('opponent', 'name', 'Opponent:', 'opponent');
	$form->input('number', 'Score phase:', 'score_phase');
	$form->input('number', 'Score opponent:', 'score_opponent');
	$form->input('text', 'Matchlink', 'matchlink');
	$form->end('Add game');
?>