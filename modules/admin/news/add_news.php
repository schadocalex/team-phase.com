<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_NEWS');

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$title = @$_POST['title'];
		$content = @$_POST['content'];
		$author = @$_POST['author'];
		$date = $form->verify_date('date');

		if(empty($title))
			$form->error('You must enter a title.');
		if(empty($content))
			$form->error('You must enter a content.');
		if(empty($author))
			$form->error('You must enter an author.');

		if($form->error == '')
		{
			$image_id = $form->verify_image('image');
			$insert_news = new Insert('news');
			$insert_news->title = $title;
			$insert_news->content = $content;
			$insert_news->author = $author;
			$insert_news->date = $date;
			if($image_id > 0)
				$insert_news->image_id = $image_id;
			$insert_news->execute();

			$_SESSION['success'] = 'News "'.$title.'" has been added.';
			$user->redirect('Admin-News');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_news_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>