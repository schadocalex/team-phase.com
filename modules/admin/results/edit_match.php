<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	$id = @$_GET['id'];
	if(!MySQL::exist('match2', 'id', $id))
	{
		$_SESSION['error'] = "Match with id $id doesn't exist.";
		$user->redirect('Admin-Results');
	}

	$match = Select::withId('match2', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$game_id = $form->verify_table('game', 'game');
		$date = $form->verify_date('date');
		$competition_id = $form->verify_table('competition', 'competition');
		$opponent_id = $form->verify_table('opponent', 'opponent');
		$score_phase = @$_POST['score_phase'];
		$score_opponent = @$_POST['score_opponent'];
		$matchlink = @$_POST['matchlink'];

		if(empty($matchlink))
			$matchlink = '';

		if(is_nan($score_phase))
			$form->error('You must enter a score to phase.');
		if(is_nan($score_opponent))
			$form->error('You must enter a score to the opponent.');

		if($form->error == "")
		{
			$update = $bdd->query("UPDATE match2 SET
				game_id = \"$game_id\",
				date = \"$date\",
				competition_id = \"$competition_id\",
				opponent_id = \"$opponent_id\",
				score_phase = \"$score_phase\",
				score_opponent = \"$score_opponent\",
				matchlink = \"$matchlink\"
				WHERE id = \"$id\"");

			$_SESSION['success'] = 'Match has been edited.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_match', '', 'Admin-Results-Edit-Match-'.$match['id']);
			$form->select_table('game', 'name', 'Game:', 'game', $match['game_id']);
			$form->input('date', 'Date:', 'date', $match['date']);
			$form->select_table('competition', 'name', 'Game:', 'competition', $match['competition_id']);
			$form->select_table('opponent', 'name', 'Opponent:', 'opponent', $match['opponent_id']);
			$form->input('number', 'Score phase:', 'score_phase', $match['score_phase']);
			$form->input('number', 'Score opponent:', 'score_opponent', $match['score_opponent']);
			$form->input('text', 'Matchlink', 'matchlink', $match['matchlink']);
			$form->end('Edit match');
		?>
	</div>
<?php
	include($url."footer.php");
?>