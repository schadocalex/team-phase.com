<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_OTHERS');

	$upcoming_match = MySQL::selectLast('upcoming_match');

	if(isset($_POST['upcoming_match']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name_phase = @$_POST['name_phase'];
		$name_opponent = @$_POST['name_opponent'];
		$date = $form->verify_date('date');
		$matchlink = @$_POST['matchlink'];

		if(empty($name_phase))
			$form->error('You must enter phase name.');

		if($form->error == '')
		{
			$image_phase_id = $form->verify_image('image_phase_id');
			if($image_phase_id <= 0)
				$image_phase_id = $upcoming_match['image_phase_id'];

			$image_opponent_id = $form->verify_image('image_opponent_id');
			if($image_opponent_id <= 0)
				$image_opponent_id = $upcoming_match['image_opponent_id'];

			$insert_upcoming = new Insert('upcoming_match');
			$insert_upcoming->name_phase = $name_phase;
			$insert_upcoming->image_phase_id = $image_phase_id;
			$insert_upcoming->name_opponent = $name_opponent;
			$insert_upcoming->image_opponent_id = $image_opponent_id;
			$insert_upcoming->date = $date;
			$insert_upcoming->matchlink = $matchlink;
			$insert_upcoming->execute();

			$_SESSION['success'] = 'Upcoming match has been edited.';
			$user->redirect('Admin-Others');
		}
	}

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_menu" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>UPCOMING MATCH</h2>
		<p class="right">
			<img width="78" height="78" src="<?= srcImg($upcoming_match['image_phase_id']) ?>" />
			vs
			<img width="78" height="78" src="<?= srcImg($upcoming_match['image_opponent_id']) ?>" />
		</p>
		<p>
			<?php
				$form->initializeImg('edit_upcoming_match', '', 'Admin-Others');
				$form->hidden('upcoming_match');
				$form->input('text', 'Name phase:', 'name_phase', $upcoming_match['name_phase']);
				$form->input('image', 'Image phase:', 'image_phase_id');
				$form->input('text', 'Name opponent:', 'name_opponent', $upcoming_match['name_opponent']);
				$form->input('image', 'Image opponent:', 'image_opponent_id');
				$form->input('date', 'Date:', 'date', $upcoming_match['date']);
				$form->input('text', 'Matchlink:', 'matchlink', $upcoming_match['matchlink']);
				$form->end('Edit upcoming');
			?>
		</p>
	</div>
</div>
<?php
	include($url."footer.php");
?>