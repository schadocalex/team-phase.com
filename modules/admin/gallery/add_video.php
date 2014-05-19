<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_MEDIAS');

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

		if($form->error == '')
		{
			$insert_video = new Insert('video');
			$insert_video->title = $title;
			$insert_video->id_youtube = $id_yt;
			$insert_video->url_youtube = $url_yt;
			$insert_video->date = $date;
			$insert_video->execute();

			$_SESSION['success'] = 'Video has been added.';
			$user->redirect('Admin-Gallery');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_video_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>