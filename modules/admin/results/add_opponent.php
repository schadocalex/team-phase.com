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
		else if(MySQL::exist('opponent', 'name', $name))
			$form->error('The opponent "'.$name.'" already exist.');

		if($form->error == "")
		{
			$flag_id = $form->verify_flag('flag');
			$insert_opponent = new Insert('opponent');
			$insert_opponent->name = $name;
			$insert_opponent->flag_id = $flag_id;
			$insert_opponent->execute();

			$_SESSION['success'] = 'Opponent "'.$name.'" has been added.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_opponent_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>