<?php

	$form->initialize('edit_profile', '', 'Edit');
	$form->input('text', 'Name', 'name', $user->name);
	$form->input('flag', 'Country', 'country', $user->country_id);
	$form->input('text', 'City', 'city', $user->city);
	$form->input('text', 'Age', 'age', $user->age);
	$form->end('Update');

?>