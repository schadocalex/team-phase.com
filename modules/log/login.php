<?php
	$url = '../../';
	include($url.'include.php');

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		
		$requete_login = $bdd->prepare('SELECT * FROM user WHERE username = ?');
		$requete_login->bindValue(1, $_POST['username'], PDO::PARAM_STR);
		$requete_login->execute();
		
		// fail
		if($requete_login->rowCount() == 0)
		{
			$_SESSION['error'] = 'Pseudo not found.';
		}
		else
		{
			while($requete_login2 = $requete_login->fetch())
			{
				$id = $requete_login2['id'];
				$salt = $requete_login2['salt'];
				$password_crypted = $requete_login2['password'];
				$email = $requete_login2['email'];
				$enabled = $requete_login2['enabled'];
				// success
				if(encodePasswordSf2($_POST['password'], $salt) == $password_crypted AND $enabled)
				{
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['password'] = $password_crypted;
					
					// if remember cheched
					if(isset($_POST['remember']) AND $_POST['remember'] == 'on')
					{
						setcookie('username', $_SESSION['username']);
						setcookie('password', $_SESSION['password']);
					}
					$user = new User($id);
					$user->last_login = date("Y-m-d H:i:s");  
					$user->update();
					
					$user->redirect('Home');
				}
				elseif(encodePasswordSf2($_POST['password'], $salt) != $password_crypted)
				{
					$_SESSION['error'] = 'Bad password.';
				}
				else
				{
					$_SESSION['error'] = 'Your account isn\'t enabled. Please check your boxmail and follow the instructions.
					<a href="Registration?username=' . $_POST['username'] . '">Click here to send again a mail.</a>';
				}
			}	
		}
	}
	include($url."header.php");
?>
	<div class="bg4" >
		<?php 
			showMessages();
			include('login_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>