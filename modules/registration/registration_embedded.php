<?php
	$form->initialize('registration', '', 'Registration');
	$form->input('email', 'Email:', 'email');
	$form->input('text', 'Username:', 'username');
	$form->input('password', 'Password:', 'password');
	$form->input('password', 'Confirm password:', 'password_confirm');
	$form->end('Register');
?>