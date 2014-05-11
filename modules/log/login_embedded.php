<?php
	$form->initialize('login', '', 'Login');
	$form->input('text', 'Username:', 'username');
	$form->input('password', 'Password:', 'password');
	$form->checkbox('remember', 'Remember me', 'remember');
	$form->end('Login');
?>