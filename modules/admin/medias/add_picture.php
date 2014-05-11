<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_MEDIAS');

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$date = $form->verify_date('date');
		$image_id = $form->verify_image('image', 180);

		if($form->error == '')
		{
			$insert_picture = new Insert('picture');
			$insert_picture->date = $date;
			if($image_id > 0)
				$insert_picture->image_id = $image_id;
			$insert_picture->execute();

			$_SESSION['success'] = 'Picture has been added.';
			$user->redirect('Admin-Medias');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_picture_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>