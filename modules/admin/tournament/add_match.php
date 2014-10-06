<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_RESULTS');

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$date = $form->verify_date('date');
		$team_1_id = $form->verify_table('team', 'team_1');
		$team_2_id = $form->verify_table('team', 'team_2');
		$score_team_1 = @$_POST['score_team_1'];
		$score_team_2 = @$_POST['score_team_2'];
		$group_letter = @$_POST['group_letter'];
		$matchlink = @$_POST['matchlink'];

		if(empty($matchlink))
			$matchlink = '';

		if(empty($group_letter))
			$form->error('You must enter a group letter for the match.');
		if(is_nan($score_team_1))
			$form->error('You must enter a score to team 1.');
		if(is_nan($score_team_2))
			$form->error('You must enter a score to team 2.');

		if($form->error == "")
		{
			$insert_match = new Insert('tournament_match');
			$insert_match->date = $date;
			$insert_match->team_1_id = $team_1_id;
			$insert_match->team_2_id = $team_2_id;
			$insert_match->score_team_1 = $score_team_1;
			$insert_match->score_team_2 = $score_team_2;
			$insert_match->group_letter = $group_letter;
			$insert_match->matchlink = $matchlink;
			$insert_match->execute();

			$_SESSION['success'] = 'Match has been added.';
			$_SESSION['compute_groups'] = true;
			$user->redirect('Admin-Tournament');
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