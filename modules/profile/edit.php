<?php
	$url= '../../';
	include($url . 'include.php');
	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$country_id = $form->verify_flag('country');
		$form->verify_number($_POST['age'], 8, 100);
		if($form->error == NULL)
		{
			$user->name = $_POST['name'];
			$user->country_id = $country_id;
			$user->city = $_POST['city'];
			$user->age = $_POST['age'];
			$user->update();
			
			$_SESSION['success'] = 'Your profile has been updated.';
			$user->redirect('Edit');
		}
		else
			$_SESSION['error'] = $form->error;
	}
	include($url."header.php");
?>
	<div class="bg4" >
		<?php 
			showMessages();
			
			include('edit_embedded.php');
	
		?>
	</div>
<?php
	include($url."footer.php");
?>