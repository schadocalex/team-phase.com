<?php
	$form->initializeImg('add_match', '', 'Admin-Tournament-Add-Match');
	$form->input('date', 'Date:', 'date');
	$form->select_table('team', 'name', 'Team 1:', 'team_1');
	$form->select_table('team', 'name', 'Team 2:', 'team_2');
	$form->input('number', 'Score team 1:', 'score_team_1');
	$form->input('number', 'Score team 2:', 'score_team_2');
	$form->input('text', 'Group letter:', 'group_letter');
	$form->input('text', 'Matchlink:', 'matchlink');
	$form->end('Add game');
?>