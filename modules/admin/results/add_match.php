<?php
	// TODO
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

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
			$insert_match = new Insert('match2');
			$insert_match->game_id = $game_id;
			$insert_match->date = $date;
			$insert_match->competition_id = $competition_id;
			$insert_match->opponent_id = $opponent_id;
			$insert_match->score_phase = $score_phase;
			$insert_match->score_opponent = $score_opponent;
			$insert_match->matchlink = $matchlink;
			$insert_match->execute();

			$_SESSION['success'] = 'Match has been added.';
			$user->redirect('Admin-Results');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			include('add_match_embedded.php');
		?>
	</div>
<?php
	include($url."footer.php");
?>