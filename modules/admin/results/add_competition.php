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
		else if(MySQL::exist('competition', 'name', $name))
			$form->error('The competition "'.$name.'" already exist.');

		if($form->error == "")
		{
			$insert_competition = new Insert('competition');
			$insert_competition->name = $name;
			$insert_competition->execute();

			$_SESSION['success'] = 'Competition "'.$name.'" has been added.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_competition_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>