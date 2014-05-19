<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_MEDIAS');

	$id = @$_GET['id'];
	if(!MySQL::exist('video', 'id', $id))
	{
		$_SESSION['error'] = "Video with id $id doesn't exist.";
		$user->redirect('Admin-Video');
	}

	$video = Select::withId('video', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$date = $form->verify_date('date');
		$url_yt = @$_POST['url_youtube'];
		$id_yt = getIdYoutube($url_yt);
		$title = @$_POST['title'];

		if(empty($url_yt))
			$form->error('URL Youtube absente.');
		else if(empty($id_yt))
			$form->error('URL Youtube invalide.');

		if($form->error == "")
		{
			$update = $bdd->query("UPDATE video SET
				title = \"$title\",
				id_youtube = \"$id_yt\",
				url_youtube = \"$url_yt\",
				date = \"$date\"
				WHERE id = \"$id\"");

			$_SESSION['success'] = 'Video has been edited.';
			$user->redirect('Admin-Gallery');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_video', '', 'Admin-Gallery-Edit-Video-'.$video['id']);
			$form->input('text', 'Title:', 'title', $video['title']);
			$form->input('text', 'URL Youtube (id = '.$video['id_youtube'].'):', 'url_youtube', $video['url_youtube']);
			$form->input('date', 'Date:', 'date', $video['date']);
			$form->end('Edit video');
		?>
	</div>
<?php
	include($url."footer.php");
?>