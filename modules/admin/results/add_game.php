<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];

		if(empty($name))
			$form->error('You must enter a name.');
		else if(MySQL::exist('game', 'name', $name))
			$form->error('The game "'.$name.'" already exist.');

		if($form->error == "")
		{
			$icon_id = $form->verify_image('icon');
			$insert_game = new Insert('game');
			$insert_game->name = $name;
			if($icon_id > 0)
				$insert_game->icon_id = $icon_id;
			$insert_game->execute();

			$_SESSION['success'] = 'Game "'.$name.'" has been added.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_game_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>