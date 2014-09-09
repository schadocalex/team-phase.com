<?php
	$url= '../../';
	include($url . 'include.php');
	
	if(!empty($_GET['confirmation_token']))
	{
		$requete_confirmation_token = $bdd->prepare('SELECT * FROM user WHERE confirmation_token = ?');
		$requete_confirmation_token->bindValue(1, $_GET['confirmation_token'], PDO::PARAM_STR);
		$requete_confirmation_token->execute();
	
		if($requete_confirmation_token->rowCount() == 0)
		{
			$_SESSION['error'] = 'The account you are trying to validated does not exist.';
		}
		else
		{
			while($requete_confirmation_token2 = $requete_confirmation_token->fetch())
			{
				$id = $requete_confirmation_token2['id'];
			}
			$user = new User($id);
			$user->confirmation_token = NULL;
			$user->enabled = 1;
			$user->update();
			
			$_SESSION['success'] = 'Congratulations, your account has been created.';//validated. You can now login.';
		}
	}
	else
	{
		$_SESSION['success'] = 'Congratulations, a confirmation e-mail has been sent.';
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