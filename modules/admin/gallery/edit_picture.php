<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_MEDIAS');

	$id = @$_GET['id'];
	if(!MySQL::exist('picture', 'id', $id))
	{
		$_SESSION['error'] = "Picture with id $id doesn't exist.";
		$user->redirect('Admin-Picture');
	}

	$picture = Select::withId('picture', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$date = $form->verify_date('date');
		$image_id = $form->verify_image('image', 180);
		$title = @$_POST['title'];

		if($form->error == "")
		{
			if($image_id == 0)
				$image_id = $picture['image_id'];

			$update = $bdd->query("UPDATE picture SET
				image_id = \"$image_id\",
				title = \"$title\",
				date = \"$date\"
				WHERE id = \"$id\"");

			$_SESSION['success'] = 'Picture has been edited.';
			$user->redirect('Admin-Gallery');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_picture', '', 'Admin-Gallery-Edit-Picture-'.$picture['id']);
			$form->input('image', 'Image:', 'image');
			$form->input('date', 'Date:', 'date', $picture['date']);
			$form->input('text', 'Title:', 'title', $picture['title']);
			$form->end('Edit picture');
		?>
	</div>
<?php
	include($url."footer.php");
?>