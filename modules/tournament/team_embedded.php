<?php
	$form->initialize('accept_team', '', 'Team-Accept-1');
	echo 'Team <em>phase</em> invited you to join it.';
	$form->end('Accept');
?>
You can also create your own team:<br />
<?php
	$form->initializeImg('create_team', '', 'Team');
	$form->input('text', 'Name:', 'name');
	$form->input('flag', 'Flag:', 'flag');
	$form->input('text', 'Channel IRC:', 'irc');
	$form->input('text', 'Website:', 'website');
	$form->end('Create team');
?>
If you want to join an existing team, ask a member from it to invite you.