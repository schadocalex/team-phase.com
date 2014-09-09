<?php
	$url= '../../';
	include($url . 'include.php');
	
	if(isset($_POST['jeton']))
	{
	
		$form->verify_jeton($_POST['jeton']);

		$ip = getIp();
		$nb_ips = $bdd->query('SELECT COUNT(*) FROM user WHERE ip = "'.$ip.'"');
		$nb_ips = $nb_ips->fetch();
		$nb_ips = $nb_ips[0];

		if($nb_ips >= 5)
			$_SESSION['error'] = 'Your IP address is already used by five others accounts. You can\'t create one more.';
		else if(MySQL::exist('user', 'username', $_POST['username']))
			$_SESSION['error'] = 'Your username is already used by another account.';
		else if($form->verify_email($_POST['email']) == 1)
			$_SESSION['error'] = 'Your address e-mail is incorrect.';

		else if(MySQL::exist('user', 'email', $_POST['email']))
			$_SESSION['error'] = 'Your address is already used by another account.';
			
		else if($_POST['password'] != $_POST['password_confirm'])
			$_SESSION['error'] = 'The confirmation of your password is not correct.';
		else
		{
			$salt = id(36);
			$confirmation_token = id(36);
			$insert = new Insert('user');
			$insert->username = $_POST['username'];
			$insert->email = $_POST['email'];
			$insert->enabled = 0;
			$insert->banished = 0;
			$insert->rank = 2;
			$insert->salt = $salt;
			$insert->confirmation_token = $confirmation_token;
			$insert->password = encodePasswordSf2($_POST['password'], $salt) ;
			$insert->execute();
			
			// ATTENTION IL MANQUE username_canonical et email_canonical
			$send_mail = 1;
		}
	}
	// we sent a confirmation e-mail
	if(isset($_GET['username']) OR isset($send_mail))
	{
		if($send_mail)
		{
			$username = $_POST['username'];
			$email = $_POST['email'];
			
		}
		else
		{
			$requete_confirm = $bdd->prepare('SELECT * FROM user WHERE username = ? AND enabled = 0');
			$requete_confirm->bindValue(1, $_GET['username'], PDO::PARAM_STR);
			$requete_confirm->execute();
			while($requete_confirm2 = $requete_confirm->fetch())
			{
				$username = $requete_confirm2['username'];
				$email = $requete_confirm2['email'];
				$confirmation_token = $requete_confirm2['confirmation_token'];
			}
		}
		$message = '
		Hello ' . $username . '!

		To finish activating your account - please visit <a href="http://www.team-phase.com/Registration-Confirm-' . $confirmation_token . '">http://www.team-phase.com/team-phase/Registration-Confirm-' . $confirmation_token . '</a>
		
		Regards,
		the Team.';
		send_mail($email, '[Team-Phase.com] Confirm your account', $message);
		$user->redirect('Registration-Confirm-'.$confirmation_token);
	}
	include($url."header.php");
?>
	<div class="bg4" >
		<?php 
			showMessages();
			include('registration_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>