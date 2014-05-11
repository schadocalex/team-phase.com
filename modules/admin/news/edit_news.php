<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_NEWS');

	$id = @$_GET['id'];
	if(!MySQL::exist('news', 'id', $id))
	{
		$_SESSION['error'] = "News with id $id doesn't exist.";
		$user->redirect('Admin-News');
	}

	$news = Select::withId('news', $id);

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

		if($form->error == "")
		{
			$image_id = $form->verify_image('image');
			if($image_id == 0)
				$image_id = $news['image_id'];

			$update = $bdd->query("UPDATE news SET
				title = \"$title\",
				image_id = \"$image_id\",
				date = \"$date\",
				author = \"$author\",
				content = \"$content\"
				WHERE id = \"$id\"");

			$_SESSION['success'] = 'News "'.$name.'" has been edited.';
			$user->redirect('Admin-News');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_news', '', 'Admin-News-Edit-News-'.$news['id']);
			$form->input('text', 'Title:', 'title', $news['title']);
			$form->textarea('content', 'Content:', $news['content'], 60, 7);
			$form->input('text', 'Author:', 'author', $news['author']);
			$form->input('date', 'Date:', 'date', $news['date']);
			$form->input('image', 'Image:', 'image');
			$form->end('Edit news');
		?>
	</div>
<?php
	include($url."footer.php");
?>