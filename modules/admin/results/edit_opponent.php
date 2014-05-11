<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	$id = @$_GET['id'];
	if(!MySQL::exist('opponent', 'id', $id))
	{
		$_SESSION['error'] = "Opponent with id $id doesn't exist.";
		$user->redirect('Admin-Results');
	}

	$opponent = Select::withId('opponent', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];

		if(empty($name))
			$form->error('You must enter a name.');

		if($form->error == "")
		{
			$flag_id = $form->verify_flag('flag');
			$update = $bdd->query("UPDATE opponent SET name = \"$name\", flag_id = \"$flag_id\" WHERE id = \"$id\"");

			$_SESSION['success'] = 'Opponent "'.$name.'" has been edited.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_opponent', '', 'Admin-Results-Edit-Opponent-'.$opponent['id']);
			$form->input('flag', 'Flag:', 'flag', $opponent['flag_id']);
			$form->input('text', 'Name:', 'name', $opponent['name']);
			$form->end('Edit opponent');
		?>
	</div>
<?php
	include($url."footer.php");
?>