<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	$id = @$_GET['id'];
	if(!MySQL::exist('game', 'id', $id))
	{
		$_SESSION['error'] = "Game with id $id doesn't exist.";
		$user->redirect('Admin-Results');
	}

	$game = Select::withId('game', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];

		if(empty($name))
			$form->error('You must enter a name.');

		if($form->error == "")
		{
			$icon_id = $form->verify_image('icon');
			if($icon_id == 0)
				$icon_id = $game['icon_id'];

			$update = $bdd->query("UPDATE game SET name = \"$name\", icon_id = \"$icon_id\" WHERE id = \"$id\"");

			$_SESSION['success'] = 'Game "'.$name.'" has been edited.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_game', '', 'Admin-Results-Edit-Game-'.$game['id']);
			$form->input('image', 'Icon (16x16):', 'icon');
			$form->input('text', 'Name:', 'name', $game['name']);
			$form->end('Edit game');
		?>
	</div>
<?php
	include($url."footer.php");
?>