<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	$id = @$_GET['id'];
	if(!MySQL::exist('competition', 'id', $id))
	{
		$_SESSION['error'] = "Competition with id $id doesn't exist.";
		$user->redirect('Admin-Results');
	}

	$competition = Select::withId('competition', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];

		if(empty($name))
			$form->error('You must enter a name.');

		if($form->error == "")
		{
			$update = $bdd->query("UPDATE competition SET name = \"$name\" WHERE id = \"$id\"");

			$_SESSION['success'] = 'Competition "'.$name.'" has been edited.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_competition', '', 'Admin-Results-Edit-Competition-'.$competition['id']);
			$form->input('text', 'Name:', 'name', $competition['name']);
			$form->end('Edit competition');
		?>
	</div>
<?php
	include($url."footer.php");
?>