<?php
	$url = '../../';
	include($url.'include.php');

	$id = @$_GET['id_login_with'];
	if(empty($id))
	{
		if(@$_GET['jeton_logout'] == $_SESSION['jeton_logout'])
		{
			//session_destroy();
			session_unset();
			setcookie('username', ''); 
			setcookie('password', '');

			$user->redirect('Home');
		}
	}

/********************************
/** LOGIN WITH FUNCTIONNALITY **/

	else
	{
		$user->accessRight('ADMIN_MEMBERS');
		$user2 = new User($id);
		if($user2->is('ADMIN') AND !$user->is('DEV'))
		{
			$_SESSION['error'] = "You can't login as an admin with \"login with\" functionnality.";
		}
		else
		{
			session_unset();
			setcookie('username', ''); 
			setcookie('password', '');

			$_SESSION['username'] = $user2->username;
			$_SESSION['password'] = $user2->password;
			$user2->redirect('Profile-'.$user2->id.'-'.getCanonical($user2->username));
		}
	}

	include($url."header.php");
?>
	<div class="bg4" >
		<?php 
			showMessages();
		?>
	</div>
<?php
	include($url."footer.php");
?>