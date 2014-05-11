<?php
	$form->initializeImg('add_news', '', 'Admin-News-Add-News');
	$form->input('text', 'Title:', 'title');
	$form->textarea('content', 'Content:', '', 60, 7);
	$form->input('text', 'Author:', 'author');
	$form->input('date', 'Date:', 'date');
	$form->input('image', 'Image:', 'image');
	$form->end('Add news');
?>