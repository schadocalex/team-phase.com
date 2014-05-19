<?php
	$form->initializeImg('add_video', '', 'Admin-Gallery-Add-Video');
	$form->input('text', 'Title:', 'title');
	$form->input('text', 'URL Youtube:', 'url_youtube');
	$form->input('date', 'Date:', 'date');
	$form->end('Add video');
?>