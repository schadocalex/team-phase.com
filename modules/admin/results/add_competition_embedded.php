<?php
	$form->initializeImg('add_competition', '', 'Admin-Results-Add-Competition');
	$form->input('text', 'Name:', 'name');
	$form->select('type', 'Type');
		$form->option(0, "External cup");
		$form->option(1, "TEAM-PHASE ESPORT CUP");
	$form->end_select();
	$form->end('Add competition');
?>